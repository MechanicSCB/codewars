<?php

namespace App\Classes\Train\Runners\C;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class CScriptGenerator extends LangScriptGenerator
{


    protected string $ext = 'c';

    public function getScriptCode():string
    {
        $script = "#include <stdio.h>\n#include \"solution.h\"\n\nint main(void)\n{\n";

        foreach ($this->attempts as $key => $attempt) {
            $code = str_replace("{$attempt['name']} (", "{$attempt['name']}(", $this->solutionCode);
            $returnType = explode("{$attempt['name']}(", $code)[0];
            $returnType = explode("\n", $returnType);
            $returnType = $returnType[count($returnType) - 1];
            $returnType = trim($returnType);
            $returnTypes[$key] = $returnType;

            $code = str_replace("$returnType {$attempt['name']}(", "$returnType{$attempt['name']}(", $code);
            $code = str_replace("const ", "", $code);
            $args = explode("$returnType{$attempt['name']}(", $code)[1] ?? '';
            $args = explode(')', $args)[0];
            $args = explode(',', $args);
            $args = array_map(fn($v) => trim($v), $args);
            $args = array_map(fn($v) => ['type' => explode(' ', $v)[0], 'name' => explode(' ', $v)[1] ?? ''], $args);

            $attemptArgsStrings = [];

            foreach ($attempt['args'] as $attemptArgKey => $attemptArg) {
                $tmp = json_encode($attemptArg);
                $tmp = str_replace(['[', ']'], ['{', '}'], $tmp);
                $attemptArgsStrings[$attemptArgKey] = str_replace(['[', ']'], ['{', '}'], $tmp);
            }

            $indexedArgs = [];

            foreach ($args as $argKey => $arg) {
                $indexedArg = $arg['name'] . $key;

                // if array set t_size //int a[] = {121, 144, 19, 161, 19, 144, 19, 11};
                if (str_contains($arg['type'] . $arg['name'], "*")) {
                    $indexedArg = str_replace('*', '', $indexedArg);
                    $arg['type'] = str_replace('*', '', $arg['type']);
                    $indexedArg .= "[]";
                }

                if(@$attemptArgsStrings[$argKey] === null){
                    continue;
                }

                $script .= "\t{$arg['type']} $indexedArg = $attemptArgsStrings[$argKey];\n";
                $indexedArg = str_replace('[]', '', $indexedArg);
                $indexedArgs[] = $indexedArg;
            }


            if (in_array($returnType, ['int', 'long', 'bool'])) {
                $specifier = 'd';
            } elseif (str_starts_with($returnType, 'float')) {
                $specifier = 'f';
            } else {
                $specifier = 's';
            }

            $script .= "\tprintf(\"%$specifier\", {$attempt['name']}(" . implode(',', $indexedArgs) . "));\n";
            $script .= "\tprintf(\"%s\",\"$this->separator\");\n";
            //echo json_encode($args);
        }

        $script .= "}";

        $script = str_replace('\/', '/', $script);

        return $script;
    }

    protected function getCompileCmd(): ?string
    {
        return 'gcc script.c -lm 2>&1';
    }

    protected function getExecCmd(): ?string
    {
        return './a.out 2>&1';
    }

}
