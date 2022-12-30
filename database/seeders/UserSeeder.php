<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run():void
    {
        (new TableSeeder())->run('users');
    }

    public function seedFromUsernames()
    {
        $users = [];
        $usersNames = json_decode(file_get_contents(base_path('database/seeders/json/usernames.json')), 1);

        foreach ($usersNames as $userName){
            $slug = Str::slug($userName);

            if($slug === ''){
                $slug = Str::random();
            }

            $users[] = [
                'name' => $userName,
                'email' => "$slug@example.com",
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ];
        }

        DB::table('users')->truncate();
        DB::table('users')->insert($users);
    }

}
