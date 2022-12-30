<?php

namespace App\Classes\Train\Runners\Clojure;


use App\Classes\Train\Runners\Abstract\LangOutputParser;

class ClojureOutputParser extends LangOutputParser
{
    public function parseRawOutput(string $rawOutput): array
    {
        return parent::parseRawOutput($rawOutput);
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        if(str_contains($item, 'error')){
            $item = json_encode($item);
        }

        return parent::convertItem($item, $itemKey);
    }
}
