<?php

namespace App\Classes\Train\Runners\Ruby;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class RubyScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'rb';

    public function getScriptCode():string
    {
        $script = "require 'json'\nrequire  './solution.rb'\n\n";

        foreach ($this->attempts as $attempt) {
            $attemptString = $this->getAttemptString($attempt);
            $script .= "puts $attemptString.to_json\n";
            $script .= "puts \"$this->separator\"\n";
        }

        return $script;
    }
}
