<?php

namespace App\Classes\Train\Runners\Fsharp;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;
use Illuminate\Support\Str;

class FsharpScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'fsx';
    protected string $cmd = 'DOTNET_CLI_HOME=/tmp DOTNET_NOLOGO=1 dotnet fsi';

    public function getScriptCode():string
    {
        $script = "$this->solutionCode\n\n";

        foreach ($this->attempts as $attempt) {
            $attemptString = $this->getAttemptString($attempt);

            if(str_contains($attemptString, '[')){
                $attemptString = str_replace(',',';', $attemptString);
                $attemptString = str_replace('];','],', $attemptString);
                $script .= "System.Console.WriteLine($attemptString)\n";
            }else{
                $args = array_map('json_encode', $attempt['args'] );
                $args = implode(' ', $args);
                $script .= "System.Console.WriteLine({$attempt['name']} $args)\n";
            }

            $script .= "System.Console.WriteLine(\"$this->separator\")\n";
        }

        return $script;
    }

}
