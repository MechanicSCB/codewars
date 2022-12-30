<?php


class JuliaRunner extends LangRunner
{
    public function __construct(protected array $request)
    {
        $this->ext = 'jl';
        $this->solutionFileName = 'Solution';
        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        if($moduleName = $this->getModuleName($this->solutionCode)){
            // check allowed module name
            if (! preg_match("/^[A-z][A-z\d_-]+$/", $moduleName)) {
                echo json_encode(["error: module name is not defined!\n<br> please define module a valid name or remove module definition"]);
                $this->removeTempFolder();
                die();
            }

            $script = "import JSON\ninclude(\"./Solution.jl\")\nusing .$moduleName\n";
        }else{
            $script = "import JSON\ninclude(\"./Solution.jl\")\n";
        }

        foreach ($this->attempts as $attempt){
            $script .= "println(JSON.json({$attempt['string']}))\n";
            $script .= "println(\"$this->separator\")\n";
        }

        return $script;
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && julia script.jl 2>&1");
    }

    private function getModuleName(string $code):?string
    {
        if (!$moduleName = @explode("module ", $code)[1]) {
            return null;
        }

        $moduleName = str_replace(["\n", ' '], '|||', $moduleName);

        return explode('|||', $moduleName)[0];
    }

}
