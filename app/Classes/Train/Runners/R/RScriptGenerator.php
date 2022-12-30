<?php

namespace App\Classes\Train\Runners\R;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class RScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'r';

    public function getScriptCode():string
    {
        $script = "library(rjson)\nsource('solution.r')\n";

        foreach ($this->attempts as $attempt) {
            $attemptString = $this->getAttemptString($attempt);

            foreach ($attempt['args'] as $arg){
                if(is_bool($arg)){
                    $attemptString= str_replace(['true','false'],['TRUE','FALSE'],$attemptString);
                }
            }

            $attemptString= str_replace([']', '['], [')', 'c('], $attemptString);
            $script .= "toJSON({$attemptString})\n";
            $script .= "print('$this->separator')\n";
        }

        return $script;
    }

    protected function getExecCmd(): ?string
    {
        return "Rscript script.r 2>&1";
    }

}
