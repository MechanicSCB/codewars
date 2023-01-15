<?php

namespace App\Http\Controllers;


use App\Classes\ReverseSeeder;
use App\Classes\Trash\BreakPieces;
use App\Classes\Trash\KataSolver;
use App\Models\Kata;
use App\Models\Lang;
use App\Models\Solution;
use Faker\Factory;
use Illuminate\Support\Str;


class TestController extends Controller
{
    public function test()
    {
        $res = (new KataSolver())->solve();

        df(tmr(@$this->start), $res);


        // $faker = \Faker\Factory::create();
        function getRandomArguments()
        {
            return [array_map(function () {return random_int(-100, 100);}, range(1, $n = random_int(5, 20))), array_map(function () {return random_int(-100, 100);}, range(1, $n))];
        }

        df(tmr(@$this->start), getRandomArguments());
    }

}
