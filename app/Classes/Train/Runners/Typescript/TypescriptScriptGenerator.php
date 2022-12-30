<?php

namespace App\Classes\Train\Runners\Typescript;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class TypescriptScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'ts';
    protected string $cmd = 'ts-node';

    public function getScriptCode():string
    {
        $solutionFileName = $this->solutionValidator->getClassName();
        $script = "import solution = require('./solution');\n";

        foreach ($this->attempts as $attempt) {
            $attemptString = $this->getAttemptString($attempt);

            if ($solutionFileName !== '') {
                $script .= "console.log(JSON.stringify(solution.$solutionFileName.$attemptString));\n";
            } else {
                $script .= "console.log(JSON.stringify(solution.$attemptString));\n";
            }

            $script .= "console.log('$this->separator');\n";
        }

        return $script;
    }

    //protected function getCompileCmd(): ?string
    //{
    //    $solutionFileName = $this->solutionValidator->getClassName();
    //
    //    return "scalac $solutionFileName.scala Script.scala 2>&1";
    //}
    //
    //protected function getExecCmd(): ?string
    //{
    //    return "scala Script 2>&1";
    //}

}
