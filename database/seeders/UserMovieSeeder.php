<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserMovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->select('*')->limit(10)->get();
        $movies = DB::table('movies')->select('*')->limit(10)->get();

        $payload = [];
        $length = max(count($users), count($movies));

        for ($i = 0; $i < $length; $i++) {
            $payload[] = [
                'id' => uuid_create(),
                'user_id' => $users[$i]->id,
                'movie_id' => $movies[$i]->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        DB::table('user_movies')->insert($payload);
    }
}
