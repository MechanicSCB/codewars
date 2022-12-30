<?php


namespace App\Runners;


use Illuminate\Http\Request;

class RRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'r';
        $this->cmdName = 'Rscript';
        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        $script = "library(rjson)\nsource('solution.r')\n";

        foreach ($this->attempts as $attempt) {
            foreach ($attempt['args'] as $arg){
                if(is_bool($arg)){
                    $attempt['string'] = str_replace(['true','false'],['TRUE','FALSE'],$attempt['string'] );
                }
            }

            $attempt['string'] = str_replace([']', '['], [')', 'c('], $attempt['string']);
            $script .= "toJSON({$attempt['string']})\n";
            $script .= "print('$this->separator')\n";
        }

        return $script;
    }

    public function handleRawOutput(string $shellOutput): array
    {
        $separator = $this->getSeparatorToExplode();
        $output = explode($separator, $shellOutput);
        $output = array_map('trim', $output);

        // clean null array last element after explode if it's not an error
        if (str_contains($shellOutput, $this->separator)) {
            unset($output[count($output) - 1]);
        }

        foreach ($output as $key => &$item) {
            // skip ERROR to json (return null) decoding
            if (! str_contains($shellOutput, $this->separator)) {
                continue;
            }

            $item = str_replace('[1] ', '', $item);
            $item = json_decode($item);

            $expected = array_key_exists('expected', $this->attempts[$key]) ? $this->attempts[$key]['expected'] : $this->attempts[0]['expected'];

            if(is_array($expected)){
                $item = json_decode($item);
                $item = (array) $item;
                $item = json_encode($item);
            }

            $item = json_decode($item);
        }

        return $output;
    }

    protected function getSeparatorToExplode(): string
    {
        return "\n[1] \"$this->separator\"\n";
    }
}
