<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_rejected(): void
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(302);
    }

    public function test_user_with_wrong_role_is_forbidden(): void
    {
        $user = User::factory()->create([
            'role' => 'applicant',
            'division_id' => null,
        ]);

        $this->actingAs($user);

        $response = $this->get('/hrd');

        $response->assertStatus(403);
    }

    public function test_hrd_can_access_hrd_route(): void
    {
        $user = User::factory()->create([
            'role' => 'hrd',
            'division_id' => null,
        ]);

        $this->actingAs($user);

        $response = $this->get('/hrd');

        $response->assertStatus(200);
    }
}
