<?php

namespace App\Classes\Train\Runners\Elixir;


use App\Classes\Train\Runners\Abstract\LangOutputParser;
use Illuminate\Support\Str;

class ElixirOutputParser extends LangOutputParser
{
    public function parseRawOutput(string $rawOutput): array
    {
        $rawOutput = Str::after($rawOutput, '|separator|:');
        $rawOutput = str_replace('|separator|:','|separator|','"'.$rawOutput);

        return parent::parseRawOutput($rawOutput);
    }
}
