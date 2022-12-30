<?php

namespace App\Classes\Train\Runners\Go;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class GoScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'go';

    public function getScriptCode():string
    {
        $script = "package main\n\nimport (\n\"encoding/json\"\n\"fmt\"\n)\n\nfunc main() {\n";

        foreach ($this->attempts as $key => $attempt){
            // only int array done yet
            $attemptString = $this->getAttemptString($attempt);
            // TODO ref to get argument type
            $attemptString = str_replace([']', '['], ['}', '[]int{'], $attemptString);

            $script .= "\tj$key, err := json.Marshal($attemptString)
            if err != nil {
                fmt.Println(err)
            } else {
                fmt.Println(string(j$key))
            }\n";

            $script .= "\tfmt.Println(string(\"$this->separator\"))\n";
        }

        $script .= "}";

        return $script;
    }

    protected function getExecCmd(): ?string
    {
        return "GOCACHE=CURRENT_FOLDER/cache go run script.go solution.go 2>&1";
    }

}
