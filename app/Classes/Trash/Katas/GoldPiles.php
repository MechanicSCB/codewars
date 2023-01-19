<?php


namespace App\Classes\Trash\Katas;


class GoldPiles
{
    public function solve()
    {
        $inputs = [[[4, 7, 2, 9, 5, 2]], [[10, 1000, 2, 1]], [[10, 1000, 2]], [[140, 649, 340, 982, 105, 86, 56, 610, 340, 879]]];
        $outputs = [[18, 11], [1001, 12], [12, 1000], [3206, 981]];
        $n = 0;
        $input = $inputs[$n][0];
        //$input = [4, 7, 2, 9, 5, 2,140, 649, 340, 982, 105, 86, 56, 610, 340, 879, 982, 105, 86, 56, 610, 340];
        //$input = [659,946,192,549,766,213,29,866,392];
        $input = [659,946,192,549,766,213,29,866,392];

        $res = $this->distribution_of_tab($input);
        $assert = json_encode($res) === json_encode($outputs[$n]);

        df(tmr(@$this->start), $input, $outputs[$n], $res, $assert);
    }

    function distribution_of_tab(array $golds)
    {
        //
    }

    function distribution_of_rec(array $golds)
    {
        $res1 = $this->bestChoice($golds);
        $res2= array_sum($golds) - $res1;

        return [$res1, $res2];
    }

    function bestChoice(array $golds, &$memo = []): int
    {
        $key = implode(',', $golds);

        if(array_key_exists($key, $memo)){
            return $memo[$key];
        }

        if (count($golds) <= 2) {
            return max($golds);
        }

        $l = array_sum($golds) - $this->bestChoice(array_slice($golds, 1), $memo);
        $r = array_sum($golds) - $this->bestChoice(array_slice($golds, 0, -1), $memo);

        $memo[$key] = max($l,$r);
        return max($l,$r);
    }


}
