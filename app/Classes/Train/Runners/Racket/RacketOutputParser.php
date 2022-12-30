<?php

namespace App\Classes\Train\Runners\Racket;


use App\Classes\Train\Runners\Abstract\LangOutputParser;

class RacketOutputParser extends LangOutputParser
{
    public function parseRawOutput(string $shellOutput): array
    {
        $shellOutput = str_replace('\"|separator|\"','|separator|',$shellOutput);

        //df(tmr(@$this->start), $shellOutput);
        return parent::parseRawOutput($shellOutput);
    }


    protected function convertItem(mixed $item, int $itemKey)
    {
        return parent::convertItem($item, $itemKey);
    }
}
