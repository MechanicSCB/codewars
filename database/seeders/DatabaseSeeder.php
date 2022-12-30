<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public string $seedFilesFolder;

    public function __construct()
    {
        $this->seedFilesFolder = database_path("seeders/seeds");
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            LangSeeder::class,
            TagSeeder::class,
            KataSeeder::class,
            KataLangSeeder::class,
            KataTagSeeder::class,
            SampleSeeder::class,
            RandomTestSeeder::class,
            SolutionSeeder::class,
        ]);
    }
}
