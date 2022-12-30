<?php


namespace App\Runners;


use Illuminate\Http\Request;

class TypescriptRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'ts';
        $this->cmdName = 'ts-node';

        parent::__construct($request);
        $this->solutionFileName = $this->getClassName();
    }

    protected function getClassName(): string
    {
        if (! str_contains($this->solutionCode, 'export function')) {
            $className = explode('{', $this->solutionCode)[0];
            $className = str_replace('interface', 'class', $className);
            $className = @explode('class ', $className)[1];
            $className = trim($className);
        }

        return $className ?? '';
    }

    public function saveSolutionToFile(string $code)
    {
        file_put_contents("$this->folder/solution.$this->ext", $code);
    }

    public function getScriptCode(): string
    {
        $script = "import solution = require('./solution');\n";

        foreach ($this->attempts as $attempt) {
            if ($this->solutionFileName !== '') {
                $script .= "console.log(JSON.stringify(solution.$this->solutionFileName.{$attempt['string']}));\n";
            } else {
                $script .= "console.log(JSON.stringify(solution.{$attempt['string']}));\n";
            }

            $script .= "console.log('$this->separator');\n";
        }

        return $script;
    }
}
