<?php

namespace App\Classes\Train\Runners\Fortran;


use App\Classes\Train\Runners\Abstract\LangOutputParser;

class FortranOutputParser extends LangOutputParser
{
    public function parseRawOutput(string $rawOutput): array
    {
        return parent::parseRawOutput($rawOutput);
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        return parent::convertItem($item, $itemKey);
    }
}
