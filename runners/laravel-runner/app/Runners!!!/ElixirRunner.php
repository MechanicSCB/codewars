<?php


namespace App\Runners;


use Illuminate\Http\Request;

class ElixirRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'exs';
        $this->hasSolutionFile = false;
        parent::__construct($request);
        $this->solutionFileName = $this->getSolutionCode();
    }

    public function getSolutionCode(): string
    {
        if (! str_contains($this->solutionCode, 'export function')) {
            $moduleName = explode(' do', $this->solutionCode)[0];
            $moduleName = @explode('defmodule ', $moduleName)[1];
        }

        return $moduleName;
    }

    public function getScriptCode(): string
    {
        $script = "$this->solutionCode\n";

        foreach ($this->attempts as $attempt) {
            $script .= "IO.inspect $this->solutionFileName.{$attempt['string']}, label: \"$this->separator\"\n";
        }

        return $script;
    }

    public function handleRawOutput(string $shellOutput): array
    {
        $separator = $this->getSeparatorToExplode();
        $output = explode($separator, $shellOutput);
        $output = array_map('trim', $output);

        $error = array_shift($output);

        if (! str_contains($shellOutput, $separator)) {
            $output = [$error];
        } else {
            foreach ($output as $itemKey => &$item) {
                // skip ERROR to json (return null) decoding

                $item = $this->convertItem($item, $itemKey);
            }
        }

        return $output;
    }

    protected function getSeparatorToExplode(): string
    {
        return "$this->separator: ";
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        if(is_array($this->attempts[$itemKey]['expected'])){
            $item = str_replace(['{','}'],['[',']'],$item);
        }

        return parent::convertItem($item,$itemKey);
    }

}
