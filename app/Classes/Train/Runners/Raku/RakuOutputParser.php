<?php

namespace App\Classes\Train\Runners\Raku;


use App\Classes\Train\Runners\Abstract\LangOutputParser;

class RakuOutputParser extends LangOutputParser
{
    public function parseRawOutput(string $rawOutput): array
    {
        if(str_contains($rawOutput, $this->separator)){
            $head = explode('Saw 1 occurrence of deprecated code.',$rawOutput)[0];
            $tail = @explode('--------------------------------------------------------------------------------\n',$rawOutput)[1];
            $rawOutput = $head . $tail;
            $rawOutput = str_replace('\nPlease contact the author to have these occurrences of deprecated code\nadapted, so that this message will disappear!\n','', $rawOutput);
        }

        return parent::parseRawOutput($rawOutput);
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        return parent::convertItem($item, $itemKey);
    }
}
