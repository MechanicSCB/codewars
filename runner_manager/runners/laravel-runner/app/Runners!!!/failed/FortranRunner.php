<?php


namespace App\Runners;


use Illuminate\Http\Request;

class FortranRunner extends LangRunner
{
    // "gfortran: fatal error: cannot execute 'f951': execvp: No such file or directory"
    public function __construct(Request $request)
    {
        $this->ext = 'f90';
        $this->compileCmd = 'gfortran';
        $this->hasSolutionFile = false;
        $this->needCompile = true;
        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        $script = "$this->solutionCode\n\nprogram main\n";

        foreach ($this->attempts as $attempt) {
            $script .= "\tprint *, {$attempt['string']}\n";
            $script .= "\tprint *, \"$this->separator\"\n";
        }

        $script .= "end program";

        return $script;
    }

    public function compileScriptFile(): ?string
    {
        return shell_exec("cd $this->folder && gfortran script.f90 2>&1");
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && ./a.out 2>&1");
    }

}
