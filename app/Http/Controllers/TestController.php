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
            $width = mt_rand(20, 30);
            $height = mt_rand(20, 30);

            $matrix = [];
            for ($y = 0; $y < $height; $y++) {
                $matrix[$y] = str_repeat(' ', $width);
            }

            $p1 = [
                'start' => ['x' => 0, 'y' => 0],
                'end' => ['x' => 0, 'y' => 0],
            ];
            $p2 = [
                'start' => ['x' => 0, 'y' => 0],
                'end' => ['x' => 0, 'y' => 0],
            ];

            $p1['start']['x'] = mt_rand(0, $width - 5);
            $p1['start']['y'] = mt_rand(0, $height - 5);
            $p1['end']['x'] = mt_rand($p1['start']['x'] + 2, $width - 1);
            $p1['end']['y'] = mt_rand($p1['start']['y'] + 2, $height - 1);

            do {
                $p2['start']['x'] = mt_rand(0, $width - 5);
                $p2['start']['y'] = mt_rand(0, $height - 5);
                $p2['end']['x'] = mt_rand($p2['start']['x'] + 2, $width - 1);
                $p2['end']['y'] = mt_rand($p2['start']['y'] + 2, $height - 1);
                $valid = true;
                if (abs($p1['start']['x'] - $p2['start']['x']) === 1) {
                    $valid = false;
                }
                if (abs($p1['start']['x'] - $p2['end']['x']) === 1) {
                    $valid = false;
                }
                if (abs($p1['end']['x'] - $p2['start']['x']) === 1) {
                    $valid = false;
                }
                if (abs($p1['end']['x'] - $p2['end']['x']) === 1) {
                    $valid = false;
                }

                if (abs($p1['start']['y'] - $p2['start']['y']) === 1) {
                    $valid = false;
                }
                if (abs($p1['start']['y'] - $p2['end']['y']) === 1) {
                    $valid = false;
                }
                if (abs($p1['end']['y'] - $p2['start']['y']) === 1) {
                    $valid = false;
                }
                if (abs($p1['end']['y'] - $p2['end']['y']) === 1) {
                    $valid = false;
                }

                if (
                    $p2['start']['x'] > $p1['start']['x'] && $p2['end']['x'] < $p1['end']['x'] &&
                    $p2['start']['y'] > $p1['start']['y'] && $p2['end']['y'] < $p1['end']['y']
                ) {
                    $valid = false;
                }
                if (
                    $p1['start']['x'] > $p2['start']['x'] && $p1['end']['x'] < $p2['end']['x'] &&
                    $p1['start']['y'] > $p2['start']['y'] && $p1['end']['y'] < $p2['end']['y']
                ) {
                    $valid = false;
                }
            } while (! $valid);


            foreach ([$p1, $p2] as $p) {
                $minX = $p['start']['x'];
                $maxX = $p['end']['x'];
                $minY = $p['start']['y'];
                $maxY = $p['end']['y'];

                // Top line
                $matrix[$minY][$minX] = '+';
                for ($x = $minX + 1; $x < $maxX; $x++) {
                    if ($matrix[$minY][$x] !== '+') {
                        $matrix[$minY][$x] = $matrix[$minY][$x] === '|' ? '+' : '-';
                    }
                }
                $matrix[$minY][$maxX] = '+';

                // Bottom line
                $matrix[$maxY][$minX] = '+';
                for ($x = $minX + 1; $x < $maxX; $x++) {
                    if ($matrix[$maxY][$x] !== '+') {
                        $matrix[$maxY][$x] = $matrix[$maxY][$x] === '|' ? '+' : '-';
                    }
                }
                $matrix[$maxY][$maxX] = '+';

                // Left line
                $matrix[$minY][$minX] = '+';
                for ($y = $minY + 1; $y < $maxY; $y++) {
                    if ($matrix[$y][$minX] !== '+') {
                        $matrix[$y][$minX] = $matrix[$y][$minX] === '-' ? '+' : '|';
                    }
                }
                $matrix[$maxY][$minX] = '+';

                // Right line
                $matrix[$minY][$maxX] = '+';
                for ($y = $minY + 1; $y < $maxY; $y++) {
                    if ($matrix[$y][$maxX] !== '+') {
                        $matrix[$y][$maxX] = $matrix[$y][$maxX] === '-' ? '+' : '|';
                    }
                }
                $matrix[$maxY][$maxX] = '+';
            }

            $res = implode("\n", $matrix);
            $res = stripslashes($res);

            return [$res];
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
