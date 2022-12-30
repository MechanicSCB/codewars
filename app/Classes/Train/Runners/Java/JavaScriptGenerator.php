<?php

namespace App\Classes\Train\Runners\Java;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class JavaScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'java';

    public function getScriptCode():string
    {
        $solutionFileName = $this->solutionValidator->getClassName();
        $script = "import java.util.Arrays;\nclass script {\npublic static void main(String[] args) {\n";

        foreach ($this->attempts as $key => $attempt) {
            $attemptString = $this->getAttemptString($attempt);

            if(gettype(@$attempt['args'][0][0]) === 'string'){
                $attemptString = str_replace([']', '['], ['}', 'new String[]{'], $attemptString);
                //$attemptString = str_replace([']', '['], [')', 'Arrays.asList('], $attemptString);
            }else{
                $attemptString = str_replace([']', '['], ['}', 'new int[]{'], $attemptString);
            }

            //if(str_ends_with($functionsInfo[$attempt['name']]['return_type'] ?? '', '[]')){
            if(@$attempt['expected']['type'] === 'array'){
                $script .= "System.out.println(Arrays.toString($solutionFileName.$attemptString));\n";
            }else{
                $script .= "System.out.println($solutionFileName.$attemptString);\n";
            }

            $script .= "System.out.println(\"$this->separator\");\n";
        }

        $script .= "\t}\n}";

        return $script;
    }

    protected function getCompileCmd(): ?string
    {
        return 'javac script.java 2>&1';
    }

    protected function getExecCmd(): ?string
    {
        //return 'java script 2>&1';
        return "$this->cmd $this->scriptFilename 2>&1";
    }

}
