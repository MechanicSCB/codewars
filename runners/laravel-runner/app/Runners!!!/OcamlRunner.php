<?php


namespace App\Runners;


use Illuminate\Http\Request;

class OcamlRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'ml';
        parent::__construct($request);
    }

    public function getScriptCode(): string
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
                $expected = $attempt['expected'] ?? @$this->attempts[0]['expected'];
                $returnType = gettype($expected);

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
