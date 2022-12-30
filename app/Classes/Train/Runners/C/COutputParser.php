<?php

namespace App\Classes\Train\Runners\C;


use App\Classes\Train\Runners\Abstract\LangOutputParser;

class COutputParser extends LangOutputParser
{
    public function parseRawOutput(string $rawOutput): array
    {
        return parent::parseRawOutput($rawOutput);
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        if (@$this->attempts[$itemKey]['expected']['type'] === 'boolean') {
            $item = json_encode((bool) $item);
        }

        if (@$this->attempts[$itemKey]['expected']['type'] === 'string') {
            $item = json_encode( $item);
        }

        return parent::convertItem($item, $itemKey);
    }
}
