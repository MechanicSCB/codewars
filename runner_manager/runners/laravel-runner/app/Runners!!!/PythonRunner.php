<?php


namespace App\Runners;


use Illuminate\Http\Request;

class PythonRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'py';
        $this->cmdName = 'python3';
        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        $script = "import sys\nimport solution\nimport json\n";

        foreach ($this->attempts as $attempt){
            foreach ($attempt['args'] as $arg){
                if(is_bool($arg)){
                    $attempt['string'] = str_replace(['true','false'],['True','False'],$attempt['string'] );
                }
            }

            $script .= "print (json.dumps(solution.{$attempt['string']}))\n";
            $script .= "print (\"$this->separator\")\n";
        }

        return $script;
    }

}
