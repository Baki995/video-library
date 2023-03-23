<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));

        for ($i = 1; $i <= 30; $i++) {
            $title = $faker->movie;
            $payload[] = [
                'id' => uuid_create(),
                'title' => $title,
                'studio' => $faker->studio,
                'runtime' => $faker->runtime,
                'slug' => Str::slug($title, '-') . '-' . Str::lower(Str::random(8)),
                'genre' => $faker->movieGenre,
                'description' => $faker->overview,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('movies')->insert($payload);
    }
}
