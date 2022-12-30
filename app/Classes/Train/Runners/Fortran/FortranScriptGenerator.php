<?php

namespace App\Classes\Train\Runners\Fortran;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class FortranScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'f90';

    public function getScriptCode():string
    {
        $script = "$this->solutionCode\n\nprogram main\n";

        foreach ($this->attempts as $attempt) {
            $attemptString = $this->getAttemptString($attempt);
            $script .= "\tprint *, $attemptString\n";
            $script .= "\tprint *, \"$this->separator\"\n";
        }

        $script .= "end program";

        return $script;
    }

    protected function getCompileCmd(): ?string
    {
        return 'gfortran script.f90 2>&1';
    }

    protected function getExecCmd(): ?string
    {
        return './a.out 2>&1';
    }
}
