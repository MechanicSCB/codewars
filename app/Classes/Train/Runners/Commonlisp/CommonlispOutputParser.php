<?php

namespace App\Classes\Train\Runners\Commonlisp;


use App\Classes\Train\Runners\Abstract\LangOutputParser;

class CommonlispOutputParser extends LangOutputParser
{
    public function parseRawOutput(string $rawOutput): array
    {
        $rawOutput = str_replace(['\n',' \"|separator|\" '],['','|separator|'],$rawOutput);

        return parent::parseRawOutput($rawOutput);
    }
}
