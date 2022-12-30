<?php

namespace App\Classes\Train\Runners\R;


use App\Classes\Train\Runners\Abstract\LangOutputParser;

class ROutputParser extends LangOutputParser
{
    public function parseRawOutput(string $rawOutput): array
    {
        $rawOutput = str_replace('[1] \\"', '', $rawOutput);
        $rawOutput = str_replace('\"\n|separator|\"\n', '|separator|', $rawOutput);

        return parent::parseRawOutput($rawOutput);
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        $item = str_replace('\"','"',$item);

        if (@$this->attempts[$itemKey]['expected']['type'] === 'array'
            && gettype(json_decode($item)) !== 'array'
        ) {
            $item = (array) json_decode($item);
            $item = json_encode($item);
        }

        return parent::convertItem($item, $itemKey);
    }
}
