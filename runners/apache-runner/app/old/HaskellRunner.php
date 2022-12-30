<?php


class HaskellRunner extends LangRunner
{
    public function __construct(protected array $request)
    {
        $this->ext = 'hs';
        parent::__construct($request);
    }

    public function saveSolutionToFile(string $code)
    {
        $moduleName = explode(' where', $code)[0];
        $moduleName = @explode('module ', $moduleName)[1] ?? 'Solution';
        $moduleName = trim($moduleName);
        $moduleNameWithoutPoints = str_replace('.', '', $moduleName);
        $this->solutionCode = str_replace($moduleName, $moduleNameWithoutPoints, $this->solutionCode);
        $this->solutionFileName = $moduleNameWithoutPoints;

        parent::saveSolutionToFile($this->solutionCode);
    }

    public function getScriptCode(): string
    {
        $script = "module Main where\nimport $this->solutionFileName\nimport Numeric (showHex, showIntAtBase)\nimport Data.Char (intToDigit)\n\n";

        $script .= "main = print [";

        foreach ($this->attempts as $attempt) {
            $script .= "{$attempt['name']} ";

            foreach ($attempt['args'] as $arg) {
                if (is_array($arg) || is_string($arg)) {
                    $arg = json_encode($arg);
                }

                if (is_bool($arg)) {
                    $arg = $arg ? 'True' : 'False';
                }

                $script .= "($arg) ";
            }

            $script .= ", ";
        }
        $script = substr($script, 0, -2);
        $script .= "]";

        return $script;
    }

    public function compileScriptFile(): ?string
    {
        $compileOutput = shell_exec("cd $this->folder && ghc -o script script.hs 2>&1");

        if (str_ends_with($compileOutput, "Linking script ...\n")) {
            return null;
        } else {
            return $compileOutput;
        }
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && ./script 2>&1");
    }

    public function handleRawOutput(string $execOutput): array
    {
        $execOutput = str_replace(['Just '], [''], trim($execOutput ?? ''));
        $output = json_decode($execOutput, 1);

        if (! $output) {
            $output = explode(',', substr($execOutput, 1, -1));
        }

        foreach ($output ?? [] as $itemKey => &$item) {
            $item = $this->convertItem($item, $itemKey);
        }

        return $output;
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        if($item === 'Nothing'){
            $item = 'null';
        }

        return parent::convertItem($item,$itemKey);
    }

}
