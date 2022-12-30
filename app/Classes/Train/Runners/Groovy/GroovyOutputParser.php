<?php

namespace App\Classes\Train\Runners\Groovy;


use App\Classes\Train\Runners\Abstract\LangOutputParser;
use Illuminate\Support\Str;

class GroovyOutputParser extends LangOutputParser
{
    public function parseRawOutput(string $shellOutput): array
    {
        // Remove groovy warnings
        $shellOutput = Str::after($shellOutput, 'All illegal access operations will be denied in a future release\n');
        $shellOutput = '"' . $shellOutput;

        return parent::parseRawOutput($shellOutput);
    }
}
