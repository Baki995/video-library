<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerTest extends TestCase
{
    use WithFaker;

    /**
     * Test the `register` method.
     *
     * @return void
     */
    public function testRegister(): void
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => 'Pass21@!',
            'c_password' => 'Pass21@!'
        ];

        $response = $this->postJson('/register', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'name' => $data['name'],
        ]);
    }

    /**
     * Test the `login` method.
     *
     * @return void
     */
    public function testLogin(): void
    {
        $password = 'Pass32#@';
        $user = User::factory()->create([
            'password' => bcrypt($password),
        ]);
        $data = [
            'email' => $user->email,
            'password' => $password,
        ];

        $response = $this->postJson('/login', $data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);
    }

    /**
     * Test the `login` method with invalid credentials.
     *
     * @return void
     */
    public function testLoginWithInvalidCredentials(): void
    {
        $data = [
            'email' => $this->faker->safeEmail,
            'password' => 'Pass32#@32',
        ];

        $response = $this->postJson('/login', $data);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Invalid login credentials',
        ]);
    }

    /**
     * Test the `refreshToken` method.
     *
     * @return void
     */
    public function testRefreshToken(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/refresh-token');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);
    }

    /**
     * Test the `logout` method.
     *
     * @return void
     */
    public function testLogout(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Test the `me` method.
     *
     * @return void
     */
    public function testMe(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/me');

        $response->assertStatus(200);
        $response->assertJson([
            'email' => $user->email,
            'name' => $user->name,
        ]);
    }
}
