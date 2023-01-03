<?php


namespace App\Runners;


use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DartRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'dart';
        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        $script = "import 'dart:convert';\nimport 'solution.dart';\nvoid main() {";

        foreach ($this->attempts as $attempt){
            $returnType = $this->getReturnType($attempt['name']);
            // not slashed $ get  syntax error
            $attempt['string'] = str_replace('$', '\$', $attempt['string']);

            if($returnType === "BigInt"){
                $script .= "\tprint({$attempt['string']});\n";
            }else{
                $script .= "\tprint(jsonEncode({$attempt['string']}));\n";
            }

            $script .= "\tprint('$this->separator');\n";
        }

        $script .= "}";

        return $script;
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
