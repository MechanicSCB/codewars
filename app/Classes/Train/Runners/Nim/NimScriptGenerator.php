<?php

namespace App\Classes\Train\Runners\Nim;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class NimScriptGenerator extends LangScriptGenerator
{
    //protected string $ext = 'nim';

    public function getScriptCode():string
    {
        $script = "import solution\nimport std/json\n\n";

        foreach ($this->attempts as $key => $attempt){
            $attemptString = $this->getAttemptString($attempt);
            $attemptString = str_replace('[', '@[', $attemptString);
            $script .= "let output$key = %* {$attemptString}\n";
            $script .= "echo output$key\n";
            $script .= "echo \"$this->separator\"\n";
        }

        return $script;
    }

    protected function getExecCmd(): ?string
    {
        return "nim c -r --verbosity:0 script.nim 2>&1";
    }

}
