<?php


namespace App\Classes\Trash;


class AllConstruct
{
    public function solve()
    {
        $input = ['abcdef', ['ab', 'abc', 'cd', 'def', 'abcd', 'ef']]; // true - 1
        $input = ['abcdef', ['ab', 'abc', 'cd', 'def', 'abcd', 'ef']]; // true - 3
        $input = ['skateboard', ['bo', 'rd', 'ate', 't', 'ska', 'sk', 'boar']]; // false - 0
        $input = ['enterapotentpot', ['a', 'p', 'ent', 'enter', 'ot', 'o', 't']]; // true
        $input = ['eeeeeeeeeeeeeeeeeeef', ['e', 'ee', 'eee', 'eeee', 'eeeee', 'eeeeee', 'eeeeeee']]; // false
        $input = ['eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeef', ['e', 'ee', 'eee', 'eeee', 'eeeee', 'eeeeee', 'eeeeeee',]]; // false
        $input = ['purple', ['purp', 'p', 'ur', 'le', 'purpl']]; // false


        $res = $this->allConstruct(...$input);

        df(tmr(@$this->start), $input, $res);
    }


    function allConstruct($target, $wordBank, &$memo = [])
    {
        if(array_key_exists($target, $memo)){
            return $memo[$target];
        }

        if($target === ''){
            return true;
        }

        $variants = [];

        foreach ($wordBank as $word){
            if(str_starts_with($target,$word)){
                $reminder = substr($target, strlen($word));
                $res = $this->allConstruct($reminder, $wordBank, $memo);

                if($res === true){
                    $variants[] = [$word];
                }elseif(count($res)){
                    foreach ($res as $var){
                        $variants[] = [$word, ...$var];
                    }
                }
            }
        }

        $memo[$target] = $variants;
        return $variants;
    }

    function countConstruct($target, $wordBank, &$memo = [])
    {
        if (array_key_exists($target, $memo)) {
            return $memo[$target];
        }

        if ($target === '') {
            return 1;
        }

        $ways = 0;

        foreach ($wordBank as $word) {
            if (str_starts_with($target, $word)) {
                $remainder = substr($target, strlen($word));
                $ways += $this->countConstruct($remainder, $wordBank, $memo);
            }
        }

        $memo[$target] = $ways;

        return $ways;
    }

    function canConstruct($target, $wordBank, &$memo = [])
    {
        if (array_key_exists($target, $memo)) {
            return $memo[$target];
        }

        if ($target === '') {
            return true;
        }

        foreach ($wordBank as $word) {
            if (str_starts_with($target, $word)) {
                $remainder = substr($target, strlen($word));
                $res = $this->canConstruct($remainder, $wordBank, $memo);

                if ($res) {
                    $memo[$target] = $res;

                    return $res;
                }
            }
        }

        $memo[$target] = false;

        return false;
    }

}
