<?php

namespace App\Classes\Train\Runners\Coffeescript;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class CoffeescriptScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'coffee';
    protected string $cmd = 'coffee';

    public function getScriptCode():string
    {
        $script = "$this->solutionCode\n";

        foreach ($this->attempts as $attempt) {
            $attemptString = $this->getAttemptString($attempt);
            $script .= "console.log(JSON.stringify($attemptString))\n";
            $script .= "console.log('$this->separator');\n";
        }

        return $script;
    }
}
