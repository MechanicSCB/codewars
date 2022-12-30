<?php

namespace Database\Seeders;

use App\Models\Kata;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run():void
    {
        (new TableSeeder())->run('katas');
    }


    public function seedFromOrigJson():void
    {
        // TODO fix array of descriptions (kata Four/Seven)
        $katas = json_decode(file_get_contents(base_path('database/data/json/katas.json')), 1);
        $users = User::pluck('id', 'name');
        $katasToDb = [];

        foreach ($katas as $kata){
            $katasToDb[] = [
                'id' => $kata['id'],
                'name' => $kata['name'],
                'slug' => $kata['slug'],
                'category' => $kata['category'],
                'published_at' => $kata['publishedAt'],
                'approved_at' => $kata['approvedAt'],
                'url' => $kata['url'],
                'rank' => @$kata['rank']['id'] * -1,
                'created_at_orig' => Str::beforeLast($kata['createdAt'], '.'),
                'created_by' => @$users[$kata['createdBy']['username']],
                'approved_by' => @$users[$kata['approvedBy']['username']],
                'description' => $kata['description'],
                'total_attempts' => $kata['totalAttempts'],
                'total_completed' => $kata['totalCompleted'],
                'total_stars' => $kata['totalStars'],
                'vote_score' => $kata['voteScore'],
                'contributors_wanted' => $kata['contributorsWanted'],
                'unresolved_issues' => @$kata['unresolved']['issues'],
                'unresolved_suggestions' => @$kata['unresolved']['suggestions'],
            ];
        }

        foreach (array_chunk($katasToDb, 1000) as $chunk){
            //DB::table('katas')->insert($chunk);
            DB::table('katas')->upsert($chunk, 'id');
        }
    }
}
