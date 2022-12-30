<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param string $tableName
     * @param int $chunkLength
     * @return void
     */
    public function run(string $tableName, int $chunkLength = 1000):void
    {
        $folder = (new DatabaseSeeder())->seedFilesFolder;
        $data = json_decode(file_get_contents("$folder/$tableName.json"), 1);

        clearDbTable($tableName);

        foreach (array_chunk($data, $chunkLength) as $chunk) {
            DB::table($tableName)->insert($chunk);
        }
    }
}
