<?php

namespace App\Classes\Train\Runners\Scala;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class ScalaScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'scala';
    protected string $scriptFilename = 'Script';

    public function getScriptCode():string
    {
        $solutionFileName = $this->solutionValidator->getClassName();
        $functionNames = [];

        foreach ($this->attempts as $attempt) {
            if (in_array($attempt['name'], $functionNames)) {
                continue;
            }

            $functionNames[] = $attempt['name'];
        }

        $script = '';

        foreach ($functionNames as $functionName) {
            $script .= "import $solutionFileName.$functionName\n";
        }

        $script .= "object Script {\ndef main(args: Array[String]) = {\n";

        foreach ($this->attempts as $attempt) {
            $attemptString = $this->getAttemptString($attempt);
            // Seq(121, 144, 19, 161, 19, 144, 19, 11)
            $attemptString = str_replace(['[', ']'], ['Seq(', ')'], $attemptString);
            $script .= "\t\tprintln({$attemptString})\n";

            $script .= "\t\tprintln(\"$this->separator\")\n";
        }

        $script .= "\t}\n}";

        return $script;
    }

    protected function getCompileCmd(): ?string
    {
        $solutionFileName = $this->solutionValidator->getClassName();

        return "scalac $solutionFileName.scala Script.scala 2>&1";
    }

    protected function getExecCmd(): ?string
    {
        return "scala Script 2>&1";
    }

}
