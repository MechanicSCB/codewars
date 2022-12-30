<?php


namespace App\Runners;


class RubyRunner extends LangRunner
{
    protected string $ext = 'rb';

    public function getScriptCode(): string
    {
        $script = "require 'json'\nrequire '$this->folder/solution.rb'\n\n";

        foreach ($this->attempts as $attempt) {
            $script .= "puts {$attempt['string']}.to_json\n";
            $script .= "puts \"$this->separator\"\n";
        }

        return $script;
    }
}
