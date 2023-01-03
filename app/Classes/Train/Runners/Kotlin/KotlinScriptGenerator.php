<?php

namespace App\Classes\Train\Runners\Kotlin;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class KotlinScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'kt';

    public function getScriptCode():string
    {
        $script = "$this->solutionCode\n\nfun main(args: Array<String>) {\n";

        foreach ($this->attempts as $attempt) {
            $attemptString = $this->getAttemptString($attempt);
            // TODO ref
            $attemptString = str_replace(['[', ']'], ['arrayOf(', ')'], $attemptString);
            //$attemptString = str_replace(['[', ']'], ['intArrayOf(', ')'], $attemptString);
            //$attemptString = str_replace(['[', ']'], ['listOf(', ')'], $attemptString);
            $script .= "\tprintln({$attemptString})\n";
            $script .= "\tprintln(\"$this->separator\")\n";
        }

        $script .= "}";

        return $script;
    }

    protected function getCompileCmd(): ?string
    {
        return 'kotlinc script.kt -include-runtime -d script.jar 2>&1';
    }

    protected function getExecCmd(): ?string
    {
        return "java -jar script.jar 2>&1";
    }

}
