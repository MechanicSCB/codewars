<?php


namespace App\Runners;


use Illuminate\Http\Request;

class HaskellRunner extends LangRunner
{
    // ERR on compile:  "x86_64-linux-gnu-gcc: fatal error: cannot execute 'cc1': execvp: No such file or directory"
    protected string $ext = 'hs';
    protected string $cmdName = 'ghc';
    protected string $className;

    public function saveSolutionToFile(string $code)
    {
        $moduleName = explode(' where', $this->solutionCode)[0];
        $moduleName = @explode('module ', $moduleName)[1] ?? 'Solution';
        $moduleName = trim($moduleName);
        $moduleNameWithoutPoints = str_replace('.', '',$moduleName);
        $this->solutionCode = str_replace($moduleName, $moduleNameWithoutPoints, $this->solutionCode);
        $this->className  = $moduleNameWithoutPoints;

        $this->solutionPath = str_replace('solution', $this->className, $this->solutionPath);

        file_put_contents($this->solutionPath, $this->solutionCode);
    }

    public function getScriptCode(): string
    {
        $script = "module Main where\nimport $this->className\nimport Numeric (showHex, showIntAtBase)\nimport Data.Char (intToDigit)\n\n";

        $script .= "main = print [";

        foreach ($this->attempts as $attempt) {
            $script .= "{$attempt['name']} ";

            foreach ($attempt['args'] as $arg){
                if(is_array($arg) || is_string($arg)){
                    $arg = json_encode($arg);
                }

                if(is_bool($arg)){
                    $arg = $arg ? 'True' : 'False';
                }

                $script .= "($arg) ";
            }

            $script .= ", ";
        }
        $script = substr($script, 0,-2);
        $script .= "]";

        return $script;
    }

    public function compileScriptFile(): ?string
    {
        $output = shell_exec("cd $this->folder && ghc script.hs 2>&1");
        //$output = shell_exec("cd $this->folder && ghc -o script script.hs 2>&1");
        //$output = shell_exec("cd $this->folder && ghc -o $this->folder/script script.hs 2>&1");
        echo $output;
        die();

        return $output;
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && ./script 2>&1");
    }

}
