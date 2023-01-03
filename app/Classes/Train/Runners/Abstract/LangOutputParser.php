<?php

namespace App\Classes\Train\Runners\Abstract;

use App\Models\Kata;

abstract class LangOutputParser
{
    protected string $separator = "|separator|";

    public function __construct(
        protected Kata $kata,
        protected string $solutionCode,
        protected string $lang,
        protected array $attempts,
    )
    {
    }

    public function parseRawOutput(string $rawOutput): array
    {
        //df(tmr(@$this->start), $rawOutput);
        if(! $shellOutput = json_decode($rawOutput)){
            return [$rawOutput];
        }

        $separator = $this->getSeparatorToExplode();
        $output = explode($separator, $shellOutput);
        $output = array_map('trim', $output);

        // clean null array last element after explode if it's not an error
        if (str_contains($shellOutput, $this->separator) && $output[count($output) - 1] === "") {
            unset($output[count($output) - 1]);
        }

        //df(tmr(@$this->start), $output);

        foreach ($output as $itemKey => &$item) {
            // skip ERROR to json (return null) decoding
            if (! str_contains($shellOutput, $this->separator)) {
                continue;
            }

            $item = $this->convertItem($item, $itemKey);
        }


        return $output;
    }

    protected function getSeparatorToExplode(): string
    {
        return $this->separator;
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        if($item === 'null'){
            $item = null;
        }

        if($item === 'True'){
            $item = 'true';
        }

        if($item === 'False'){
            $item = 'false';
        }

        $decoded = json_decode($item, 1);

        if(
            $decoded === null
            && $item !== null
            //&& is_string(@$this->attempts[$itemKey]['expected'])
        ) {
            $decoded = json_decode("\"$item\"", 1) ?? $item;
        }

        return $decoded;
    }

    //function isJson($string) {
    //    json_decode($string);
    //    return json_last_error() === JSON_ERROR_NONE;
    //}
}
