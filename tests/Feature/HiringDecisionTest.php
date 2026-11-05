<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Division;
use App\Models\Interview;
use App\Models\JobPosting;
use App\Models\Position;
use App\Models\Requisition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HiringDecisionTest extends TestCase
{
    use RefreshDatabase;

    public function test_hrd_can_accept_after_interviews_and_applicant_sees_outcome_but_not_internal_note(): void
    {
        [$application, $applicant] = $this->createApplicationWithCompletedInterview();
        $hrd = User::factory()->create(['role' => 'hrd']);

        $this->actingAs($hrd)
            ->get(route('hrd.applications.index'))
            ->assertOk()
            ->assertSee('Record final decision');

        $this->post(route('hrd.applications.hiring-decision.store', $application), [
            'result' => 'accepted',
            'start_date' => now()->addDays(14)->toDateString(),
            'note' => 'Offer approved by clinic leadership.',
        ])->assertRedirect(route('hrd.applications.index'));

        $this->assertDatabaseHas('hiring_decisions', [
            'application_id' => $application->id,
            'decided_by' => $hrd->id,
            'result' => 'accepted',
            'note' => 'Offer approved by clinic leadership.',
        ]);
        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'accepted',
        ]);
        $this->assertDatabaseHas('status_histories', [
            'subject_type' => Application::class,
            'subject_id' => $application->id,
            'old_status' => 'interview',
            'new_status' => 'accepted',
            'changed_by' => $hrd->id,
            'reason' => 'Final hiring decision recorded.',
        ]);

        $this->actingAs($applicant)
            ->get(route('applications.index'))
            ->assertOk()
            ->assertSee('Final outcome: Accepted')
            ->assertSee(now()->addDays(14)->format('d-m-Y'))
            ->assertDontSee('Offer approved by clinic leadership.');
    }

    public function test_final_decision_requires_all_interviews_completed_and_acceptance_requires_start_date(): void
    {
        [$application] = $this->createApplicationWithCompletedInterview();
        $hrd = User::factory()->create(['role' => 'hrd']);
        Interview::create([
            'application_id' => $application->id,
            'interviewer_id' => $hrd->id,
            'scheduled_at' => now()->addDay(),
            'status' => 'scheduled',
        ]);

        $this->actingAs($hrd)
            ->post(route('hrd.applications.hiring-decision.store', $application), [
                'result' => 'accepted',
                'start_date' => now()->addDays(10)->toDateString(),
                'note' => 'Approved.',
            ])->assertSessionHasErrors('application');

        Interview::query()->where('application_id', $application->id)
            ->where('status', 'scheduled')
            ->update(['status' => 'cancelled']);

        $this->post(route('hrd.applications.hiring-decision.store', $application), [
            'result' => 'accepted',
            'note' => 'Approved.',
        ])->assertSessionHasErrors('start_date');

        $this->post(route('hrd.applications.hiring-decision.store', $application), [
            'result' => 'rejected',
            'start_date' => now()->addDays(10)->toDateString(),
            'note' => 'The role requirements were not met.',
        ])->assertRedirect(route('hrd.applications.index'));

        $this->assertDatabaseHas('hiring_decisions', [
            'application_id' => $application->id,
            'result' => 'rejected',
            'start_date' => null,
        ]);
        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'rejected',
        ]);
    }

    public function test_only_hrd_can_record_a_single_final_decision_after_interview_stage(): void
    {
        [$application, $applicant] = $this->createApplicationWithCompletedInterview();
        $hrd = User::factory()->create(['role' => 'hrd']);

        $this->actingAs($applicant)
            ->post(route('hrd.applications.hiring-decision.store', $application), [
                'result' => 'rejected',
                'note' => 'Unauthorized.',
            ])->assertForbidden();

        $this->actingAs($hrd)
            ->post(route('hrd.applications.hiring-decision.store', $application), [
                'result' => 'rejected',
                'note' => 'Final decision.',
            ])->assertRedirect(route('hrd.applications.index'));

        $this->post(route('hrd.applications.hiring-decision.store', $application), [
            'result' => 'accepted',
            'start_date' => now()->addDays(10)->toDateString(),
            'note' => 'Attempt to replace a final decision.',
        ])->assertSessionHasErrors('application');

        [$notInterviewed] = $this->createApplicationWithCompletedInterview('screening_failed', false);
        $this->post(route('hrd.applications.hiring-decision.store', $notInterviewed), [
            'result' => 'rejected',
            'note' => 'It is too early to decide.',
        ])->assertSessionHasErrors('application');

        $this->assertDatabaseCount('hiring_decisions', 1);
    }

    private function createApplicationWithCompletedInterview(
        string $applicationStatus = 'interview',
        bool $withCompletedInterview = true
    ): array
    {
        $division = Division::create(['name' => 'Veterinary Medicine '.uniqid()]);
        $position = Position::create([
            'division_id' => $division->id,
            'name' => 'Veterinarian '.uniqid(),
        ]);
        $manager = User::factory()->create([
            'role' => 'division_manager',
            'division_id' => $division->id,
        ]);
        $requisition = Requisition::create([
            'position_id' => $position->id,
            'requested_by' => $manager->id,
            'quantity' => 1,
            'reason' => 'Clinic hiring need',
            'status' => 'approved',
        ]);
        $posting = JobPosting::create([
            'requisition_id' => $requisition->id,
            'created_by' => User::factory()->create(['role' => 'hrd'])->id,
            'title' => 'Veterinarian',
            'description' => 'Provide veterinary care.',
            'quota' => 1,
            'deadline' => now()->addDays(10)->toDateString(),
            'status' => 'open',
        ]);
        $applicant = User::factory()->create(['role' => 'applicant']);
        $application = Application::create([
            'job_posting_id' => $posting->id,
            'applicant_id' => $applicant->id,
            'cv_path' => "applications/{$applicant->id}/cv.pdf",
            'status' => $applicationStatus,
            'submitted_at' => now()->subDays(5),
        ]);
        if ($withCompletedInterview) {
            Interview::create([
                'application_id' => $application->id,
                'interviewer_id' => User::factory()->create(['role' => 'hrd'])->id,
                'scheduled_at' => now()->subDay(),
                'status' => 'completed',
                'score' => 85,
                'evaluation_notes' => 'Good clinical experience.',
                'completed_at' => now()->subDay(),
            ]);
        }

        return [$application, $applicant];
    }
}
