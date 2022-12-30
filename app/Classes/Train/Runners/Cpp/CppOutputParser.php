<?php

namespace App\Classes\Train\Runners\Cpp;


use App\Classes\Train\Runners\Abstract\LangOutputParser;

class CppOutputParser extends LangOutputParser
{
    public function parseRawOutput(string $rawOutput): array
    {
        return parent::parseRawOutput($rawOutput);
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        $item = substr($item, 1,-1);
        $item = str_replace('\"','"',$item);

        return parent::convertItem($item, $itemKey);
    }
}
