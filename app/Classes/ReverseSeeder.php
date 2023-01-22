<?php


namespace App\Classes;


use App\Models\Lang;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\DB;

class ReverseSeeder
{
    protected string $folder;

    public function __construct()
    {
        //$this->folder = database_path("seeders/seeds");
        $this->folder = (new DatabaseSeeder())->seedFilesFolder;
    }

    public function run()
    {
        // TODO change to csv format?
        $tableNames = ['users', 'langs', 'tags', 'katas', 'kata_lang', 'kata_tag', 'samples', 'random_tests'];

        foreach ($tableNames as $tableName){
            $this->saveTableToJson($tableName);
        }

        $this->saveSolutionTableToLangJsonFiles();
    }

    public function saveTableToJson(string $tableName): void
    {
        file_put_contents(
            "$this->folder/$tableName.json",
            json_encode(DB::table($tableName)->get())
        );
    }

    public function saveSolutionTableToLangJsonFiles(): void
    {
        $solutionsFolder = "$this->folder/solutions";
        $solutionsDb = DB::table('solutions')->get();
        $langsSlugs = Lang::pluck('slug', 'id');
        $solutions = [];

        foreach ($solutionsDb as $solutionDb) {
            $solutions[$langsSlugs[$solutionDb->lang_id]][$solutionDb->kata_id][] = [
                'id' => $solutionDb->id,
                'user_id' => $solutionDb->user_id,
                'body' => $solutionDb->body,
                'lang_id' => $solutionDb->lang_id,
                'is_control' => $solutionDb->is_control,
                'status' => $solutionDb->status,
                'variations' => $solutionDb->variations,
                'best_practice' => $solutionDb->best_practice,
                'clever' => $solutionDb->clever,
                'comments' => $solutionDb->comments,
                'created_at' => $solutionDb->created_at,
                'updated_at' => $solutionDb->updated_at,
            ];
        }

        foreach ($solutions as $langSlug => $langSolutions) {
            file_put_contents("$solutionsFolder/$langSlug.json", json_encode($langSolutions));
        }
    }
}
