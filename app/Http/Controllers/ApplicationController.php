<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationController extends Controller
{
    private const ACTIVE_STATUSES = ['submitted', 'screening_passed', 'interview'];

    public function index(): View
    {
        Gate::authorize('viewAny', Application::class);

        $applications = Application::query()
            ->with(['jobPosting.requisition.position.division', 'applicant', 'statusHistories.changer'])
            ->when(
                Auth::user()->role === 'applicant',
                fn ($query) => $query->where('applicant_id', Auth::id())
            )
            ->latest('submitted_at')
            ->get();

        return view(
            Auth::user()->role === 'hrd' ? 'hrd.applications.index' : 'applicant.applications.index',
            compact('applications')
        );
    }

    public function store(Request $request, JobPosting $jobPosting): RedirectResponse
    {
        Gate::authorize('create', Application::class);

        $validated = $request->validate([
            'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $user = Auth::user();
        DB::transaction(function () use ($jobPosting, $user, $validated): void {
            User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();

            $profile = $user->applicantProfile()->first();
            if (! $profile || blank($profile->phone) || blank($profile->education)) {
                throw ValidationException::withMessages([
                    'profile' => 'Complete your phone number and education before applying.',
                ]);
            }

            $posting = JobPosting::query()->lockForUpdate()->findOrFail($jobPosting->id);
            if ($posting->status !== 'open' || $posting->deadline->toDateString() < now()->toDateString()) {
                abort(404);
            }

            if (Application::query()
                ->where('applicant_id', $user->id)
                ->where('job_posting_id', $posting->id)
                ->exists()) {
                throw ValidationException::withMessages([
                    'application' => 'You have already applied for this job posting.',
                ]);
            }

            $activeCount = Application::query()
                ->where('applicant_id', $user->id)
                ->whereIn('status', self::ACTIVE_STATUSES)
                ->count();

            if ($activeCount >= 3) {
                throw ValidationException::withMessages([
                    'application_limit' => 'You can have no more than three active applications at a time.',
                ]);
            }

            $path = $validated['cv']->store("applications/{$user->id}", 'local');
            if ($path === false) {
                throw new \RuntimeException('The CV could not be stored.');
            }

            Application::create([
                'job_posting_id' => $posting->id,
                'applicant_id' => $user->id,
                'cv_path' => $path,
                'status' => 'submitted',
                'submitted_at' => now(),
            ])->statusHistories()->create([
                'old_status' => null,
                'new_status' => 'submitted',
                'changed_by' => $user->id,
                'reason' => 'Application submitted.',
                'created_at' => now(),
            ]);

        });

        return redirect()->route('applications.index')->with('success', 'Your application was submitted.');
    }

    public function downloadCv(Application $application): StreamedResponse
    {
        Gate::authorize('view', $application);

        abort_unless(Storage::disk('local')->exists($application->cv_path), 404);

        return Storage::disk('local')->download($application->cv_path);
    }
}
