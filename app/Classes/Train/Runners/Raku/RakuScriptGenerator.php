<?php

namespace App\Classes\Train\Runners\Raku;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class RakuScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'rakumod';

    public function getScriptCode():string
    {
        $script = "use v6;\nuse lib '.';\nuse Solution;\n\nsub MAIN() {\n";

        foreach ($this->attempts as $attempt) {
            $attemptString = $this->getAttemptString($attempt);
            $script .= "\tsay to-json $attemptString;\n";
            $script .= "\tsay \"$this->separator\";\n";
        }

        $script .= "}";

        return $script;
    }
}
