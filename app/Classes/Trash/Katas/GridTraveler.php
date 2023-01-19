<?php


namespace App\Classes\Trash\old;


class GridTraveler
{
    public function solve()
    {
        //df(tmr(@$this->start), 777);
        $m = $n = 14;
        $res = $this->gridTraveler($m, $n);

        df(tmr(@$this->start), $res);

    }

    function gridTravelerTab($m, $n)
    {
        $grid = array_fill(0, $m + 2, 0);
        $grid = array_map(fn($v) => array_fill(0, $n + 2, 0), $grid);
        $grid[1][1] = 1;

        for ($x = 1; $x <= $m; $x++) {
            for ($y = 1; $y <= $n; $y++) {
                $grid[$x + 1][$y] += $grid[$x][$y];
                $grid[$x][$y + 1] += $grid[$x][$y];
            }
        }

        //df(tmr(@$this->start), array_map(fn($v) => implode(',',$v), $grid));
        return $grid[$m][$n];
    }

    function gridTravelerMemo($m, $n, &$memo = [])
    {
        if (isset($memo[$m][$n]) || isset($memo[$n][$m])) {
            return $memo[$m][$n] ?? $memo[$n][$m];
        }

        if ($m <= 0 || $n <= 0) {
            return 0;
        }

        if ($m === 1 || $n === 1) {
            return 1;
        }

        $memo[$m - 1][$n] = $this->gridTravelerMemo($m - 1, $n, $memo);
        $memo[$m][$n - 1] = $this->gridTravelerMemo($m, $n - 1, $memo);
        $memo[$m][$n] = $memo[$m - 1][$n] + $memo[$m][$n - 1];

        return $memo[$m][$n];
    }

    function gridTravelerRec($m, $n)
    {
        if ($m === 1 || $n === 1) {
            return 1;
        }

        return $this->gridTravelerRec($m - 1, $n) + $this->gridTravelerRec($m, $n - 1);
    }

}
