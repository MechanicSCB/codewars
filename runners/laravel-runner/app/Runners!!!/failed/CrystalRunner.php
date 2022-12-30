<?php


namespace App\Runners;


use Illuminate\Http\Request;

class CrystalRunner extends LangRunner
{
    // ERR shell_exec "cc: fatal error: '-fuse-linker-plugin', but liblto_plugin.so not found"
    protected string $ext = 'cr';

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

    public function execScriptFile(): string
    {
        //return shell_exec("$this->cmdName run $this->scriptPath 2>&1");
    }

}
