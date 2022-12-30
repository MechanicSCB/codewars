<?php


namespace App\Runners;


use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KotlinRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'kt';
        $this->hasSolutionFile = false;
        $this->needCompile = true;

        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        $script = "$this->solutionCode\n\nfun main(args: Array<String>) {\n";

        foreach ($this->attempts as $attempt) {
            //$attempt['string'] = str_replace(['[', ']'], ['intArrayOf(', ')'], $attempt['string']);
            $attempt['string'] = str_replace(['[', ']'], ['listOf(', ')'], $attempt['string']);
            $script .= "\tprintln({$attempt['string']})\n";
            $script .= "\tprintln(\"$this->separator\")\n";
        }

        $script .= "}";

        return $script;
    }

    public function compileScriptFile(): ?string
    {
        return shell_exec("cd $this->folder && kotlinc script.kt -include-runtime -d script.jar 2>&1");
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && java -jar script.jar 2>&1");
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        if(is_string(@$this->attempts[$itemKey]['expected'])){
            $item = "\"$item\"";
        }

        return parent::convertItem($item, $itemKey);
    }

}
