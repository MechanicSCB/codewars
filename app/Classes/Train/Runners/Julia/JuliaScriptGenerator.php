<?php

namespace App\Classes\Train\Runners\Julia;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class JuliaScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'jl';

    public function getScriptCode():string
    {
        if($moduleName = $this->solutionValidator->getModuleName()){
            $script = "import JSON\ninclude(\"./Solution.jl\")\nusing .$moduleName\n";
        }else{
            $script = "import JSON\ninclude(\"./Solution.jl\")\n";
        }

        foreach ($this->attempts as $attempt){
            $attemptString = $this->getAttemptString($attempt);
            $script .= "println(JSON.json($attemptString))\n";
            $script .= "println(\"$this->separator\")\n";
        }

        return $script;
    }
}
