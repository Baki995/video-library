<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payload = [];
        for ($i = 1; $i <= 30; $i++) {
            $payload[] = [
                'id' => uuid_create(),
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => bcrypt('Password' . $i . '#@'),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('users')->insert($payload);
    }
}
