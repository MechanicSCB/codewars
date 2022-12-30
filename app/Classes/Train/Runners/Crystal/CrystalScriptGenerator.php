<?php

namespace App\Classes\Train\Runners\Crystal;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class CrystalScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'cr';
    protected string $cmd = 'crystal run';

    public function getScriptCode():string
    {
        $script = "require \"json\"\nrequire \"./solution\"\n";

        foreach ($this->attempts as $attempt){
            $attemptString = $this->getAttemptString($attempt);
            $attemptString = str_replace('[]', '[] of Int32',$attemptString);
            $script .= "puts {$attemptString}.to_json\n";
            $script .= "puts \"$this->separator\"\n";
        }

        return $script;
    }
}
