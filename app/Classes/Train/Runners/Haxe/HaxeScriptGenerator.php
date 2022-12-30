<?php

namespace App\Classes\Train\Runners\Haxe;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class HaxeScriptGenerator extends LangScriptGenerator
{
    protected string $scriptFilename = 'Script';
    protected string $ext = 'hx';
    protected string $cmd = 'haxe --main Script --interp 2>&1';

    public function getScriptCode():string
    {
        $solutionFileName = $this->getSolutionFileName();
        $script = "import $solutionFileName;\nclass Script {\n\tstatic function main() {\n";

        foreach ($this->attempts as $key => $attempt) {
            $attemptString = $this->getAttemptString($attempt);
            $script .= "\t\thaxe.Log.trace(haxe.Json.stringify($solutionFileName.$attemptString), null);\n";
            $script .= "\t\thaxe.Log.trace(\"$this->separator\", null);\n";
        }

        $script .= "\t}\n}";

        return $script;
    }

    public function getSolutionFileName():string
    {
        $className = @explode('class ', $this->solutionCode)[1];
        $className = @explode('{', $className)[0];

        return trim( $className);
    }

    protected function getExecCmd(): ?string
    {
        return "haxe --main Script --interp 2>&1";
    }


}
