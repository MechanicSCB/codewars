<?php

namespace App\Classes\Train\Runners\Scala;


use App\Classes\Train\Runners\Abstract\LangOutputParser;

class ScalaOutputParser extends LangOutputParser
{
    public function parseRawOutput(string $rawOutput): array
    {
        return parent::parseRawOutput($rawOutput);
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        if (@$this->attempts[$itemKey]['expected']['type'] === 'array') {
            $item = str_replace('List(', '[', $item);
            $item = substr($item, 0, -1) . ']';
        }

        return parent::convertItem($item, $itemKey);
    }
}
