<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_login_returns_token_for_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'api-user@example.com',
            'password' => bcrypt('secret123'),
            'role' => 'applicant',
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'token']);
    }

    public function test_api_requires_auth_for_protected_route(): void
    {
        $response = $this->getJson('/api/v1/me');

        $response->assertStatus(401);
    }
}
