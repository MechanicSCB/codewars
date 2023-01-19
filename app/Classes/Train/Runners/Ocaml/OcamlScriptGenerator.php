<?php

namespace App\Classes\Train\Runners\Ocaml;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class OcamlScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'ml';

    public function getScriptCode():string
    {
        $script = "#use \"solution.ml\" ;;\n\nopen Printf\n";

        foreach ($this->attempts as $attempt) {
            $argsStr = '';

            $args = $attempt['args'];

            foreach ($args as $arg){
                $tmp = json_encode($arg);

                if(is_array($arg)){
                    $tmp = str_replace(',', ';', $tmp);
                }

                $argsStr.="($tmp) ";
                $expected = $attempt['expected']['value'];
                $returnType = $attempt['expected']['type'];
                // TODO get expected to define return type
                //df(tmr(@$this->start), $returnType, $expected);

                if($returnType === 'array'){
                    if(gettype(@$expected[0]) === 'string'){
                        $arrItemType = 's';
                    }elseif(gettype(@$expected[0]) === 'integer'){
                        $arrItemType = 'd';
                    }elseif(gettype(@$expected[0]) === 'float'){
                        $arrItemType = 'f';
                    }else{
                        $arrItemType = 's';
                    }

                    $printCommand = "List.iter (printf \"%$arrItemType \")";
                }elseif ($returnType === 'string'){
                    $printCommand = 'print_string';
                }elseif ($returnType === 'integer'){
                    $printCommand = 'print_int';
                }elseif ($returnType === 'double'){
                    $printCommand = 'print_float';
                }elseif ($returnType === 'boolean'){
                    $printCommand = "printf \"%b \"";
                }else{
                    $printCommand = 'print_string';
                }
            }

            $argsStr = rtrim($argsStr);

            $script .= "let () = $printCommand ({$attempt['name']} $argsStr)\n";
            $script .= "let () = print_string (\"$this->separator\")\n";
        }

        return $script;
    }
}
