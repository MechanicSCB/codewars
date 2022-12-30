<?php


namespace App\Runners;


use Illuminate\Http\Request;

class PerlRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'pm';
        $this->solutionFileName = $this->getSolutionFileName($request['code']);
        parent::__construct($request);
    }

    public function getSolutionFileName(string $code): string
    {
        $packageName = @explode('package ', $_POST['code'])[1];
        $packageName = @explode(';', $packageName)[0];
        $packageName = trim( $packageName);

        if(! $packageName){
            echo json_encode(['ERROR: class name undefined!']);
            $this->removeTempFolder();
            die;
        }

        return $packageName;
    }

    public function getScriptCode(): string
    {
        $script = "use strict;\nuse warnings;\nuse JSON;\n\nuse $this->solutionFileName;\n";

        foreach ($this->attempts as $attempt){
            foreach ($attempt['args'] as $arg){
                if(is_bool($arg)){
                    $attempt['string'] = str_replace(['true', 'false'], ['1','0'],$attempt['string']);
                }
            }

            $script .= "print encode_json $this->solutionFileName::{$attempt['string']};\n";
            $script .= "print('$this->separator');\n";
        }

        return $script;
    }

    public function saveScriptToFile(string $code)
    {
        $this->ext = 'pl';

        parent::saveScriptToFile($code);
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && perl -I $this->folder script.pl 2>&1");
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        if(is_array($this->attempts[$itemKey]['expected'])){
            // ""[[42,2500],[246,84100]]"" -> "[[42,2500],[246,84100]]"
            $item = json_decode($item);
        }

        return parent::convertItem($item,$itemKey);
    }

}
