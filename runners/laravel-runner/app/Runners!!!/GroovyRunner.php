<?php


namespace App\Runners;


use Illuminate\Http\Request;

class GroovyRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'groovy';
        $this->solutionFileName = $this->getSolutionFileName($request['code']);
        parent::__construct($request);
    }

    public function getSolutionFileName(string $code): string
    {
        $className = explode('{', $code)[0];
        $className = @explode('class', $className)[1];
        $className = trim( $className ?? '');

        if(! $this->solutionFileName){
            echo json_encode(['ERROR: class name undefined!']);
            die;
        }

        return $className;
    }

    public function getScriptCode(): string
    {
        $script = '';

        foreach ($this->attempts as $attempt){
            $script .= "println groovy.json.JsonOutput.toJson($this->solutionFileName.{$attempt['string']});";
            $script .= "println(\"$this->separator\")\n";
        }

        return $script;
    }

    public function handleRawOutput(string $shellOutput): array
    {
        $separator = $this->getSeparatorToExplode();
        $output = explode($separator, $shellOutput);
        $output = array_map('trim', $output);

        // clean null array last element after explode if it's not an error
        if (str_contains($shellOutput, $this->separator)) {
            unset($output[count($output) - 1]);
        }

        foreach ($output as &$item) {
            // remove groovy WARNINGS from output
            if(str_starts_with($item,'WARNING: ')){
                $exploded = explode("All illegal access operations will be denied in a future release\n", $item);
                $item = $exploded[1];
            }

            // skip ERROR to json (return null) decoding
            if (! str_contains($shellOutput, $this->separator)) {
                continue;
            }

            $item = json_decode($item, 1);
        }

        return $output;
    }
}
