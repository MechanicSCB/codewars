<?php

namespace App\Classes\Train\Runners\Commonlisp;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class CommonlispScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'lsp';
    protected string $cmd = 'sbcl --script';

    public function getScriptCode():string
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
}
