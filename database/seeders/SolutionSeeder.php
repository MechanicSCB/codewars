<?php

namespace Database\Seeders;

use App\Classes\Checkers\SolutionHandler;
use App\Models\Lang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SolutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        set_time_limit(180);
        $solutionsByLangs = (new SolutionHandler())->getSolutionsByLangsFromReverseSeederJsonFiles();

        $solutionsToDb = [];

        foreach ($solutionsByLangs as $lang => $katasSolutions) {
            foreach ($katasSolutions as $kataId => $kataSolutions) {
                foreach ($kataSolutions as $key => $solution) {
                    if ($key >= 2) {
                        //continue; // get only two solutions per lang
                    }

                    $solutionsToDb[] = [
                        //'id' => $solution['id'],
                        'kata_id' => $kataId,
                        'user_id' => $solution['user_id'],
                        'body' => $solution['body'],
                        'lang_id' => $solution['lang_id'],
                        'is_control' => $solution['is_control'],
                        'status' => $solution['status'],
                        //'variations' => $solution['variations'],
                        //'best_practice' => $solution['best_practice'],
                        //'clever' => $solution['clever'],
                        //'comments' => $solution['comments'],
                        //'created_at' => $solution['created_at'],
                        //'updated_at' => $solution['updated_at'],
                    ];
                }
            }
        }

        clearDbTable('solutions');

        foreach (array_chunk($solutionsToDb, 1000) as $chunk) {
            DB::table('solutions')->insert($chunk);
        }
    }

    public function seedMissedSolutionsFromParsedJson()
    {
        set_time_limit(180);
        $existedSolutionsByLangs = (new SolutionHandler())->getSolutionsByLangsFromReverseSeederJsonFiles();
        $solutionsByLangs = (new SolutionHandler())->getSolutionsByLangsFromOrigJsonFiles();

        $solutionsToDb = [];
        $langsIds = Lang::pluck('id', 'slug');
        $usersIds = User::pluck('id', 'name');

        foreach ($solutionsByLangs as $lang => $katasSolutions) {
            foreach ($katasSolutions as $kataId => $kataSolutions) {
                if (isset($existedSolutionsByLangs[$lang][$kataId])) {
                    continue;
                }

                foreach ($kataSolutions as $key => $solution) {
                    $firstUserName = Str::before(@$solution['users'], ',');

                    $solutionsToDb[] = [
                        'kata_id' => $kataId,
                        'user_id' => @$usersIds[$firstUserName],
                        'body' => $solution['body'],
                        'lang_id' => $langsIds[$lang],
                        'variations' => $solution['variations'],
                        'best_practice' => $solution['best_practice'],
                        'clever' => $solution['clever'],
                        'comments' => $solution['comments'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        foreach (array_chunk($solutionsToDb, 1000) as $chunk) {
            DB::table('solutions')->insert($chunk);
        }
    }
}
