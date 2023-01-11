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
        //
        // $faker = \Faker\Factory::create();
        function getRandomArguments()
        {
            return [array_map(function () {return random_int(-100, 100);}, range(1, $n = random_int(5, 20))), array_map(function () {return random_int(-100, 100);}, range(1, $n))];
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

    public function kata_test()
    {
        $formula = 'H12M(R23Ta34R)T11Yzz89';
        //$formula = 'B2H6';
        $formula = "{[Co(NH3)4(OH)2]3Co}(SO4)3";


        function parse_molecule($formula)
        {
            $formula = str_replace(['[', ']', '{', '}'], ['(', ')'], $formula);

            $simple = get_simpler($formula);
            df($simple);

            return parse_simple($formula);
        }

        function get_simpler($formula)
        {
            [$head, $tail] = explode('(', $formula, 2);

            $cnt = 0;

            for ($i = 0; $i < strlen($tail); $i++) {
                $chr = $tail[$i];

                if ($chr === '(') {
                    $cnt++;
                }


            }

        }

        function parse_simple($formula)
        {
            $atoms = [];
            $atom = '';

            for ($i = 0; $i < strlen($formula); $i++) {
                $chr = $formula[$i];
                $ord = ord($chr);

                if ($ord >= 65 && $ord <= 90) {
                    if ($atom) {
                        $atoms[$atom] = $num ?? 1;
                        $num = 1;
                    }

                    $atom = $chr; // A - Z
                } elseif ($ord >= 97 && $ord <= 122) {
                    $atom .= $chr; // a - z
                } elseif ($ord >= 48 && $ord <= 57) {
                    $num = +$chr; // 0 - 9

                    if ($next = @$formula[$i + 1]) {
                        if (ord($next) >= 48 && ord($next) <= 57) {
                            $num = +"$num$next";
                            $i++;
                        }
                    }
                }

            }

            $atoms[$atom] = $num ?? 1;

            return $atoms;
        }

        $res = parse_molecule($formula);
        df(tmr(@$this->start), $res);

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
