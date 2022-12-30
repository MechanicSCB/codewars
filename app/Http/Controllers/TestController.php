<?php

namespace App\Http\Controllers;


use App\Classes\ReverseSeeder;
use App\Models\Lang;
use App\Models\Solution;
use App\Classes\Trash\MyKataSolver;
use Faker\Factory;
use Illuminate\Support\Str;


class TestController extends Controller
{
    //function squirrel($h,$H,$S){return round((1+($S/$h)**2)**.5*$H, 4);}
    public function test()
    {
        // $faker = \Faker\Factory::create();
        //"olhNRr4QNuv9XR73","NyhwzoLciHIYhg"
        function getRandomArguments()
        {
            $start = rand(-99999, 99999);
            $arr = range($start, $start + rand(1,20000));

            return [$arr];
        }

        function randomNum() {return random_int(1, 5e6) / 1e6 * (-1 + 2 * random_int(0, 1));}
        function randomOp() {return '+-*/'[random_int(0, 3)];}

        df(tmr(@$this->start), getRandomArguments());

        //M7KxIWGz4JXS
        df(tmr(@$this->start), 'test');

        //$kata = Kata::find('551dd1f424b7a4cdae0001f0');
        //$kata->description = Str::markdown($kata->description);
        //$kata->save();

        df(tmr(@$this->start), 'test');

        //$solutionsStatuses = Solution::whereNotNull('status')->pluck('status', 'id');
        //file_put_contents(base_path("database/data/json/solutions/solutions_statuses.json"), json_encode($solutionsStatuses));
        //df(tmr(@$this->start), $solutionsStatuses);

        $langsSlugs = ['php', 'javascript', 'python', 'ruby', 'lua', 'r'];
        $langsSlugs = ['javascript'];
        $langsIds = Lang::whereIn('slug', $langsSlugs)->pluck('id');

        $solutions = Solution::query()
            ->whereNull('status')
            ->has('kata.sample')
            ->whereIn('lang_id', $langsIds)
            //->skip(0)
            ->take(500)
            ->get();
        //df(tmr(@$this->start), $solutions);

        //(new SolutionMassChecker())->run($solutions);

        df(tmr(@$this->start), 'test');
        //file_put_contents(base_path("database/data/json/samples.json"), Sample::get()->toJson());
    }

    public function test2()
    {
        function generateRandomString($length = 10)
        {
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';

            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            return $randomString;
        }

        function gen()
        {
            $words = [];

            for ($i = 1; $i < rand(1, 9); $i++) {
                $word = generateRandomString(rand(2, 12));
                $x = rand(0, strlen($word) - 1);
                $head = substr($word, 0, $x);
                $tail = substr($word, $x);
                $words[] = $head . $i . $tail;
            }

            shuffle($words);

            return implode(' ', $words);
        }

        $res = gen();

        df(tmr(@$this->start), $res);


        //$kata = Kata::find('551dc350bf4e526099000ae5');
        //$kata->description = Str::markdown($kata->description);
        //$kata->save();

        df(tmr(@$this->start), 'test');

        //$solutionsStatuses = Solution::whereNotNull('status')->pluck('status', 'id');
        //file_put_contents(base_path("database/data/json/solutions/solutions_statuses.json"), json_encode($solutionsStatuses));
        //df(tmr(@$this->start), $solutionsStatuses);

        $langsSlugs = ['php', 'javascript', 'python', 'ruby', 'lua', 'r'];
        $langsSlugs = ['javascript'];
        $langsIds = Lang::whereIn('slug', $langsSlugs)->pluck('id');

        $solutions = Solution::query()
            ->whereNull('status')
            ->has('kata.sample')
            ->whereIn('lang_id', $langsIds)
            //->skip(0)
            ->take(500)
            ->get();
        //df(tmr(@$this->start), $solutions);

        //(new SolutionMassChecker())->run($solutions);

        df(tmr(@$this->start), 'test');
        //file_put_contents(base_path("database/data/json/samples.json"), Sample::get()->toJson());
    }

}
