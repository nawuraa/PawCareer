<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicantRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_creates_applicant_role_only(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Applicant',
            'email' => 'applicant@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'hrd',
            'division_id' => 99,
        ]);

        $response->assertRedirect('/dashboard');

        $user = User::where('email', 'applicant@example.com')->firstOrFail();
        $this->assertSame('applicant', $user->role);
        $this->assertNull($user->division_id);
    }
}
