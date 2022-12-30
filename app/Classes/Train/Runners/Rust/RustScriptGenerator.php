<?php

namespace App\Classes\Train\Runners\Rust;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class RustScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'rs';

    public function getScriptCode():string
    {
        $script = "$this->solutionCode\n\nfn main() {\n";

        foreach ($this->attempts as $attempt) {
            $attemptString = $this->getAttemptString($attempt);
            $script .= "println!(\"{:?}\", $attemptString);\n";
            $script .= "println!(\"$this->separator\");\n";
        }

        $script .= "}";

        return $script;
    }

    protected function getAttemptString(array $attempt): string
    {
        $attemptString = parent::getAttemptString($attempt);
        $attemptString = str_replace('[', 'vec![', $attemptString);

        return $attemptString;
    }

    protected function getCompileCmd(): ?string
    {
        return 'rustc script.rs 2>&1';
    }

    protected function getExecCmd(): ?string
    {
        return './script';
    }
}
