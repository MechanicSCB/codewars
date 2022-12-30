<?php

namespace App\Classes\Train\Runners\Csharp;


use App\Classes\Train\Runners\Abstract\LangOutputParser;

class CsharpOutputParser extends LangOutputParser
{
    public function parseRawOutput(string $rawOutput): array
    {
        return parent::parseRawOutput($rawOutput);
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        if (@$this->attempts[$itemKey]['expected']['type'] === 'array') {
            $exploded = explode('|elem|', $item);

            foreach ($exploded as &$v) {
                if (is_numeric($v)) {
                    $v = +$v;
                }
            }

            if($exploded === ['']){
                $exploded = [];
            }

            $item = json_encode($exploded);
        }

        return parent::convertItem($item, $itemKey);
    }

}
