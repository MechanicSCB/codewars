<?php

namespace App\Classes\Train\Runners\Lua;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class LuaScriptGenerator extends LangScriptGenerator
{
    public function getScriptCode():string
    {
        $script = "json = require \"json\"\nlocal solution = require 'solution'\n\n";

        foreach ($this->attempts as $attempt){
            $attemptString = $this->getAttemptString($attempt);
            $attemptString = str_replace([']', '['], ['}', '{'], $attemptString);

            if(str_contains($this->solutionCode, ".{$attempt['name']}")){
                $script .= "print(json.encode(solution.$attemptString))\n";
            }else{
                $attemptString = str_replace($attempt['name'], '', $attemptString);
                $script .= "print(json.encode(solution$attemptString))\n";
            }

            $script .= "print('$this->separator');\n";
        }

        return $script;
    }
}
