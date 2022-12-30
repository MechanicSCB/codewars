<?php

namespace App\Classes\Train\Runners\Cpp;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class CppScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'cpp';

    public function getScriptCode():string
    {
        $moduleName = $this->getModuleName();
        $script = "#include <iostream>\n#include \"nlohmann/json.hpp\"\n#include \"solution.hpp\"\nusing json = nlohmann::json;\n\nint main()\n{\n";

        foreach ($this->attempts as $attempt) {
            $attemptString = $this->getAttemptString($attempt);
            $attemptString = str_replace(['[', ']'], ['{', '}'], $attemptString);
            $script .= "\tstd::cout << json::array({{$moduleName}$attemptString}) << \"$this->separator\";\n";
        }

        $script .= "}";

        return $script;
    }

    protected function getModuleName(): string
    {
        $moduleName = @explode('class ', $this->solutionCode)[1];

        if($moduleName){
            $moduleName = @explode('{', $moduleName)[0];
            $moduleName = trim($moduleName). '::';
        }else{
            $moduleName = '';
        }

        return $moduleName;
    }

    protected function getCompileCmd(): ?string
    {
        return 'c++ script.cpp -lm 2>&1';
    }

    protected function getExecCmd(): ?string
    {
        return './a.out 2>&1';
    }



}
