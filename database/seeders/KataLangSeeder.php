<?php

namespace Database\Seeders;

use App\Models\Lang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KataLangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        (new TableSeeder())->run('kata_lang');
    }
}
