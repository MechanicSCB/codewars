<?php


namespace App\Runners;


use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FsharpRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'fsx';
        // DOTNET_NOLOGO=1 disable first dotnet run welcome message
        $this->cmdName = 'DOTNET_CLI_HOME=/tmp DOTNET_NOLOGO=1 dotnet fsi';
        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        $script = "$this->solutionCode\n\n";

        foreach ($this->attempts as $attempt) {
            if(str_contains($attempt['string'], '[')){
                $attempt['string'] = str_replace(',',';', $attempt['string']);
                $attempt['string'] = str_replace('];','],', $attempt['string']);
                $script .= "System.Console.WriteLine({$attempt['string']})\n";
            }else{
                $args = array_map('json_encode', $attempt['args'] );
                $args = implode(' ', $args);
                $script .= "System.Console.WriteLine({$attempt['name']} $args)\n";
            }

            $script .= "System.Console.WriteLine(\"$this->separator\")\n";
        }

        return $script;
    }

    public function handleRawOutput(string $shellOutput): array
    {
        //// remove first exec welcome message
        //if(str_contains($shellOutput, 'Welcome to .NET 6.0!')){
        //    $shellOutput = Str::after($shellOutput,"--------------------------------------------------------------------------------------\n");
        //}

        // remove warning
        if(str_contains($shellOutput, 'This warning may be disabled using')){
            $shellOutput = Str::afterLast($shellOutput,"nowarn \"3391\".\n\n");
        }

        return parent::handleRawOutput($shellOutput);
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        if(is_array($this->attempts[$itemKey]['expected'])){
            $item = str_replace(['(','); ',')'],['[','],',']'],$item);
        }

        return parent::convertItem($item,$itemKey);
    }

}
