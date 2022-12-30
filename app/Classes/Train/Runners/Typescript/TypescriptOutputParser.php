<?php

namespace App\Classes\Train\Runners\Typescript;


use App\Classes\Train\Runners\Abstract\LangOutputParser;

class TypescriptOutputParser extends LangOutputParser
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
