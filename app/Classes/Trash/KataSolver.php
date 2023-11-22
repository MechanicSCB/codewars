<?php


namespace App\Classes\Trash;


use App\Classes\Trash\Katas\PathFinder2;
use App\Classes\Trash\Katas\Sky_4;
use App\Classes\Trash\Katas\Sky_6;
use Illuminate\Support\Str;
use function Symfony\Component\String\b;

class KataSolver
{

    public function solve()
    {
        $res = $this->speed(159, 0.8); // 153.79671564846308

        dd(tmr(), $res);
    }

    function speed($d, $mu)
    {
        $mug = $mu * 9.81;

        return 3.6*(-2*$mug + sqrt(4*($mug**2) + 8*$mug*$d))/2;
    }

    function dist($v, $mu)
    {
        $v /= 3.6;

        return ($v * $v) / (2 * $mu * 9.81) + $v;
    }


}













