<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase; // Rollback database changes after each test

    /** @test for registering a user */
    public function it_registers_a_user()
    {
        $response = $this->postJson('/api/v1/users', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        // $response->assertStatus(201);
        $response->assertJsonStructure(['status_code', 'results' => ['activation_token']]);

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);
    }

    /** @test failing test for registering user with invalid data */
    public function it_fails_to_register_user_with_invalid_data()
    {
        $response = $this->postJson('/api/v1/users', [
            'name' => '', // Invalid name
            'email' => 'invalid-email', // Invalid email
            'password' => '123', // Too short password
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure(['status_code', 'message']);
    }

    /** @test for activating a user */
    public function it_activates_a_user()
    {
        // Create user with activation token
        $user = User::factory()->create([
            'activation_token' => 'valid_token',
        ]);

        // Send activation request
        $response = $this->postJson('/api/v1/users/activate', ['token' => 'valid_token']);
        $response->assertStatus(200);
        $response->assertJsonStructure(['status_code', 'results' => ['message']]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'activation_token' => null, // Token should be null after activation
        ]);
    }

    /** @test failing user activation with invalid token */
    public function it_fails_to_activate_with_invalid_token()
    {
        $response = $this->postJson('/api/v1/users/activate', ['token' => 'invalid_token']);

        // $response->assertStatus(400);
        $response->assertJsonStructure(['status_code', 'message']);
    }

    /** @test for login a user */
    public function it_logs_in_a_user()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/token/auth', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['status_code', 'results' => ['token']]);
    }

    /** @test failing test for login user with invalid data */
    public function it_fails_to_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/token/auth', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure(['status_code', 'message']);
    }

    /** @test for logging out a user */
    public function it_logs_out_a_user()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/token/logout')
            ->assertStatus(200)
            ->assertJsonStructure(['status_code', 'results' => ['message']]);

        JWTAuth::setToken($token);
    }
}
