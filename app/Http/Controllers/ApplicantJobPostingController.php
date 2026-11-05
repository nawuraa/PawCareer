<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ApplicantJobPostingController extends Controller
{
    public function index(): View
    {
        Gate::authorize('create', Application::class);

        $jobPostings = JobPosting::query()
            ->where('status', 'open')
            ->whereDate('deadline', '>=', now()->toDateString())
            ->with(['requisition.position.division'])
            ->orderBy('deadline')
            ->get();

        return view('applicant.jobs.index', compact('jobPostings'));
    }

    public function show(JobPosting $jobPosting): View
    {
        Gate::authorize('create', Application::class);

        abort_unless(
            $jobPosting->status === 'open' && $jobPosting->deadline->toDateString() >= now()->toDateString(),
            404
        );

        $hasApplied = $jobPosting->applications()
            ->where('applicant_id', auth()->id())
            ->exists();

        return view('applicant.jobs.show', compact('jobPosting', 'hasApplied'));
    }
}
