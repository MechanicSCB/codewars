<?php


namespace App\Runners;


use Illuminate\Http\Request;

class CoffeescriptRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'coffee';
        $this->cmdName = 'coffee';
        $this->hasSolutionFile = false;
        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        $script = "$this->solutionCode\n";

        foreach ($this->attempts as $attempt) {
            $script .= "console.log(JSON.stringify({$attempt['string']}))\n";
            $script .= "console.log('$this->separator');\n";
        }

        return $script;
    }
}
