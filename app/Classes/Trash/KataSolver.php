<?php


namespace App\Classes\Trash;


use App\Classes\Trash\Katas\PathFinder2;
use Illuminate\Support\Str;
use function Symfony\Component\String\b;

class KataSolver
{
    public function solve()
    {
        $inputs = [[["red", "red"]], [["red", "green", "blue"]], [["gray", "black", "purple", "purple", "gray", "black"]], [[]], [["red", "green", "blue", "blue", "red", "green", "red", "red", "red"]]];
        $outputs = [1, 0, 3, 0, 4];
        $n = 2;
        $input = $inputs[$n];

        $res = $this->beeramid(1500, 2);
        $res = $this->beeramid(10, 2);
        df(tmr(@$this->start), $res);
        df(tmr(@$this->start), $inputs[$n], $outputs[$n], $res);
    }

    function beeramid($money, $price)
    {
        $n = intval($money/$price);
        $s = 1;
        $lev = 0;

        while($n >= $s**2){
            $lev++;
            $n -= $s**2;
            $s++;
        }

        return $lev;
    }


}













