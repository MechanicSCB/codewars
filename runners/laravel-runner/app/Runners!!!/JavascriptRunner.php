<?php


namespace App\Runners;


class JavascriptRunner extends LangRunner
{
    protected string $ext = 'js';
    protected string $cmdName = 'node';
    protected bool $hasSolutionFile = false;

    public function getScriptCode(): string
    {
        $script = "$this->solutionCode\n";

        foreach ($this->attempts as $attempt) {
            $script .= "console.log(JSON.stringify({$attempt['string']}))\n";
            $script .= "console.log('$this->separator');\n";
        }

        return $script;
    }
}
