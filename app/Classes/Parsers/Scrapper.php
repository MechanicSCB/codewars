<?php


namespace App\Classes\Parsers;


use App\Models\User;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Scrapper
{
    public function showKataSolutions(string $kataId = null, string $lang = null)
    {
        $kataId = '514b92a657cdc65150000006';
        $lang = 'javascript';
        $headers = $this->getHeaders();

        $url = "https://www.codewars.com/kata/$kataId/solutions/$lang?show-solutions=1";
        $body = Http::withHeaders($headers)->post($url)->body();
        file_put_contents(base_path("database/data/json/solutions/$kataId.html"), $body);
    }

    public function getHeaders()
    {
        $headers = [
            'accept' => 'application/json, text/plain, */*',
            'accept-encoding' => 'gzip, deflate, br',
            'accept-language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            'authorization' => 'eyJhbGciOiJIUzI1NiJ9.eyJpZCI6IjYyYzJmZjFhZTA5NDNmMDA0OTVlMWI0MCIsImV4cCI6MTY1Nzg3MTg3Nn0.EB623sIxYeneW-ssO-_pGswBEmmKRXYuONk9214vaTE',
            'content-length' => '2',
            'content-type' => 'application/json',
            'cookie' => '_gid=GA1.2.1614785492.1656782735; _hjid=7ab56dfb-f73e-485c-b1b5-c314cde0b364; _hjSessionUser_1661672=eyJpZCI6IjM5ZThlZjQ4LTEzYWEtNTY3Yy04NTViLWY2ZmY3OTJjMTgzZCIsImNyZWF0ZWQiOjE2NTY3OTEzODcyMjksImV4aXN0aW5nIjp0cnVlfQ==; intercom-session-x27gw54w=NW1HQ09pWWUrc3lHdWl0MDdaNWJ4VjMyV3BPVDJabEdKeFFXUVJlQThRbmpXQ1BvMTNEY09VODRhN3ZTZmxyYS0taldEUm5zQkRpYlpYTUNobEZWdG53UT09--8879730a4a82fe81f64cee688f6293f45a036b28; _session_id=16a2557e2de3bbb95eda3db8d7aee9f4; _ga=GA1.2.2024936030.1612347945; CSRF-TOKEN=KSKSkK1jpZYl20Tf%2F%2B1n0P2Q9xqXWnTn9G74zUJK28mS92AvUZSs6pKEbOtQyaqcQPAHmEGp27fqfPveNKEeaQ%3D%3D; _ga_M3JYSQLS8M=GS1.1.1657007057.64.1.1657007878.0',
            'origin' => 'https://www.codewars.com',
            'referer' => 'https://www.codewars.com/kata/6204c419b5e27f001b91a207/solutions/bf',
            'sec-ch-ua' => '".Not/A)Brand";v="99", "Google Chrome";v="103", "Chromium";v="103"',
            'sec-ch-ua-mobile' => '?0',
            'sec-ch-ua-platform' => '"Windows"',
            'sec-fetch-dest' => 'empty',
            'sec-fetch-mode' => 'cors',
            'sec-fetch-site' => 'same-origin',
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36',
            'x-csrf-token' => 'KSKSkK1jpZYl20Tf/+1n0P2Q9xqXWnTn9G74zUJK28mS92AvUZSs6pKEbOtQyaqcQPAHmEGp27fqfPveNKEeaQ==',
            'x-requested-with' => 'XMLHttpRequest',
        ];

        return $headers;
    }

    public function seedKatasFromApiJsonToDb()
    {
        $katasOld = json_decode(file_get_contents(base_path("database/data/json/katas.json")), 1);

        df(tmr(@$this->start), $katasOld);
        $langs = json_decode(file_get_contents(base_path("database/data/json/langs.json")), 1);
        $langsApi = json_decode(file_get_contents(base_path("database/data/json/langs-names.json")), 1);
        $slugs = array_column($langs, 'slug');

        $dif1 = array_diff($langsApi, $slugs);
        $dif2 = array_diff($slugs, $langsApi);
        df(tmr(@$this->start), $dif1, $dif2);
        $katas = file_get_contents(base_path('database/data/json/katasAll2.json'));
        $katas = json_decode($katas, 1);

        //$katas = array_slice($katas, 0, 100);

        //df(tmr(@$this->start), $katas);
        $langs = [];
        $tags = [];

        foreach ($katas as $kata) {
            $langs = [...$langs, ...$kata['languages']];
            $tags = [...$tags, ...$kata['tags']];
        }

        $langs = array_unique($langs);
        $tags = array_unique($tags);
        $langs = array_values($langs);
        $tags = array_values($tags);
        file_put_contents(base_path("database/data/json/langs-names.json"), json_encode($langs));
        file_put_contents(base_path("database/data/json/tags-names.json"), json_encode($tags));
        df(tmr(@$this->start), $langs, $tags);

        $users = [];

        foreach ($katas as $kata) {
            if (@$kata['createdBy']) {
                $users[$kata['createdBy']['username']] = $kata['createdBy']['username'];
            }

            if (@$kata['approvedBy']) {
                $users[$kata['approvedBy']['username']] = $kata['approvedBy']['username'];
            }
        }

        $users = array_values($users);
        file_put_contents(base_path("database/data/json/usernames.json"), json_encode($users));
        df(tmr(@$this->start), $users);
        foreach ($users as $user) {
            $usersToDb[] = [
                'name' => $user,
                'email' => Str::slug($user) . '@example.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ];
        }

        //df(tmr(@$this->start), $usersToDb);
        foreach (array_chunk($usersToDb, 1000) as $chunk) {
            User::upsert($chunk, 'id');
        }

        df(tmr(@$this->start), $users);

        //df(tmr(@$this->start), last($katas));
        //df(tmr(@$this->start), $katas['62a933d6d6deb7001093de16']);
        $katasToDb = [];

        foreach ($katas as $kata) {
            $katasToDb[] = [
                'id' => $kata['id'],
                'name' => $kata['name'],
                'slug' => $kata['slug'],
                'category' => $kata['category'],
                'published_at' => $kata['publishedAt'],
                'approved_at' => $kata['approvedAt'],
                'languages' => json_encode($kata['languages']),
                'url' => $kata['url'],
                'rank' => @$kata['rank']['id'] * -1,
                'created_at' => Str::beforeLast($kata['createdAt'], '.'),
                //'created_by' => @$kata['createdBy']['username'],
                //'approved_by' => @$kata['approvedBy']['username'],
                'description' => Str::markdown($kata['description']),
                'total_attempts' => $kata['totalAttempts'],
                'total_completed' => $kata['totalCompleted'],
                'total_stars' => $kata['totalStars'],
                'vote_score' => $kata['voteScore'],
                'tags' => json_encode($kata['tags']),
                'contributors_wanted' => $kata['contributorsWanted'],
                'unresolved_issues' => @$kata['unresolved']['issues'],
                'unresolved_suggestions' => @$kata['unresolved']['suggestions'],
            ];
        }

        foreach (array_chunk($katasToDb, 1000) as $chunk) {
            DB::table('katas')->upsert($chunk, 'id');
        }
        df(tmr(@$this->start), $katasToDb);

        return $katas;
    }

    public function getKatasFromApi(array $ids): array
    {
        if (count($ids) > 300) {
            return ['too much ids'];
        }

        $katas = [];

        $responses = Http::pool(function (Pool $pool) use ($ids) {
            foreach ($ids as $id) {
                $return[] = $pool->timeout(60)->get("https://www.codewars.com/api/v1/code-challenges/$id");
            }

            return $return ?? [];
        });

        foreach ($responses as $response) {
            if (! is_a($response, 'Illuminate\Http\Client\Response')) {
                continue;
            }

            $katas[] = @$response->json() ?? [];
        }

        return $katas;
    }

    public function getVoileKatasIds(): array
    {
        $katas = file_get_contents(base_path('database/data/json/ignored/voile-completed-katas.json'));
        $katas = json_decode($katas, 1);
        $katasIds = array_column($katas, 'id');
        $katasIds = array_unique($katasIds);
        $katasIds = array_values($katasIds);

        return $katasIds;
    }

    public function getUserCompletedKatas(string $userName = null): array
    {
        $userName ??= 'Voile';
        $pages = 50; // 50!
        $katas = [];

        for ($page = 0; $page < $pages; $page++) {
            $data = Http::get("http://www.codewars.com/api/v1/users/$userName/code-challenges/completed?page=$page")->json('data');
            $katas = [...$katas, ...$data];
        }

        return $katas;
    }

    public function getSolutionsPageHtml()
    {
        $headers = [
            'Cookie' => "_ga_M3JYSQLS8M=GS1.1.1656780257.71.1.1656780888.0; _ga=GA1.2.421845459.1650964118; _hjid=fccfca25-d2fe-4b44-b579-f6633751e51b; _hjSessionUser_1661672=eyJpZCI6ImY2OTYzZjRiLTQ3MGUtNWM3OS04YmJhLWFlMzAyMTI4ODg0YyIsImNyZWF0ZWQiOjE2NTA5NjQxMTg4NjAsImV4aXN0aW5nIjp0cnVlfQ==; remember_user_token=eyJfcmFpbHMiOnsibWVzc2FnZSI6IkJBaGJDRnNHU1NJZE5XWmhaVGxrTkRobE5ESmpNakl3TURGbVlXRXpZMlV6QmpvR1JWUkpJaGxvWm1OdGVGUnlNWEpuVGxOMFdEWXRhbkF6TFFZN0FGUkpJaGN4TmpVMU16RXhNRE0yTGpFeU9UWTFPVElHT3dCRyIsImV4cCI6IjIwMjMtMDYtMTVUMTY6Mzc6MTYuMTI5WiIsInB1ciI6bnVsbH19--ce42aa927916916f8a8d88357f3a40e3562a5f78; _session_id=c9f1631d6b719d6511aca45f8bf19664; _gid=GA1.2.163592925.1656586928; CSRF-TOKEN=SXok9uFSQ5%2Bpo2mTWHLBmGUCE3ch6m4ZgNje7loobrU%2FDbi%2Bm%2BGiCOyFno30F8dnOPaV9kuxk%2B0zwM8dtSQa0A%3D%3D; _gat_gtag_UA_33566223_1=1",
        ];
        $res = Http::withHeaders($headers)->get('https://www.codewars.com/kata/56dec885c54a926dcd001095/solutions/javascript')->body();
        df(tmr(@$this->start), $res);
    }
}
