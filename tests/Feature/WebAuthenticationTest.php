<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class WebAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_home_and_login_pages_offer_a_working_auth_entry_point(): void
    {
        $this->get('/')
            ->assertRedirect(route('login'));

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Sign in to PawCareer')
            ->assertSee(route('login.post'))
            ->assertSee(route('register'));
    }

    public function test_successful_login_redirects_applicant_to_jobs_and_wrong_password_is_generic(): void
    {
        $user = User::factory()->create([
            'email' => 'applicant@example.com',
            'password' => 'correct-password',
            'role' => 'applicant',
        ]);

        $this->from(route('login'))
            ->post(route('login.post'), [
                'email' => $user->email,
                'password' => 'incorrect-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'correct-password',
            'remember' => '1',
        ])->assertRedirect(route('applicant.jobs.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_redirects_staff_roles_to_their_own_work_areas(): void
    {
        $hrd = User::factory()->create([
            'email' => 'hrd@example.com',
            'password' => 'correct-password',
            'role' => 'hrd',
        ]);

        $this->post(route('login.post'), [
            'email' => $hrd->email,
            'password' => 'correct-password',
        ])->assertRedirect(route('divisions.index'));

        auth()->logout();

        $manager = User::factory()->create([
            'email' => 'manager@example.com',
            'password' => 'correct-password',
            'role' => 'division_manager',
            'division_id' => \App\Models\Division::create(['name' => 'Clinic'])->id,
        ]);

        $this->post(route('login.post'), [
            'email' => $manager->email,
            'password' => 'correct-password',
        ])->assertRedirect(route('requisitions.index'));
    }

    public function test_logout_invalidates_the_authenticated_session(): void
    {
        $user = User::factory()->create(['role' => 'applicant']);

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_login_throttling_returns_a_form_error_with_status_429(): void
    {
        $email = 'throttled@example.com';
        $key = strtolower($email).'|127.0.0.1';
        RateLimiter::clear($key);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->from(route('login'))->post(route('login.post'), [
                'email' => $email,
                'password' => 'wrong-password',
            ])->assertRedirect(route('login'));
        }

        $this->from(route('login'))
            ->post(route('login.post'), [
                'email' => $email,
                'password' => 'wrong-password',
            ])
            ->assertStatus(429)
            ->assertHeader('Location', route('login'))
            ->assertSessionHasErrors('email');

        RateLimiter::clear($key);
    }
}
