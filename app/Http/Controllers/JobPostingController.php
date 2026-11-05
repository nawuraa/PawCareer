<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\Requisition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class JobPostingController extends Controller
{
    public function indexReview(): View
    {
        Gate::authorize('viewAny', Requisition::class);

        $requisitions = Requisition::query()
            ->with(['position.division', 'requester'])
            ->where('status', 'submitted')
            ->latest()
            ->get();

        return view('job_postings.review', compact('requisitions'));
    }

    public function processReview(Request $request, Requisition $requisition): RedirectResponse
    {
        Gate::authorize('viewAny', Requisition::class);

        $decision = $request->validate([
            'action' => ['required', 'in:approve,reject'],
        ]);

        if ($decision['action'] === 'reject') {
            $review = $request->validate([
                'review_note' => ['required', 'string', 'max:10000'],
            ]);
        } else {
            $review = $request->validate([
                'title' => ['nullable', 'string', 'max:150'],
                'description' => ['required', 'string', 'max:10000'],
                'requirements' => ['nullable', 'string', 'max:10000'],
                'deadline' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
                'review_note' => ['nullable', 'string', 'max:10000'],
            ]);
        }

        $result = DB::transaction(function () use ($decision, $review, $requisition): string {
            $lockedRequisition = Requisition::query()
                ->with('position')
                ->lockForUpdate()
                ->findOrFail($requisition->id);

            if ($lockedRequisition->status !== 'submitted') {
                return 'already_reviewed';
            }

            if ($decision['action'] === 'reject') {
                $lockedRequisition->update([
                    'status' => 'rejected',
                    'reviewed_by' => Auth::id(),
                    'review_note' => $review['review_note'],
                    'reviewed_at' => now(),
                ]);

                return 'rejected';
            }

            if ($lockedRequisition->jobPosting()->withTrashed()->exists()) {
                return 'posting_exists';
            }

            $lockedRequisition->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'review_note' => $review['review_note'] ?? null,
                'reviewed_at' => now(),
            ]);

            $lockedRequisition->jobPosting()->create([
                'created_by' => Auth::id(),
                'title' => ($review['title'] ?? null) ?: $lockedRequisition->position->name,
                'description' => $review['description'],
                'requirements' => $review['requirements'] ?? null,
                'quota' => $lockedRequisition->quantity,
                'deadline' => $review['deadline'],
                'status' => 'open',
            ]);

            return 'approved';
        });

        return match ($result) {
            'approved' => redirect()->route('job_postings.review')->with('success', 'Requisition approved and job posting published.'),
            'rejected' => redirect()->route('job_postings.review')->with('success', 'Requisition rejected.'),
            'posting_exists' => back()->withErrors(['requisition' => 'A job posting already exists for this requisition.']),
            default => back()->withErrors(['requisition' => 'This requisition has already been reviewed.']),
        };
    }

    public function index(): View
    {
        Gate::authorize('viewAny', JobPosting::class);

        $jobPostings = JobPosting::query()
            ->with(['requisition.position.division', 'creator'])
            ->latest()
            ->get();

        return view('job_postings.index', compact('jobPostings'));
    }

    public function edit(JobPosting $jobPosting): View
    {
        Gate::authorize('update', $jobPosting);

        return view('job_postings.edit', compact('jobPosting'));
    }

    public function update(Request $request, JobPosting $jobPosting): RedirectResponse
    {
        Gate::authorize('update', $jobPosting);

        if ($jobPosting->status === 'closed') {
            return back()->with('error', 'Closed job postings cannot be changed.');
        }

        if ($request->has('close_job')) {
            $request->validate(['close_job' => ['accepted']]);
            $jobPosting->update(['status' => 'closed']);

            return redirect()->route('job-postings.index')->with('success', 'Job posting closed.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:10000'],
            'requirements' => ['nullable', 'string', 'max:10000'],
            'deadline' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ]);

        $jobPosting->update($validated);

        return redirect()->route('job-postings.index')->with('success', 'Job posting updated.');
    }

    public function destroy(JobPosting $jobPosting): RedirectResponse
    {
        Gate::authorize('delete', $jobPosting);

        if ($jobPosting->applications()->exists()) {
            return back()->with('error', 'Job postings with applicants cannot be deleted; close them instead.');
        }

        $jobPosting->delete();

        return redirect()->route('job-postings.index')->with('success', 'Job posting deleted.');
    }
}
