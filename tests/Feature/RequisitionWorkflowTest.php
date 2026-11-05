<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\JobPosting;
use App\Models\Position;
use App\Models\Requisition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequisitionWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_submit_only_for_an_active_position_in_their_division(): void
    {
        $division = Division::create(['name' => 'Veterinary Medicine']);
        $otherDivision = Division::create(['name' => 'Surgery & ICU']);
        $position = Position::create([
            'division_id' => $division->id,
            'name' => 'Veterinarian',
        ]);
        $otherPosition = Position::create([
            'division_id' => $otherDivision->id,
            'name' => 'Surgeon',
        ]);
        $manager = User::factory()->create([
            'role' => 'division_manager',
            'division_id' => $division->id,
        ]);

        $this->actingAs($manager)
            ->post(route('requisitions.store'), [
                'position_id' => $position->id,
                'quantity' => 2,
                'reason' => 'Increasing patient demand',
                'requested_by' => User::factory()->create()->id,
                'status' => 'approved',
            ])
            ->assertRedirect(route('requisitions.index'));

        $this->assertDatabaseHas('requisitions', [
            'position_id' => $position->id,
            'requested_by' => $manager->id,
            'status' => 'submitted',
            'quantity' => 2,
        ]);
        $this->get(route('requisitions.index'))
            ->assertOk()
            ->assertSee('Increasing patient demand');

        $this->post(route('requisitions.store'), [
            'position_id' => $otherPosition->id,
            'quantity' => 1,
            'reason' => 'Cross-division request',
        ])->assertSessionHasErrors('position_id');

        $position->delete();
        $this->post(route('requisitions.store'), [
            'position_id' => $position->id,
            'quantity' => 1,
            'reason' => 'Deleted position request',
        ])->assertSessionHasErrors('position_id');

        $this->assertDatabaseCount('requisitions', 1);
    }

    public function test_manager_can_only_edit_or_delete_their_own_pending_requisitions(): void
    {
        $division = Division::create(['name' => 'Nursing']);
        $position = Position::create([
            'division_id' => $division->id,
            'name' => 'Veterinary Nurse',
        ]);
        $manager = User::factory()->create([
            'role' => 'division_manager',
            'division_id' => $division->id,
        ]);
        $anotherManager = User::factory()->create([
            'role' => 'division_manager',
            'division_id' => $division->id,
        ]);
        $requisition = Requisition::create([
            'position_id' => $position->id,
            'requested_by' => $manager->id,
            'quantity' => 1,
            'reason' => 'Covering a vacancy',
        ]);

        $this->actingAs($anotherManager)
            ->put(route('requisitions.update', $requisition), [
                'position_id' => $position->id,
                'quantity' => 3,
                'reason' => 'Unauthorized update',
            ])->assertForbidden();

        $this->actingAs($manager)
            ->put(route('requisitions.update', $requisition), [
                'position_id' => $position->id,
                'quantity' => 3,
                'reason' => 'Updated staffing need',
                'status' => 'approved',
                'requested_by' => $anotherManager->id,
            ])->assertRedirect(route('requisitions.index'));

        $this->assertDatabaseHas('requisitions', [
            'id' => $requisition->id,
            'quantity' => 3,
            'status' => 'submitted',
            'requested_by' => $manager->id,
        ]);

        $requisition->update(['status' => 'approved']);
        $this->delete(route('requisitions.destroy', $requisition))->assertForbidden();
    }

    public function test_hrd_can_reject_with_a_note_or_approve_and_publish_once(): void
    {
        $division = Division::create(['name' => 'Clinic Operations']);
        $position = Position::create([
            'division_id' => $division->id,
            'name' => 'Animal Care Technician',
        ]);
        $archivedPosition = Position::create([
            'division_id' => $division->id,
            'name' => 'Veterinary Assistant',
        ]);
        $manager = User::factory()->create([
            'role' => 'division_manager',
            'division_id' => $division->id,
        ]);
        $hrd = User::factory()->create(['role' => 'hrd']);

        $rejected = Requisition::create([
            'position_id' => $position->id,
            'requested_by' => $manager->id,
            'quantity' => 1,
            'reason' => 'Temporary coverage',
        ]);
        $approved = Requisition::create([
            'position_id' => $archivedPosition->id,
            'requested_by' => $manager->id,
            'quantity' => 2,
            'reason' => 'Expanding patient care team',
        ]);
        $archivedPosition->delete();

        $this->actingAs($manager)
            ->get(route('job_postings.review'))
            ->assertForbidden();

        $this->actingAs($hrd)
            ->get(route('job_postings.review'))
            ->assertOk()
            ->assertSee('Veterinary Assistant');

        $this->actingAs($hrd)
            ->post(route('job_postings.process_review', $rejected), [
                'action' => 'reject',
            ])->assertSessionHasErrors('review_note');

        $this->post(route('job_postings.process_review', $rejected), [
            'action' => 'reject',
            'review_note' => 'Budget approval is pending.',
        ])->assertRedirect(route('job_postings.review'));

        $this->assertDatabaseHas('requisitions', [
            'id' => $rejected->id,
            'status' => 'rejected',
            'reviewed_by' => $hrd->id,
            'review_note' => 'Budget approval is pending.',
        ]);
        $this->assertDatabaseMissing('job_postings', ['requisition_id' => $rejected->id]);

        $deadline = now()->addDays(14)->toDateString();
        $this->post(route('job_postings.process_review', $approved), [
            'action' => 'approve',
            'description' => 'Provide daily care to clinic patients.',
            'deadline' => now()->subDay()->toDateString(),
        ])->assertSessionHasErrors('deadline');
        $this->assertDatabaseHas('requisitions', [
            'id' => $approved->id,
            'status' => 'submitted',
        ]);

        $this->post(route('job_postings.process_review', $approved), [
            'action' => 'approve',
            'description' => 'Provide daily care to clinic patients.',
            'requirements' => 'Veterinary care experience preferred.',
            'deadline' => $deadline,
            'review_note' => 'Approved for recruitment.',
        ])->assertRedirect(route('job_postings.review'));

        $posting = JobPosting::query()->where('requisition_id', $approved->id)->firstOrFail();
        $this->assertSame('open', $posting->status);
        $this->assertSame('Veterinary Assistant', $posting->title);
        $this->assertSame(2, $posting->quota);
        $this->assertSame($deadline, $posting->deadline->toDateString());
        $this->assertSame($hrd->id, $posting->created_by);
        $this->assertDatabaseHas('requisitions', [
            'id' => $approved->id,
            'status' => 'approved',
            'review_note' => 'Approved for recruitment.',
        ]);
        $this->get(route('job-postings.index'))
            ->assertOk()
            ->assertSee('Clinic Operations')
            ->assertSee('Veterinary Assistant');

        $this->post(route('job_postings.process_review', $approved), [
            'action' => 'approve',
            'description' => 'Duplicate attempt.',
            'deadline' => $deadline,
        ])->assertSessionHasErrors('requisition');

        $this->assertDatabaseCount('job_postings', 1);
    }

    public function test_job_posting_deadline_uses_jakarta_local_date(): void
    {
        $this->assertSame('Asia/Jakarta', config('app.timezone'));
    }
}
