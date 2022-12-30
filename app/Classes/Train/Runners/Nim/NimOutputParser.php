<?php

namespace App\Classes\Train\Runners\Nim;


use App\Classes\Train\Runners\Abstract\LangOutputParser;
use Illuminate\Support\Str;

class NimOutputParser extends LangOutputParser
{
    public function parseRawOutput(string $shellOutput): array
    {
        $shellOutput = substr($shellOutput,1);
        $shellOutput = '"'. ltrim($shellOutput, '.');

        return parent::parseRawOutput($shellOutput);
    }
}
