<?php

namespace App\Http\Controllers;


use App\Classes\ReverseSeeder;
use App\Classes\Trash\BreakPieces;
use App\Models\Lang;
use App\Models\Solution;
use Faker\Factory;
use Illuminate\Support\Str;


class TestController extends Controller
{
    public function test()
    {
        // $faker = \Faker\Factory::create();
        function getRandomArguments()
        {
            $arr = [];

            for($i=0; $i<rand(1,9);$i++){
                for ($k=0; $k<8;$k++){
                    $arr[] = rand(0,1);
                }
            }

            return [$arr];
        }

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
