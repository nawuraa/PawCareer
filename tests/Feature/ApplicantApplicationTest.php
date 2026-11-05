<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\ApplicantProfile;
use App\Models\Division;
use App\Models\JobPosting;
use App\Models\Position;
use App\Models\Requisition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplicantApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    public function test_applicant_must_complete_profile_before_submitting_a_private_cv(): void
    {
        [$posting] = $this->createOpenPosting('Veterinarian');
        $applicant = User::factory()->create(['role' => 'applicant']);
        $this->actingAs($applicant);

        $this->get(route('applicant.jobs.index'))
            ->assertOk()
            ->assertSee('Veterinarian');
        $this->get(route('applicant.jobs.show', $posting))
            ->assertOk()
            ->assertSee('Submit application');

        $this->post(route('applications.store', $posting), [
            'cv' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('profile');

        $this->put(route('applicant.profile.update'), [
            'phone' => '+62123456789',
            'education' => 'Doctor of Veterinary Medicine',
            'user_id' => User::factory()->create()->id,
        ])->assertRedirect(route('applicant.profile.edit'));

        $this->post(route('applications.store', $posting), [
            'cv' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
            'applicant_id' => User::factory()->create()->id,
            'status' => 'accepted',
            'submitted_at' => now()->subYear(),
        ])->assertRedirect(route('applications.index'));

        $application = Application::query()->firstOrFail();
        $this->assertSame($applicant->id, $application->applicant_id);
        $this->assertSame('submitted', $application->status);
        $this->assertNotNull($application->submitted_at);
        $this->assertStringStartsWith("applications/{$applicant->id}/", $application->cv_path);
        Storage::disk('local')->assertExists($application->cv_path);

        $this->get(route('applications.index'))
            ->assertOk()
            ->assertSee('Veterinarian')
            ->assertSee('Download my CV');
        $this->get(route('applications.cv', $application))->assertOk();
    }

    public function test_applicant_can_have_at_most_three_active_applications_and_cannot_reapply_to_a_posting(): void
    {
        $postings = [];
        for ($index = 1; $index <= 4; $index++) {
            [$postings[]] = $this->createOpenPosting("Veterinary role {$index}");
        }

        $applicant = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $applicant->id,
            'phone' => '08123456789',
            'education' => 'Veterinary Medicine',
        ]);
        $this->actingAs($applicant);

        foreach (array_slice($postings, 0, 3) as $posting) {
            $this->post(route('applications.store', $posting), [
                'cv' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
            ])->assertRedirect(route('applications.index'));
        }

        $this->post(route('applications.store', $postings[3]), [
            'cv' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('application_limit');
        $this->assertDatabaseCount('applications', 3);

        $firstApplication = Application::query()->where('job_posting_id', $postings[0]->id)->firstOrFail();
        $firstApplication->update(['status' => 'rejected']);

        $this->post(route('applications.store', $postings[0]), [
            'cv' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('application');

        $this->post(route('applications.store', $postings[3]), [
            'cv' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ])->assertRedirect(route('applications.index'));

        $this->assertDatabaseCount('applications', 4);
    }

    public function test_applications_are_limited_to_valid_cv_files_and_current_open_postings(): void
    {
        [$posting] = $this->createOpenPosting('Clinic receptionist');
        $applicant = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $applicant->id,
            'phone' => '08123456789',
            'education' => 'Veterinary Administration',
        ]);
        $this->actingAs($applicant);

        $this->post(route('applications.store', $posting), [
            'cv' => UploadedFile::fake()->create('resume.exe', 100, 'application/octet-stream'),
        ])->assertSessionHasErrors('cv');

        $posting->update(['status' => 'closed']);
        $this->get(route('applicant.jobs.show', $posting))->assertNotFound();

        $posting->update(['status' => 'open', 'deadline' => now()->subDay()->toDateString()]);
        $this->get(route('applicant.jobs.index'))->assertDontSee('Clinic receptionist');
        $this->post(route('applications.store', $posting), [
            'cv' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ])->assertNotFound();

        $this->assertDatabaseCount('applications', 0);
    }

    public function test_only_hrd_can_view_all_applications_and_other_applicants_cannot_download_cv(): void
    {
        [$posting] = $this->createOpenPosting('Veterinary nurse');
        $applicant = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $applicant->id,
            'phone' => '08123456789',
            'education' => 'Nursing',
        ]);
        $application = Application::create([
            'job_posting_id' => $posting->id,
            'applicant_id' => $applicant->id,
            'cv_path' => 'applications/'.$applicant->id.'/resume.pdf',
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
        Storage::disk('local')->put($application->cv_path, 'private cv');

        $otherApplicant = User::factory()->create(['role' => 'applicant']);
        $this->actingAs($otherApplicant)
            ->get(route('hrd.applications.index'))
            ->assertForbidden();
        $this->get(route('applications.cv', $application))->assertForbidden();

        $hrd = User::factory()->create(['role' => 'hrd']);
        $this->actingAs($hrd)
            ->get(route('hrd.applications.index'))
            ->assertOk()
            ->assertSee($applicant->email)
            ->assertSee('Veterinary nurse');
        $this->get(route('applications.cv', $application))->assertOk();
        $this->post(route('applications.store', $posting), [
            'cv' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ])->assertForbidden();
    }

    private function createOpenPosting(string $title): array
    {
        $division = Division::create(['name' => $title.' Division']);
        $position = Position::create([
            'division_id' => $division->id,
            'name' => $title,
        ]);
        $manager = User::factory()->create([
            'role' => 'division_manager',
            'division_id' => $division->id,
        ]);
        $requisition = Requisition::create([
            'position_id' => $position->id,
            'requested_by' => $manager->id,
            'quantity' => 1,
            'reason' => 'Staffing need',
            'status' => 'approved',
        ]);
        $posting = JobPosting::create([
            'requisition_id' => $requisition->id,
            'created_by' => User::factory()->create(['role' => 'hrd'])->id,
            'title' => $title,
            'description' => 'Support clinic operations.',
            'quota' => 1,
            'deadline' => now()->addDays(7)->toDateString(),
            'status' => 'open',
        ]);

        return [$posting, $division, $position];
    }
}
