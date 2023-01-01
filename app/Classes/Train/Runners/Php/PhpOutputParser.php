<?php

namespace App\Classes\Train\Runners\Php;


use App\Classes\Train\Runners\Abstract\LangOutputParser;
use Illuminate\Support\Str;

class PhpOutputParser extends LangOutputParser
{
    public function parseRawOutput(string $rawOutput): array
    {
        $rawOutput = str_replace('\\\n', '\n', $rawOutput);

        // If error returned
        if(str_starts_with($rawOutput,'<!DOCTYPE html>')){
            $rawOutput = $this->parseRawOutputErrors($rawOutput);
        }

        $rawOutput = str_replace('\"', '"', $rawOutput);
        $rawOutput = str_replace('\\\/', '/', $rawOutput);

        if (!str_starts_with($rawOutput, '"[')){
            $rawOutput = str_replace("<","&lt;",$rawOutput);

            return [$rawOutput];
        }

        return json_decode(substr($rawOutput,1,-1));
    }

    protected function parseRawOutputErrors(string $errorOutput):string
    {
        $errorOutput = Str::before($errorOutput, '#0 /var/www/html');
        $errorOutput = Str::after($errorOutput, '<!--');
        $errorOutput = trim($errorOutput);

        return $errorOutput;
    }
}
