<?php

namespace App\Classes\Train\Runners\Haskell;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class HaskellScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'hs';

    public function getScriptCode():string
    {
        $solutionFileName = $this->solutionValidator->solutionFilename;
        $script = "module Main where\nimport $solutionFileName\nimport Numeric (showHex, showIntAtBase)\nimport Data.Char (intToDigit)\n\n";

        $script .= "main = print [";

        foreach ($this->attempts as $attempt) {
            $script .= "{$attempt['name']} ";

            foreach ($attempt['args'] as $arg) {
                if (is_array($arg) || is_string($arg)) {
                    $arg = json_encode($arg);
                }

                if (is_bool($arg)) {
                    $arg = $arg ? 'True' : 'False';
                }

                $script .= "($arg) ";
            }

            $script .= ", ";
        }
        $script = substr($script, 0, -2);
        $script .= "]";

        $script = str_replace('\/', '/', $script);

        return $script;
    }

    protected function getCompileCmd(): ?string
    {
        return 'ghc -o script script.hs 2>&1';
    }

    protected function getExecCmd(): ?string
    {
        return './script 2>&1';
    }
}
