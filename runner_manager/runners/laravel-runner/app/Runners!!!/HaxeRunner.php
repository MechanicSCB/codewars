<?php


namespace App\Runners;


use Illuminate\Http\Request;

class HaxeRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'hx';
        $this->solutionFileName = $this->getSolutionFileName($request['code']);
        $this->scriptFileName = 'Script';
        parent::__construct($request);
    }

    public function getSolutionFileName(string $solutionCode)
    {
        $className = @explode('class ', $solutionCode)[1];
        $className = @explode('{', $className)[0];

        return trim( $className);
    }

    public function getScriptCode(): string
    {
        $script = "import $this->solutionFileName;\nclass Script {\n\tstatic function main() {\n";

        foreach ($this->attempts as $key => $attempt) {
            $script .= "\t\thaxe.Log.trace(haxe.Json.stringify($this->solutionFileName.{$attempt['string']}), null);\n";
            $script .= "\t\thaxe.Log.trace(\"$this->separator\", null);\n";
        }

        $script .= "\t}\n}";

        return $script;
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && haxe --main Script --interp 2>&1");
    }

}
