<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Division;
use App\Models\HiringDecision;
use App\Models\Interview;
use App\Models\JobPosting;
use App\Models\Position;
use App\Models\Requisition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecruitmentJourneyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    public function test_recruitment_journey_from_staff_request_to_final_applicant_outcome(): void
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

        $this->actingAs($manager)
            ->get(route('requisitions.create'))
            ->assertOk()
            ->assertSee('Veterinarian');

        $this->post(route('requisitions.store'), [
            'position_id' => $position->id,
            'quantity' => 1,
            'reason' => 'Additional patient appointments require another veterinarian.',
        ])->assertRedirect(route('requisitions.index'));

        $requisition = Requisition::query()->firstOrFail();
        $this->assertSame($manager->id, $requisition->requested_by);

        $hrd = User::factory()->create(['role' => 'hrd']);
        $this->actingAs($hrd)
            ->get(route('job_postings.review'))
            ->assertOk()
            ->assertSee('Veterinarian');

        $deadline = now()->addDays(30)->toDateString();
        $this->post(route('job_postings.process_review', $requisition), [
            'action' => 'approve',
            'title' => 'Veterinarian - Small Animal Care',
            'description' => 'Provide safe, compassionate clinical care to animal patients.',
            'requirements' => 'Veterinary medicine qualification and clinical experience.',
            'deadline' => $deadline,
        ])->assertRedirect(route('job_postings.review'));

        $posting = JobPosting::query()->firstOrFail();
        $this->assertSame('approved', $requisition->fresh()->status);
        $this->assertSame('open', $posting->status);

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->post(route('register.post'), [
            'name' => 'Jordan Applicant',
            'email' => 'jordan@example.test',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
            'role' => 'hrd',
            'division_id' => $division->id,
        ])->assertRedirect(route('applicant.profile.edit'));

        $applicant = User::query()->where('email', 'jordan@example.test')->firstOrFail();
        $this->assertSame('applicant', $applicant->role);
        $this->put(route('applicant.profile.update'), [
            'phone' => '+628123456789',
            'education' => 'Doctor of Veterinary Medicine',
        ])->assertRedirect(route('applicant.profile.edit'));
        $this->get(route('applicant.jobs.index'))
            ->assertOk()
            ->assertSee('Veterinarian - Small Animal Care');
        $this->get(route('applicant.jobs.show', $posting))
            ->assertOk()
            ->assertSee('compassionate clinical care');

        $this->post(route('applications.store', $posting), [
            'cv' => UploadedFile::fake()->create('jordan-cv.pdf', 200, 'application/pdf'),
        ])->assertRedirect(route('applications.index'));

        $application = Application::query()->firstOrFail();
        Storage::disk('local')->assertExists($application->cv_path);
        $this->assertSame($applicant->id, $application->applicant_id);

        $this->actingAs($hrd)
            ->put(route('hrd.applications.screening.update', $application), [
                'status' => 'screening_passed',
                'reason' => 'The applicant meets the clinical experience requirements.',
            ])->assertRedirect(route('hrd.applications.index'));

        $interviewer = User::factory()->create(['role' => 'hrd', 'name' => 'Clinic Interviewer']);
        $scheduledAt = now()->addDays(5)->setTime(9, 0)->format('Y-m-d\TH:i');
        $this->actingAs($hrd)
            ->post(route('hrd.applications.interviews.store', $application), [
                'interviewer_id' => $interviewer->id,
                'scheduled_at' => $scheduledAt,
            ])->assertRedirect(route('hrd.applications.index'));

        $interview = Interview::query()->firstOrFail();
        $this->actingAs($applicant)
            ->get(route('applications.index'))
            ->assertOk()
            ->assertSee($interview->scheduled_at->timezone('Asia/Jakarta')->format('d-m-Y H:i'));

        $this->actingAs($interviewer)
            ->put(route('hrd.interviews.evaluation.update', $interview), [
                'score' => 92,
                'evaluation_notes' => 'Excellent clinical reasoning and communication.',
            ])->assertRedirect(route('hrd.interviews.index'));

        $this->actingAs($hrd)
            ->post(route('hrd.applications.hiring-decision.store', $application), [
                'result' => 'accepted',
                'start_date' => now()->addDays(40)->toDateString(),
                'note' => 'Offer approved after successful interview evaluation.',
            ])->assertRedirect(route('hrd.applications.index'));

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'accepted',
        ]);
        $this->assertDatabaseHas('hiring_decisions', [
            'application_id' => $application->id,
            'result' => 'accepted',
            'decided_by' => $hrd->id,
        ]);
        $this->assertDatabaseHas('status_histories', [
            'subject_type' => Application::class,
            'subject_id' => $application->id,
            'old_status' => 'interview',
            'new_status' => 'accepted',
        ]);
        $this->assertSame(92, $interview->fresh()->score);
        $this->assertNotNull($interview->fresh()->completed_at);
        $this->assertSame(1, HiringDecision::query()->count());

        $this->actingAs($applicant)
            ->get(route('applications.index'))
            ->assertOk()
            ->assertSee('Final outcome: Accepted')
            ->assertSee('Screening Passed')
            ->assertDontSee('Offer approved after successful interview evaluation.');
    }
}
