<?php

namespace App\Classes\Train\Runners\Dart;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;
use Illuminate\Support\Str;

class DartScriptGenerator extends LangScriptGenerator
{
    public function getScriptCode():string
    {
        $script = "import 'dart:convert';\nimport 'solution.dart';\nvoid main() {";

        foreach ($this->attempts as $attempt){
            $returnType = $this->getReturnType($attempt['name']);
            // not slashed $ get  syntax error
            $attemptString = $this->getAttemptString($attempt);

            if($returnType === "BigInt"){
                $script .= "\tprint($attemptString);\n";
            }else{
                $script .= "\tprint(jsonEncode($attemptString));\n";
            }

            $script .= "\tprint('$this->separator');\n";
        }

        $script .= "}";

        return $script;
    }

    protected function getAttemptString(array $attempt): string
    {
        $attemptString = parent::getAttemptString($attempt);
        $attemptString = str_replace('$', '\$', $attemptString);

        return $attemptString;
    }


    private function getReturnType(string $functionName):string
    {
        // removeDoubleSpaces and \n to space
        $code = preg_replace('/\s+/', ' ', $this->solutionCode);
        $code = str_replace(" $functionName (", " $functionName(", $code);

        $returnType = Str::before($code, " $functionName(");

        if(str_contains($returnType, ' ')){
            $returnType = Str::afterLast($returnType, ' ');
        }

        return $returnType;
    }
}
