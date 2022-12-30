<?php

namespace App\Classes\Train\Runners\Javascript;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class JavascriptScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'js';
    protected string $cmd = 'node';

    public function getScriptCode():string
    {
        $scriptCode = "$this->solutionCode\n";

        foreach ($this->attempts as $attempt){
            $attemptString = $this->getAttemptString($attempt);
            $scriptCode .= "console.log(JSON.stringify($attemptString))\n";
            $scriptCode .= "console.log('$this->separator');\n";
        }

        return $scriptCode;
    }
}
