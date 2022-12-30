<?php


namespace App\Runners;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RakuRunner extends LangRunner
{

    public function __construct(Request $request)
    {
        $this->ext = 'rakumod';
        $this->solutionFileName = 'Solution';
        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        $script = "use v6;\nuse lib '.';\nuse Solution;\n\nsub MAIN() {\n";

        foreach ($this->attempts as $attempt) {
            $script .= "\tsay to-json {$attempt['string']};\n";
            $script .= "\tsay \"$this->separator\";\n";
        }

        $script .= "}";

        return $script;
    }

    public function saveScriptToFile(string $code)
    {
        $this->ext = 'raku';
        parent::saveScriptToFile($code);
    }

    public function handleRawOutput(string $shellOutput): array
    {
        if(str_contains($shellOutput, $this->separator)){
            $head = explode('Saw 1 occurrence of deprecated code.',$shellOutput)[0];
            $tail = @explode("--------------------------------------------------------------------------------\n",$shellOutput)[1];
            $shellOutput = $head . $tail;
        }

        return parent::handleRawOutput($shellOutput);
    }
}
