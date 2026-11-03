<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_login_returns_token_and_user_for_valid_credentials(): void
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
            ->assertJsonStructure([
                'message',
                'token',
                'user' => ['id', 'name', 'email', 'role', 'division_id'],
            ])
            ->assertJsonPath('user.email', $user->email)
            ->assertJsonPath('user.role', 'applicant');
    }

    public function test_api_logout_revokes_all_tokens_for_the_user(): void
    {
        $user = User::factory()->create([
            'email' => 'logout-user@example.com',
            'password' => bcrypt('secret123'),
            'role' => 'applicant',
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/logout');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Logged out successfully.');

        $this->assertSame(0, $user->fresh()->tokens()->count());
    }

    public function test_api_requires_auth_for_protected_route(): void
    {
        $response = $this->getJson('/api/v1/me');

        $response->assertStatus(401);
    }
}
