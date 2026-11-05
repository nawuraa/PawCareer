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

class InterviewWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_hrd_can_schedule_an_interview_with_an_hrd_interviewer_and_applicant_sees_schedule(): void
    {
        [$application, $applicant] = $this->createApplicationRecord('screening_passed');
        $hrd = User::factory()->create(['role' => 'hrd', 'name' => 'Assigned Interviewer']);
        $scheduledAt = now()->addDays(3)->setTime(10, 30)->format('Y-m-d\TH:i');

        $this->actingAs($hrd)
            ->get(route('hrd.applications.index'))
            ->assertOk()
            ->assertSee('Schedule interview')
            ->assertSee('Assigned Interviewer');

        $this->post(route('hrd.applications.interviews.store', $application), [
            'interviewer_id' => $hrd->id,
            'scheduled_at' => $scheduledAt,
            'application_id' => 99999,
        ])->assertRedirect(route('hrd.applications.index'));

        $interview = Interview::query()->firstOrFail();
        $this->assertSame($application->id, $interview->application_id);
        $this->assertSame($hrd->id, $interview->interviewer_id);
        $this->assertSame('scheduled', $interview->status);
        $this->assertSame($scheduledAt, $interview->scheduled_at->timezone('Asia/Jakarta')->format('Y-m-d\TH:i'));
        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'interview',
        ]);
        $this->assertDatabaseHas('status_histories', [
            'subject_type' => Application::class,
            'subject_id' => $application->id,
            'old_status' => 'screening_passed',
            'new_status' => 'interview',
            'changed_by' => $hrd->id,
        ]);
        $this->assertDatabaseHas('status_histories', [
            'subject_type' => Interview::class,
            'subject_id' => $interview->id,
            'new_status' => 'scheduled',
        ]);

        $this->actingAs($applicant)
            ->get(route('applications.index'))
            ->assertOk()
            ->assertSee($interview->scheduled_at->timezone('Asia/Jakarta')->format('d-m-Y H:i'));
    }

    public function test_hrd_can_only_schedule_passed_applicants_and_must_choose_an_hrd_and_future_time(): void
    {
        [$screeningFailed] = $this->createApplicationRecord('screening_failed');
        [$screeningPassed] = $this->createApplicationRecord('screening_passed');
        $hrd = User::factory()->create(['role' => 'hrd']);
        $manager = User::factory()->create([
            'role' => 'division_manager',
            'division_id' => $screeningPassed->jobPosting->requisition->position->division_id,
        ]);

        $this->actingAs($hrd)
            ->post(route('hrd.applications.interviews.store', $screeningFailed), [
                'interviewer_id' => $hrd->id,
                'scheduled_at' => now()->addDay()->format('Y-m-d\TH:i'),
            ])->assertSessionHasErrors('application');

        $this->post(route('hrd.applications.interviews.store', $screeningPassed), [
            'interviewer_id' => $manager->id,
            'scheduled_at' => now()->addDay()->format('Y-m-d\TH:i'),
        ])->assertSessionHasErrors('interviewer_id');

        $this->post(route('hrd.applications.interviews.store', $screeningPassed), [
            'interviewer_id' => $hrd->id,
            'scheduled_at' => now()->subDay()->format('Y-m-d\TH:i'),
        ])->assertSessionHasErrors('scheduled_at');

        $this->assertDatabaseCount('interviews', 0);
    }

    public function test_assigned_interviewer_can_complete_evaluation_but_other_roles_and_hrd_cannot(): void
    {
        [$application] = $this->createApplicationRecord('interview');
        $assignedInterviewer = User::factory()->create(['role' => 'hrd']);
        $otherHrd = User::factory()->create(['role' => 'hrd']);
        $interview = Interview::create([
            'application_id' => $application->id,
            'interviewer_id' => $assignedInterviewer->id,
            'scheduled_at' => now()->addDay(),
            'status' => 'scheduled',
        ]);

        $this->actingAs($otherHrd)
            ->get(route('hrd.interviews.index'))
            ->assertOk()
            ->assertSee('No interviews are assigned to you.');

        $this->put(route('hrd.interviews.evaluation.update', $interview), [
            'score' => 80,
            'evaluation_notes' => 'Not the assigned interviewer.',
        ])->assertForbidden();

        $applicant = $application->applicant;
        $this->actingAs($applicant)
            ->put(route('hrd.interviews.evaluation.update', $interview), [
                'score' => 80,
                'evaluation_notes' => 'Applicant must not evaluate.',
            ])->assertForbidden();

        $this->actingAs($assignedInterviewer)
            ->get(route('hrd.interviews.index'))
            ->assertOk()
            ->assertSee($applicant->email);

        $this->put(route('hrd.interviews.evaluation.update', $interview), [
            'score' => 101,
            'evaluation_notes' => 'Out of bounds.',
        ])->assertSessionHasErrors('score');

        $this->put(route('hrd.interviews.evaluation.update', $interview), [
            'score' => 87,
            'evaluation_notes' => 'Strong clinical judgment and communication.',
        ])->assertRedirect(route('hrd.interviews.index'));

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'score' => 87,
            'status' => 'completed',
            'interviewer_id' => $assignedInterviewer->id,
        ]);
        $this->assertNotNull($interview->fresh()->completed_at);
        $this->assertDatabaseHas('status_histories', [
            'subject_type' => Interview::class,
            'subject_id' => $interview->id,
            'old_status' => 'scheduled',
            'new_status' => 'completed',
            'changed_by' => $assignedInterviewer->id,
            'reason' => 'Strong clinical judgment and communication.',
        ]);

        $this->put(route('hrd.interviews.evaluation.update', $interview), [
            'score' => 90,
            'evaluation_notes' => 'Cannot edit a completed interview.',
        ])->assertForbidden();
    }

    private function createApplicationRecord(string $status): array
    {
        $division = Division::create(['name' => 'Clinic Division '.uniqid()]);
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
            'description' => 'Provide patient care.',
            'quota' => 1,
            'deadline' => now()->addDays(10)->toDateString(),
            'status' => 'open',
        ]);
        $applicant = User::factory()->create(['role' => 'applicant']);
        $application = Application::create([
            'job_posting_id' => $posting->id,
            'applicant_id' => $applicant->id,
            'cv_path' => "applications/{$applicant->id}/cv.pdf",
            'status' => $status,
            'submitted_at' => now(),
        ]);

        return [$application, $applicant];
    }
}
