<?php


namespace App\Classes\Trash;


class GridTraveler
{
    public function solve()
    {
        //df(tmr(@$this->start), 777);
        $m = $n = 14;
        $res = $this->gridTraveler($m,$n);

        df(tmr(@$this->start), $res);

    }

    function gridTraveler1($m, $n)
    {
        if ($m === 1 || $n === 1) {
            return 1;
        }

        return $this->gridTraveler($m - 1, $n) + $this->gridTraveler($m, $n - 1);
    }

    function gridTraveler($m, $n, $memo = [])
    {
        if (isset($memo[$m][$n]) || isset($memo[$n][$m]) ){
            return $memo[$m][$n] ?? $memo[$n][$m];
        }

        if ($m <= 0 || $n <= 0) {
            return 0;
        }

        if ($m === 1 || $n === 1) {
            return 1;
        }

        $memo[$m-1][$n] = $this->gridTraveler($m - 1, $n, $memo);
        $memo[$m][$n-1] = $this->gridTraveler($m, $n - 1, $memo);
        $memo[$m][$n] = $memo[$m-1][$n] + $memo[$m][$n-1];

        return $memo[$m][$n];
    }

}
