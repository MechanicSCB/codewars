<?php

namespace App\Classes\Train\Runners\Rust;


use App\Classes\Train\Runners\Abstract\LangOutputParser;

class RustOutputParser extends LangOutputParser
{
    public function parseRawOutput(string $rawOutput): array
    {
        return parent::parseRawOutput($rawOutput);
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        if(str_starts_with($item, 'Some((')){
            $item = str_replace(['Some((', '))'], ['[',']'], $item);
        }

        return parent::convertItem($item, $itemKey);
    }
}
