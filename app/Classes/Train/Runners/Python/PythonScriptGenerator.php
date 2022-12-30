<?php

namespace App\Classes\Train\Runners\Python;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class PythonScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'py';
    protected string $cmd = 'python3';

    public function getScriptCode():string
    {
        $scriptCode = "import sys\nimport solution\nimport json\n";

        foreach ($this->attempts as $attempt){
            $attemptString = $this->getAttemptString($attempt);

            foreach ($attempt['args'] as $arg){
                if(is_bool($arg)){
                    $attemptString = str_replace(['true','false'],['True','False'],$attemptString);
                }
            }

            $scriptCode .= "print (json.dumps(solution.$attemptString))\n";
            $scriptCode .= "print (\"$this->separator\")\n";
        }

        return $scriptCode;
    }

    protected function getArgsString(array $attempt): string
    {
        $stringArgs = parent::getArgsString($attempt);
        $stringArgs = str_replace(['true', 'false'], ['True', 'False'], $stringArgs);

        return $stringArgs;
    }

}
