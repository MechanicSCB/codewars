<?php


class RustRunner extends LangRunner
{
    public function __construct(protected array $request)
    {
        $this->ext = 'rs';
        parent::__construct($request);
    }

    public function getScriptCode(): string
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

    public function compileScriptFile(): ?string
    {
        return shell_exec("cd $this->folder && rustc script.rs 2>&1");
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && ./script");
    }

    protected function getAttemptString(array $attempt): string
    {
        $attemptString = $attempt['string'];

        $attemptString = str_replace('[', 'vec![', $attemptString);
        //$attemptString = str_replace('[', '&[', $attemptString);

        return $attemptString;
    }
}
