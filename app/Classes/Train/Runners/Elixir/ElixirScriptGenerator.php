<?php

namespace App\Classes\Train\Runners\Elixir;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class ElixirScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'exs';

    public function getScriptCode():string
    {
        $script = "$this->solutionCode\n";

        foreach ($this->attempts as $attempt) {
            $attemptString = $this->getAttemptString($attempt);
            $moduleName = $this->getModuleName();

            $script .= "IO.inspect $moduleName.$attemptString, label: \"$this->separator\"\n";
        }

        return $script;
    }

    public function getModuleName(): string
    {
        if (! str_contains($this->solutionCode, 'export function')) {
            $moduleName = explode(' do', $this->solutionCode)[0];
            $moduleName = @explode('defmodule ', $moduleName)[1];
        }

        return $moduleName ?? '';
    }

}
