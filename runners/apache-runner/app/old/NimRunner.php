<?php


class NimRunner extends LangRunner
{
    public function __construct(protected array $request)
    {
        $this->ext = 'nim';
        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        $script = "import solution\nimport std/json\n\n";

        foreach ($this->attempts as $key => $attempt){
            $attempt['string'] = str_replace('[', '@[', $attempt['string']);
            $script .= "let output$key = %* {$attempt['string']}\n";
            $script .= "echo output$key\n";
            $script .= "echo \"$this->separator\"\n";
        }

        return $script;
    }

    public function compileScriptFile(): ?string
    {
        return null;
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && nim c -r --verbosity:0 script.nim");
    }
}
