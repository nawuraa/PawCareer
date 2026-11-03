<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_initial_hrd_and_master_data_seeders_create_expected_records(): void
    {
        $this->artisan('db:seed')->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => 'hrd@pawcareer.local',
            'role' => 'hrd',
        ]);

        $this->assertDatabaseHas('divisions', ['name' => 'Veterinary Medicine']);
        $this->assertDatabaseHas('positions', ['name' => 'Veterinarian']);
    }
}
