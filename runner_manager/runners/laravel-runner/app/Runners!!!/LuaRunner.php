<?php


namespace App\Runners;


use Illuminate\Http\Request;

class LuaRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'lua';
        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        $script = "json = require \"json\"\nlocal solution = require 'solution'\n\n";

        foreach ($this->attempts as $attempt){
            $attempt['string'] = str_replace([']', '['], ['}', '{'], $attempt['string']);

            if(str_contains($this->solutionCode, ".{$attempt['name']}")){
                $script .= "print(json.encode(solution.{$attempt['string']}))\n";
            }else{
                $attempt['string'] = str_replace($attempt['name'], '', $attempt['string']);
                $script .= "print(json.encode(solution{$attempt['string']}))\n";
            }

            $script .= "print('$this->separator');\n";
        }

        return $script;
    }
}
