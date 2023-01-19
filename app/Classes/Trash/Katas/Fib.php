<?php


namespace App\Classes\Trash;


class Fib
{
    public function solve()
    {

        $n = 50;
        $res2 = $this->fibRec($n);
        $res = $this->fibTab($n);

        df(tmr(@$this->start), $n, $res2, $res);
    }

    function fibTab($n)
    {
        $table = array_fill(0, $n + 2, 0);
        $table[1] = 1;

        for ($i = 0; $i <= $n - 1; $i++) {
            $table[$i + 1] += $table[$i];
            $table[$i + 2] += $table[$i];
        }

        return $table[$n];
    }

    function fib($n)
    {
        $tab = [0, 1];

        for ($i = 2; $i <= $n; $i++) {
            $tab[$i] = $tab[$i - 1] + $tab[$i - 2];
        }

        return $tab[$n];
    }

    function fibRec($n, &$memo = [])
    {
        if (array_key_exists($n, $memo)) {
            return $memo[$n];
        }

        if ($n <= 2) {
            return 1;
        }

        return $memo[$n] = $this->fibRec($n - 1, $memo) + $this->fibRec($n - 2, $memo);
    }
}
