<?php

namespace App\Classes\Train\Runners\Perl;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class PerlScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'pl';

    public function getScriptCode():string
    {
        $solutionFileName = $this->solutionValidator->getSolutionFileName();
        $script = "use strict;\nuse warnings;\nuse JSON;\n\nuse $solutionFileName;\n";

        foreach ($this->attempts as $attempt){
            $attemptString = $this->getAttemptString($attempt);

            foreach ($attempt['args'] as $arg){
                if(is_bool($arg)){
                    $attemptString = str_replace(['true', 'false'], ['1','0'],$attemptString);
                }
            }

            $script .= "print encode_json $solutionFileName::{$attemptString};\n";
            $script .= "print('$this->separator');\n";
        }

        return $script;
    }

    protected function getExecCmd(): ?string
    {
        return 'perl -I CURRENT_FOLDER script.pl 2>&1';
    }

}
