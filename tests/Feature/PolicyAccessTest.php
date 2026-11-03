<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_hrd_can_access_division_management(): void
    {
        $user = User::factory()->create(['role' => 'hrd']);
        $division = Division::create(['name' => 'Engineering']);

        $this->actingAs($user);

        $this->assertTrue($user->can('view', $division));
    }

    public function test_division_manager_cannot_access_other_division_data(): void
    {
        $ownDivision = Division::create(['name' => 'Engineering']);
        $otherDivision = Division::create(['name' => 'Sales']);

        $user = User::factory()->create([
            'role' => 'division_manager',
            'division_id' => $ownDivision->id,
        ]);

        $this->actingAs($user);

        $this->assertFalse($user->can('view', $otherDivision));
    }

    public function test_division_manager_can_access_own_division_positions(): void
    {
        $division = Division::create(['name' => 'Engineering']);
        $position = Position::create([
            'division_id' => $division->id,
            'name' => 'Backend Engineer',
        ]);

        $user = User::factory()->create([
            'role' => 'division_manager',
            'division_id' => $division->id,
        ]);

        $this->actingAs($user);

        $this->assertTrue($user->can('view', $position));
    }
}
