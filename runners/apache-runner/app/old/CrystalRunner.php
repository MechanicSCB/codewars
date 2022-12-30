<?php


class CrystalRunner extends LangRunner
{
    public function __construct(protected array $request)
    {
        $this->ext = 'cr';
        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        $script = "require \"json\"\nrequire \"./solution\"\n";

        foreach ($this->attempts as $attempt){
            $attempt['string'] = str_replace('[]', '[] of Int32',$attempt['string']);
            $script .= "puts {$attempt['string']}.to_json\n";
            $script .= "puts \"$this->separator\"\n";
        }

        return $script;
    }

    public function compileScriptFile(): ?string
    {
        return null;
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && crystal run script.cr 2>&1");
    }

}
