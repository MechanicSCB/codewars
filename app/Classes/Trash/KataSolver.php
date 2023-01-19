<?php


namespace App\Classes\Trash;


use Illuminate\Support\Str;
use function Symfony\Component\String\b;

class KataSolver
{

    public function solve()
    {
        $inputs = [[[[4, -3, 1, -10], [2, 1, 3, 0], [-1, 2, -5, 17]]], [[[2, 1, 3, 10], [-3, -2, 7, 5], [3, 3, -4, 7]]], [[[3, 2, 0, 7], [-4, 0, 3, -6], [0, -2, -6, -10]]], [[[4, 2, -5, -21], [2, -2, 1, 7], [4, 3, -1, -1]]], [[[1, 1, 1, 5], [2, 2, 3, 14], [2, -3, 2, -5]]]];
        $outputs = [[1, 4, -2], [-1, 6, 2], [3, -1, 2], [1, 0, 5], [-2, 3, 4]];
        $n = 4; // 2,4
        $input = $inputs[$n];

        $res = $this->solve_eq(...$input);
        $assert = json_encode($outputs[$n]) === json_encode($res);

        df(tmr(@$this->start), $input, $outputs[$n], $res, $assert);
    }

    function solve_eq($eq)
    {
        [$a1, $b1, $c1, $d1] = $eq[0];
        [$a2, $b2, $c2, $d2] = $eq[1];
        [$a3, $b3, $c3, $d3] = $eq[2];

        if ($a2 !== 0) {
            [$a12, $b12, $c12, $d12] = [0, $b1 - ($a1 / $a2) * $b2, $c1 - ($a1 / $a2) * $c2, $d1 - ($a1 / $a2) * $d2];
        } else {
            [$a12, $b12, $c12, $d12] = [$a2, $b2, $c2, $d2];
        }

        if ($a3 !== 0) {
            [$a13, $b13, $c13, $d13] = [0, $b1 - ($a1 / $a3) * $b3, $c1 - ($a1 / $a3) * $c3, $d1 - ($a1 / $a3) * $d3];
        } else {
            [$a13, $b13, $c13, $d13] = [$a3, $b3, $c3, $d3];
        }

        if ($b13 !== 0) {
            [$a123, $b123, $c123, $d123] = [0, 0, $c12 - ($b12 / $b13) * $c13, $d12 - ($b12 / $b13) * $d13];
        } else {
            [$a123, $b123, $c123, $d123] = [$a13, $b13, $c13, $d13];
        }

        //df(tmr(@$this->start), [$a12, $b12, $c12, $d12], [$a13, $b13, $c13, $d13]);
        $z = ($d123 / $c123);
        $y = $b12 != 0 ? ($d12 - $c12 * $z) / $b12 :($d13 - $c13 * $z) / $b13 ;
        $x = ($d1 - $b1 * $y - $c1 * $z) / $a1;
        $z = intval($z . '');
        $y = intval($y . '');
        $x = intval($x . '');

        return [$x, $y, $z];
    }

}













