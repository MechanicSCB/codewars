<?php


class CppRunner extends LangRunner
{
    public function __construct(protected array $request)
    {
        $this->ext = 'hpp';
        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        $moduleName = @explode('class ', $this->solutionCode)[1];

        if($moduleName){
            $moduleName = @explode('{', $moduleName)[0];
            $moduleName = trim($moduleName). '::';
        }else{
            $moduleName = '';
        }

        $script = "#include <iostream>
#include \"nlohmann/json.hpp\"
#include \"solution.hpp\"

using json = nlohmann::json;

int main()
{\n";

        foreach ($this->attempts as $attempt) {
            $attempt['string'] = str_replace(['[', ']'], ['{', '}'], $attempt['string']);
            $script .= "\tstd::cout << json::array({{$moduleName}{$attempt['string']}}) << \"$this->separator\";\n";
        }

        $script .= "}";

        return $script;
    }

    public function saveScriptToFile(string $code)
    {
        file_put_contents("$this->folder/script.cpp", $code);
    }

    public function compileScriptFile(): ?string
    {
        $sourceFolder = explode('/tmp', $this->folder )[0] . '/src/cpp';

        $this->recurseCopy($sourceFolder, $this->folder);

        return shell_exec("cd $this->folder && g++ script.cpp -lm 2>&1");
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && ./a.out 2>&1");
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        return json_decode($item, 1)[0];
    }


}
