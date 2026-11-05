<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterDataManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_hrd_can_manage_divisions_and_positions(): void
    {
        $hrd = User::factory()->create(['role' => 'hrd']);
        $division = Division::create(['name' => 'Veterinary Medicine']);

        $this->actingAs($hrd)
            ->get(route('divisions.index'))
            ->assertOk()
            ->assertSee('Veterinary Medicine');

        $this->post(route('divisions.store'), [
            'name' => 'Surgery & ICU',
            'description' => 'Clinical services',
        ])->assertRedirect(route('divisions.index'));

        $createdDivision = Division::query()->where('name', 'Surgery & ICU')->firstOrFail();
        $this->put(route('divisions.update', $createdDivision), [
            'name' => 'Surgery and ICU',
            'description' => 'Updated clinical services',
        ])->assertRedirect(route('divisions.index'));
        $this->assertDatabaseHas('divisions', [
            'id' => $createdDivision->id,
            'name' => 'Surgery and ICU',
        ]);

        $this->post(route('positions.store'), [
            'division_id' => $division->id,
            'name' => 'Veterinarian',
            'description' => 'Provides veterinary care',
        ])->assertRedirect(route('positions.index'));

        $position = Position::query()->where('name', 'Veterinarian')->firstOrFail();

        $this->put(route('positions.update', $position), [
            'division_id' => $division->id,
            'name' => 'Senior Veterinarian',
            'description' => 'Provides specialist veterinary care',
        ])->assertRedirect(route('positions.index'));

        $this->assertDatabaseHas('positions', [
            'id' => $position->id,
            'name' => 'Senior Veterinarian',
        ]);

        $this->delete(route('positions.destroy', $position))
            ->assertRedirect(route('positions.index'));
        $this->assertSoftDeleted('positions', ['id' => $position->id]);

        $this->delete(route('divisions.destroy', $createdDivision))
            ->assertRedirect(route('divisions.index'));
        $this->assertSoftDeleted('divisions', ['id' => $createdDivision->id]);
    }

    public function test_non_hrd_cannot_access_division_or_position_management(): void
    {
        $applicant = User::factory()->create(['role' => 'applicant']);
        $manager = User::factory()->create([
            'role' => 'division_manager',
            'division_id' => Division::create(['name' => 'Clinic Operations'])->id,
        ]);

        foreach ([$applicant, $manager] as $user) {
            $this->actingAs($user)
                ->get(route('divisions.index'))
                ->assertForbidden();

            $this->post(route('positions.store'), [
                'division_id' => $manager->division_id,
                'name' => 'Unauthorized position',
            ])->assertForbidden();
        }
    }

    public function test_deleted_division_and_position_names_remain_reserved(): void
    {
        $hrd = User::factory()->create(['role' => 'hrd']);
        $division = Division::create(['name' => 'Reserved Division']);
        $division->delete();

        $this->actingAs($hrd)
            ->post(route('divisions.store'), ['name' => 'Reserved Division'])
            ->assertSessionHasErrors('name');

        $activeDivision = Division::create(['name' => 'Active Division']);
        $position = Position::create([
            'division_id' => $activeDivision->id,
            'name' => 'Reserved Position',
        ]);
        $position->delete();

        $this->post(route('positions.store'), [
            'division_id' => $activeDivision->id,
            'name' => 'Reserved Position',
        ])->assertSessionHasErrors('name');
    }

    public function test_division_with_active_users_or_positions_cannot_be_deleted(): void
    {
        $division = Division::create(['name' => 'Veterinary Medicine']);
        $hrd = User::factory()->create(['role' => 'hrd']);
        Position::create([
            'division_id' => $division->id,
            'name' => 'Veterinarian',
        ]);

        $this->actingAs($hrd)
            ->delete(route('divisions.destroy', $division))
            ->assertSessionHasErrors('division');

        $this->assertNotSoftDeleted('divisions', ['id' => $division->id]);
    }
}
