<?php

namespace App\Classes\Train\Runners\Perl;


use App\Classes\Train\Runners\Abstract\LangOutputParser;

class PerlOutputParser extends LangOutputParser
{
    public function parseRawOutput(string $shellOutput): array
    {
        //df(tmr(@$this->start), $shellOutput);
        return parent::parseRawOutput($shellOutput);
    }


    protected function convertItem(mixed $item, int $itemKey)
    {
        $item =  parent::convertItem($item, $itemKey);

        if($item === 'true'){
            $item = true;
        }

        if($item === 'false'){
            $item = false;
        }

        return $item;
    }
}
