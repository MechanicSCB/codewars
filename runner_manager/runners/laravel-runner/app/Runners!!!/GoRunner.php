<?php


namespace App\Runners;


use Illuminate\Http\Request;

class GoRunner extends LangRunner
{
    protected string $ext = 'go';

    public function getSolutionCode(): string
    {
        if (! $packageName = $this->getPackageName($this->solutionCode)) {
            echo json_encode(["ERROR: package name is undefined\nPlease set a valid package name"]);
            die();
        }

        return str_replace("package $packageName", 'package main', $this->solutionCode);
    }

    public function getScriptCode(): string
    {
        $script = "package main\n\nimport (\n\"encoding/json\"\n\"fmt\"\n)\n\nfunc main() {\n";

        foreach ($this->attempts as $key => $attempt){
            // only int array done yet
            $attempt['string'] = str_replace([']', '['], ['}', '[]int{'], $attempt['string']);

            $script .= "\tj$key, err := json.Marshal({$attempt['string']})
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


    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && GOCACHE=$this->folder/cache go run script.go solution.go 2>&1");
    }

    function getPackageName(string $code): ?string
    {
        // removeDoubleSpaces;
        $code = preg_replace('/\s+/', ' ', $code);

        if (count($tmp = explode('package ', $code, 2)) !== 2) {
            return null;
        }

        $tmp = str_replace('//', ' //', $tmp[1]);
        $packageName = explode(' ', $tmp)[0];

        // check allowed package name
        if (! preg_match("/^[A-z][A-z\d_-]+$/", $packageName)) {
            return null;
        }

        return $packageName;
    }
}
