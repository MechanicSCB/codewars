<?php


namespace App\Runners;


use Illuminate\Http\Request;

class CommonlispRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'lsp';
        $this->cmdName = 'sbcl --script';
        $this->hasSolutionFile = false;
        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        $script = "$this->solutionCode\n";

        foreach ($this->attempts as $attempt) {
            $argsStr = '';

            $args = $attempt['args'];

            foreach ($args as $arg){
                $tmp = json_encode($arg);

                if(is_array($arg)){
                    $tmp = str_replace(['[', ']', ','], ["'(", ')', ' '], $tmp);
                }

                $argsStr.="$tmp ";
            }

            $argsStr = rtrim($argsStr);

            $script .= "(print ({$attempt['name']} $argsStr))\n";
            $script .= "(print \"$this->separator\")\n";
        }

        return $script;
    }

    protected function getSeparatorToExplode(): string
    {
        return "\"$this->separator\"";
    }
}
