<?php


namespace App\Runners;


use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

class CsharpRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'cs';
        $this->compileCmd = 'mcs';
        $this->cmdName = 'mono';
        $this->hasSolutionFile = false;
        $this->scriptFileName = 'Script';
        $this->needCompile = true;

        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        $script = Str::beforeLast($this->solutionCode, '}');
        $script .= "\npublic static void Main()\n\t{\n";

        foreach ($this->attempts as $attempt){
            $attemptString = $this->getAttemptString($attempt);
            $script .= "\t\tSystem.Console.WriteLine(string.Join(\"|elem|\",$attemptString));\n";
            $script .= "\t\tSystem.Console.WriteLine(\"$this->separator\");\n";
        }

        $script .= "\t}\n}";

        return $script;
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && mono Script.exe 2>&1");
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        if(str_contains($item, '|elem|')){
            $exploded = explode('|elem|', $item);

            foreach ($exploded as &$v){
                if(is_numeric($v)){
                    $v = +$v;
                }
            }

            $item = json_encode($exploded);
        }

        return parent::convertItem($item, $itemKey);
    }

    protected function getAttemptString(array $attempt):?string
    {
        $functionsParams = $this->getFunctionsParams($this->solutionCode);

        if($functionParams = @$functionsParams[$attempt['name']]){
            $argsString = $this->getArgsStringFromFunctionParams( $attempt, $functionParams,);
            $attemptString = "{$attempt['name']}($argsString)";
        }

        return $attemptString;
    }

    function getArgsStringFromFunctionParams(array $attempt,array $functionParams): string
    {
        $argsString = '';

        foreach ($attempt['args'] as $key => $arg){
            $argString = json_encode($attempt['args'][$key]);

            if(is_array($arg)){
                $argString = str_replace([']', '['],['}', "new {$functionParams['args'][$key]['type']}{"],$argString);
            }

            $argsString .= "$argString, ";
        }

        $argsString = trim($argsString, ', ');

        return $argsString;
    }

    function getFunctionsParams(string $code): array
    {
        $functions = [];
        $rows = $this->getFunctionsRows($code);

        foreach ($rows as $row){
            $functionName = $this->getFunctionNameFromRow($row);
            $functions[$functionName] = [
                'row' => $row,
                'name' => $functionName,
                'return_type' => $this->getFunctionReturnTypeFromRow($row, $functionName),
                'args' => $this->getFunctionArgsFromRow($row, $functionName),
            ];
        }

        return $functions;
    }

    function getFunctionsRows(string $code): array
    {
        $code = str_replace(['public static ', '{ static ', "{static "], $this->separator, $code);
        $rows = explode('|separator|', $code);
        array_shift($rows);

        foreach ($rows as &$row){
            $row = str_replace(['{','=>'], $this->separator, $row);
            $row = Str::before($row, $this->separator);
            $row = trim($row);
        }

        return $rows;
    }

    #[Pure] function getFunctionNameFromRow(string $functionRow): string
    {
        return trim(Str::afterLast(Str::before($functionRow, '('), ' '));
    }

    #[Pure] function getFunctionReturnTypeFromRow(string $functionRow, string $functionName): string
    {
        return trim(Str::before($functionRow, " $functionName"));
    }

    function getFunctionArgsFromRow(string $functionRow, string $functionName): array
    {
        $args = Str::after($functionRow, '(');
        $args = Str::beforeLast($args, ')');
        $args = explode(',', $args);

        foreach ($args as &$arg){
            $name = trim(Str::afterLast($arg, ' '));
            $type = trim(Str::beforeLast($arg, ' '));

            if($type === $name){
                $type = null;
            }

            $arg = ['type' => $type, 'name' => $name];
        }

        return $args;
    }
}
