<?php

namespace App\Classes\Train\Runners\Haskell;


use App\Classes\Train\Runners\Abstract\LangOutputParser;

class HaskellOutputParser extends LangOutputParser
{
    public function parseRawOutput(string $rawOutput): array
    {
        $output = str_replace(['True','False'], ['true','false'],$rawOutput ?? '');
        $output = json_decode($output, 1);
        $output = json_decode($output, 1) ?? [$output];

        return $output;
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        return parent::convertItem($item, $itemKey);
    }
}
