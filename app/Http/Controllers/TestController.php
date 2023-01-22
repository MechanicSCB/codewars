<?php

namespace App\Http\Controllers;


use App\Classes\Checkers\SolutionHandler;
use App\Classes\ReverseSeeder;
use App\Classes\Trash\KataSolver;
use Illuminate\Support\Facades\Storage;


class TestController extends Controller
{
    public function test()
    {
        //(new SolutionHandler())->replaceSlashedNInSolutionsBody('51c8e37cee245da6b40000bd');

        //$res = (new KataSolver())->solve();
        //
        //df(tmr(@$this->start), $res);


        // $faker = \Faker\Factory::create();
        function getRandomArguments()
        {


            //return [[$eq1, $eq2, $eq3]];
        }


        df(tmr(@$this->start), getRandomArguments());
    }

}
