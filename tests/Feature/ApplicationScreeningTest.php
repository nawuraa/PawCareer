<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Division;
use App\Models\JobPosting;
use App\Models\Position;
use App\Models\Requisition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationScreeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_hrd_can_record_a_screening_decision_and_applicant_can_view_its_history(): void
    {
        [$application, $applicant] = $this->createApplicationRecord();
        $hrd = User::factory()->create(['role' => 'hrd']);

        $this->actingAs($hrd)
            ->get(route('hrd.applications.index'))
            ->assertOk()
            ->assertSee('Save screening decision')
            ->assertSee('Download CV');

        $this->put(route('hrd.applications.screening.update', $application), [
            'status' => 'screening_passed',
            'reason' => 'The applicant meets the clinical experience requirements.',
        ])->assertRedirect(route('hrd.applications.index'));

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'screening_passed',
        ]);
        $this->assertDatabaseHas('status_histories', [
            'subject_type' => Application::class,
            'subject_id' => $application->id,
            'old_status' => 'submitted',
            'new_status' => 'screening_passed',
            'changed_by' => $hrd->id,
            'reason' => 'The applicant meets the clinical experience requirements.',
        ]);

        $this->actingAs($applicant)
            ->get(route('applications.index'))
            ->assertOk()
            ->assertSee('Screening Passed')
            ->assertSee('The applicant meets the clinical experience requirements.');
    }

    public function test_only_hrd_can_screen_and_screening_requires_a_reason_and_submitted_status(): void
    {
        [$application, $applicant] = $this->createApplicationRecord();
        $divisionManager = User::factory()->create([
            'role' => 'division_manager',
            'division_id' => $application->jobPosting->requisition->position->division_id,
        ]);

        $this->actingAs($applicant)
            ->put(route('hrd.applications.screening.update', $application), [
                'status' => 'screening_failed',
                'reason' => 'Applicant request.',
            ])->assertForbidden();

        $this->actingAs($divisionManager)
            ->put(route('hrd.applications.screening.update', $application), [
                'status' => 'screening_failed',
                'reason' => 'Manager request.',
            ])->assertForbidden();

        $hrd = User::factory()->create(['role' => 'hrd']);
        $this->actingAs($hrd)
            ->put(route('hrd.applications.screening.update', $application), [
                'status' => 'accepted',
                'reason' => 'Invalid transition for screening.',
            ])->assertSessionHasErrors('status');

        $this->put(route('hrd.applications.screening.update', $application), [
            'status' => 'screening_failed',
        ])->assertSessionHasErrors('reason');

        $this->put(route('hrd.applications.screening.update', $application), [
            'status' => 'screening_failed',
            'reason' => 'Required clinical experience is not demonstrated.',
        ])->assertRedirect(route('hrd.applications.index'));

        $this->put(route('hrd.applications.screening.update', $application), [
            'status' => 'screening_passed',
            'reason' => 'This attempt must not replace the first decision.',
        ])->assertSessionHasErrors('status');

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'screening_failed',
        ]);
        $this->assertDatabaseCount('status_histories', 2);
    }

    private function createApplicationRecord(): array
    {
        $division = Division::create(['name' => 'Veterinary Medicine']);
        $position = Position::create([
            'division_id' => $division->id,
            'name' => 'Veterinarian',
        ]);
        $manager = User::factory()->create([
            'role' => 'division_manager',
            'division_id' => $division->id,
        ]);
        $requisition = Requisition::create([
            'position_id' => $position->id,
            'requested_by' => $manager->id,
            'quantity' => 1,
            'reason' => 'Clinic staffing need',
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
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
        $application->statusHistories()->create([
            'old_status' => null,
            'new_status' => 'submitted',
            'changed_by' => $applicant->id,
            'reason' => 'Application submitted.',
            'created_at' => now(),
        ]);

        return [$application, $applicant];
    }
}
