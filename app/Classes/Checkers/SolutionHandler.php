<?php


namespace App\Classes\Checkers;


use App\Classes\Parsers\SolutionsParser;
use App\Models\Lang;
use App\Models\Sample;
use App\Models\Solution;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\DB;

class SolutionHandler
{
    protected string $seedsFolder;

    public function __construct()
    {
        $this->seedsFolder = (new DatabaseSeeder())->seedFilesFolder . '/solutions';
        $this->parsedFolder = database_path("data/json/parsed_solutions");
    }

    public function getSolutionsByLangsFromReverseSeederJsonFiles(array $langsSlugs = []): array
    {
        if (! count($langsSlugs)) {
            $langsSlugs = Lang::pluck('slug')->toArray();
        }

        $langsSolutions = [];

        foreach ($langsSlugs as $langSlug) {
            $filepath = "$this->seedsFolder/$langSlug.json";

            if (file_exists($filepath)) {
                $langsSolutions[$langSlug] = json_decode(file_get_contents($filepath), 1);
            }
        }

        return $langsSolutions;
    }

    public function getSolutionsByLangsFromOrigJsonFiles(array $langsSlugs = []): array
    {
        if (! count($langsSlugs)) {
            $langsSlugs = Lang::pluck('slug')->toArray();
        }

        $langsSolutions = [];

        foreach ($langsSlugs as $langSlug) {
            $filepath = "$this->parsedFolder/$langSlug.json";

            if (file_exists($filepath)) {
                $langsSolutions[$langSlug] = json_decode(file_get_contents($filepath), 1);
            }
        }

        return $langsSolutions;
    }

    public function parseMissingSolutionsAndSaveToJsonFile(array $langsSlugs = []): array
    {
        if (! count($langsSlugs)) {
            $langsSlugs = Lang::has('solutions')->pluck('slug')->toArray();
        }

        $langsSolutions = [];

        foreach ($langsSlugs as $langSlug) {
            $langsSolutions[$langSlug] = $this->parseLangMissingSolutionsAndSaveToJsonFile($langSlug);
        }


        return $langsSolutions;
    }

    protected function parseLangMissingSolutionsAndSaveToJsonFile(string $langSlug): array
    {
        if (! file_exists("$this->parsedFolder/$langSlug.json")) {
            file_put_contents("$this->parsedFolder/$langSlug.json", json_encode([]));
        }

        // Get existed solutions from json file
        $existedLangJsonSolutions = json_decode(file_get_contents("$this->parsedFolder/$langSlug.json"), 1) ?? [];

        // parse missing solutions
        $parsedLangSolutions = (new SolutionsParser())->parseMissingLangSolutions($langSlug);

        // append missing lang solutions to existed solutions
        $existedLangJsonSolutions = [...$parsedLangSolutions, ...$existedLangJsonSolutions];

        // save updated json to solutions.json
        file_put_contents("$this->parsedFolder/$langSlug.json", json_encode($existedLangJsonSolutions));

        $langSolutions = json_decode(file_get_contents("$this->parsedFolder/$langSlug.json"), 1);

        return $langSolutions;
    }

    public function saveSolutionsFromDbToJsonFilesByLang(array $langsSlugs = []): void
    {
        set_time_limit(180);

        $query = Solution::query();

        if (count($langsSlugs)) {
            $langsIds = Lang::whereIn('slug', $langsSlugs)->pluck('id');
            $query->whereIn('lang_id', $langsIds);
        }

        $solutionsFromDb = $query->get()->groupBy('lang_id');

        $solutionsToJsonFiles = [];
        $langsSlugs = Lang::pluck('slug', 'id');

        foreach ($solutionsFromDb as $langId => $langSolutions) {
            if (! $langSlug = @$langsSlugs[$langId]) {
                df(tmr(@$this->start), 'no lang slug!?');
                continue;
            }

            foreach ($langSolutions as $solution) {
                $solutionsToJsonFiles[$langSlug][$solution['kata_id']][] = $solution->only(
                    'user_id', 'body', 'variations', 'best_practice', 'clever', 'comments', 'created_at', 'updated_at'
                );
            }
        }

        // create/update langs solutions.json files
        foreach ($solutionsToJsonFiles as $langSlug => $langSolutions) {
            if (! file_exists($this->seedsFolder) && ! is_dir($this->seedsFolder)) {
                mkdir($this->seedsFolder);
            }

            file_put_contents("$this->seedsFolder/$langSlug.json", json_encode($langSolutions));
        }
    }

    public function checkFunctionNames()
    {
        $langIds = Lang::whereIn('status',[1,2])->pluck('id');
        $langSlugs = Lang::pluck('slug', 'id');
        $sampleFunctionNames = Sample::pluck('function_names', 'kata_id')
            ->map(fn($v) => array_unique(json_decode($v, 1)))
            ->toArray();

        $solutions = DB::table('solutions')
            ->whereIn('lang_id', $langIds)
            ->get(['id', 'kata_id', 'body', 'lang_id', 'status']);

        $solutionsGrouped = [];


        foreach ($solutions as $solution) {
            foreach ($sampleFunctionNames[$solution->kata_id] ?? [] as $functionName){
                if(count($sampleFunctionNames[$solution->kata_id]) < 2){
                    continue;
                }

                if(! str_contains($solution->body, $functionName)){
                    $solutionsGrouped[$solution->kata_id][$functionName][$langSlugs[$solution->lang_id]][] = $solution->body;
                }
            }
        }

        //foreach ($solutionsGrouped as $kataId => $kataSolutions){
        //    foreach ($kataSolutions as $lang => $kataSolutions){
        //
        //    }
        //}

        //$solutionsGrouped = array_slice($solutionsGrouped, 0, 10);
        df(tmr(@$this->start), $solutionsGrouped);

        df(tmr(@$this->start), 'checkFunctionNames');
    }

    public function replaceSlashedNInSolutionsBody($kataId)
    {
        $solutions = Solution::query()
            ->where('kata_id', $kataId)
            //->whereRelation('lang', 'slug', 'coffeescript')
            ->get(['id','body'])
        ;

        foreach ($solutions as $solution){
            $solution->body = str_replace(["\n".'"', "\n"."'", "\n`"],['\n"','\n'."'", '\n`'], $solution->body);
            $solution->save();
        }

    }
}
