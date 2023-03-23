<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class MovieControllerTest extends TestCase
{
    use WithFaker;

    public function test_it_can_list_movies()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $movies = Movie::factory()->count(10)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/movies');

        $response->assertStatus(200);

        $data = $response->json()['data'];
        $numMovies = count($data);
        $this->assertEquals(10, $numMovies);
    }

    public function test_it_can_search_movies()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $movie = Movie::factory()->create(['title' => 'The Shawshank Redemption']);
        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/movies?search=shawshank');

        $response->assertStatus(200);

        $data = $response->json()['data'];
        $numMovies = count($data);
        $this->assertEquals(1, $numMovies);
    }

    public function test_it_can_paginate_movies()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $movies = Movie::factory()->count(20)->create();
        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/movies?perPage=11');
        $response->assertStatus(200);

        $data = $response->json()['data'];
        $numMovies = count($data);
        $this->assertEquals(11, $numMovies);


        $response = $this
            ->getJson('/api/movies?perPage=5');
        $response->assertStatus(200);

        $data = $response->json()['data'];
        $numMovies = count($data);
        $this->assertEquals(5, $numMovies);
    }

    public function test_it_can_list_user_movies()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $movies = Movie::factory(10)->create();
        $user->movies()->attach($movies->pluck('id')->toArray());

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/movies?myMovies=true');

        $response->assertStatus(200);
        $data = $response->json()['data'];
        $numMovies = count($data);
        $this->assertEquals(10, $numMovies);
    }

    public function test_it_can_create_movie()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $movie = Movie::factory()->make([
            'title' => 'Cocaine Bear (2023)',
            'slug' => Str::slug('Cocaine Bear (2023)')
        ]);
        $data = $movie->toArray();
        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/movies', $data);

        $response->assertStatus(201)->assertJson($data);
        $this->assertDatabaseHas('movies', ['id' => $response['id']]);
    }
}
