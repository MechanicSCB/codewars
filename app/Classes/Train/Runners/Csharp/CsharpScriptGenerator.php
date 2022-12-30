<?php

namespace App\Classes\Train\Runners\Csharp;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;
use Illuminate\Support\Str;

class CsharpScriptGenerator extends LangScriptGenerator
{
    protected string $scriptFilename = 'Script';
    protected string $ext = 'cs';

    public function getScriptCode(): string
    {
        $script = Str::beforeLast($this->solutionCode, '}');
        $script .= "\npublic static void Main()\n\t{\n";

        foreach ($this->attempts as $attempt) {
            $attemptString = $this->getAttemptString($attempt);
            $script .= "\t\tSystem.Console.WriteLine(string.Join(\"|elem|\",$attemptString));\n";
            $script .= "\t\tSystem.Console.WriteLine(\"$this->separator\");\n";
        }

        $script .= "\t}\n}";

        //df(tmr(@$this->start), $script);

        return $script;
    }

    protected function getCompileCmd(): ?string
    {
        return "mcs $this->scriptFilename.$this->ext 2>&1";
    }

    protected function getExecCmd(): ?string
    {
        return "mono Script.exe 2>&1";
    }

    protected function getArgsString(array $attempt): string
    {
        // TODO ref!
        $stringArgs = parent::getArgsString($attempt);
        $functionsInfo = $this->solutionValidator->getFunctionsInfo();
        //df(tmr(@$this->start), $attempt, $functionsInfo, $stringArgs);

        foreach ($attempt['args'] as $argKey => $argData) {
            $argData = @$functionsInfo[$attempt['name']]['args'][$argKey];

            if (@$argData['type'] === 'string[]') {
                $stringArgs = str_replace([']', '['], ['}', 'new string[]{'], $stringArgs);
                break;
            } elseif (@$argData['type'] === 'int[]') {
                $stringArgs = str_replace([']', '['], ['}', 'new int[]{'], $stringArgs);
                break;
            } else {
                $type = str_contains($stringArgs, '["') ? 'string' : 'int';
                $stringArgs = str_replace([']', '['], ['}', "new {$type}[]{"], $stringArgs);
                break;
            }
        }

        return $stringArgs;
    }
}
