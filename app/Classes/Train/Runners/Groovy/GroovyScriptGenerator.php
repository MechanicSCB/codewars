<?php

namespace App\Classes\Train\Runners\Groovy;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class GroovyScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'groovy';

    public function getScriptCode():string
    {
        $className = $this->getClassName();

        // TODO replace to solution validator
        if(! $className){
            return "Error: class name undefined!!";
        }

        $script = '';

        foreach ($this->attempts as $attempt){
            $attemptString = $this->getAttemptString($attempt);
            $script .= "println groovy.json.JsonOutput.toJson($className.$attemptString);";
            $script .= "println(\"$this->separator\")\n";
        }

        return $script;
    }

    public function getClassName(): string
    {
        $className = explode('{', $this->solutionCode)[0];
        $className = @explode('class', $className)[1];
        $className = trim( $className ?? '');

        return $className ?? '';
    }

}
