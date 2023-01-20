<?php

namespace App\Http\Controllers;


use App\Classes\Checkers\SolutionHandler;
use App\Classes\Parsers\TestCasesParser;
use App\Classes\ReverseSeeder;
use App\Classes\Trash\BreakPieces;
use App\Classes\Trash\KataSolver;
use App\Models\Kata;
use App\Models\Lang;
use App\Models\Solution;
use Faker\Factory;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class TestController extends Controller
{
    public function test()
    {

        //[[1,69,95,70]], [[0,49,40,99]], [[37,61,92,36]], [[51,24,75,57]], [[92,59,88,11]]]
        //(new SolutionHandler())->replaceSlashedNInSolutionsBody();
        //df(tmr(@$this->start), 878);


        $res = (new KataSolver())->solve();

        df(tmr(@$this->start), $res);


        // $faker = \Faker\Factory::create();
        function getRandomArguments()
        {
            $x = rand(-100, 100);
            $x1 = rand(-100, 100);
            $x2 = rand(-100, 100);
            $x3 = rand(-100, 100);
            $y = rand(-100, 100);
            $y1 = rand(-100, 100);
            $y2 = rand(-100, 100);
            $y3 = rand(-100, 100);
            $z = rand(-100, 100);
            $z1 = rand(-100, 100);
            $z2 = rand(-100, 100);
            $z3 = rand(-100, 100);
            $eq1 = [$x1, $y1, $z1, ($x1 * $x + $y1 * $y + $z1 * $z)];
            $eq2 = [$x2, $y2, $z2, ($x2 * $x + $y2 * $y + $z2 * $z)];
            $eq3 = [$x3, $y3, $z3, ($x3 * $x + $y3 * $y + $z3 * $z)];

            return [[$eq1, $eq2, $eq3]];
        }


        df(tmr(@$this->start), getRandomArguments());
    }

}
