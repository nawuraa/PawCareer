<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Interview;
use App\Models\StatusHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InterviewController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Interview::class);

        $interviews = Interview::query()
            ->where('interviewer_id', Auth::id())
            ->with(['application.applicant', 'application.jobPosting'])
            ->orderBy('scheduled_at')
            ->get();

        return view('hrd.interviews.index', compact('interviews'));
    }

    public function store(Request $request, Application $application): RedirectResponse
    {
        Gate::authorize('create', Interview::class);

        $validated = $request->validate([
            'interviewer_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('role', 'hrd'),
            ],
            'scheduled_at' => ['required', 'date_format:Y-m-d\TH:i', 'after:now'],
        ]);

        DB::transaction(function () use ($application, $validated): void {
            $lockedApplication = Application::query()
                ->lockForUpdate()
                ->findOrFail($application->id);

            if (! in_array($lockedApplication->status, ['screening_passed', 'interview'], true)) {
                throw ValidationException::withMessages([
                    'application' => 'Only applicants who passed screening can be scheduled for an interview.',
                ]);
            }

            $interviewer = User::query()->findOrFail($validated['interviewer_id']);
            if ($interviewer->role !== 'hrd') {
                throw ValidationException::withMessages([
                    'interviewer_id' => 'The interviewer must be an HRD user.',
                ]);
            }

            $scheduledAt = Carbon::createFromFormat(
                'Y-m-d\TH:i',
                $validated['scheduled_at'],
                config('app.timezone')
            );

            $interview = $lockedApplication->interviews()->create([
                'interviewer_id' => $interviewer->id,
                'scheduled_at' => $scheduledAt,
                'status' => 'scheduled',
            ]);
            $interview->statusHistories()->create([
                'old_status' => null,
                'new_status' => 'scheduled',
                'changed_by' => Auth::id(),
                'reason' => 'Interview scheduled.',
                'created_at' => now(),
            ]);

            if ($lockedApplication->status === 'screening_passed') {
                $lockedApplication->update(['status' => 'interview']);
                $lockedApplication->statusHistories()->create([
                    'old_status' => 'screening_passed',
                    'new_status' => 'interview',
                    'changed_by' => Auth::id(),
                    'reason' => 'Interview scheduled.',
                    'created_at' => now(),
                ]);
            }
        });

        return redirect()->route('hrd.applications.index')->with('success', 'Interview scheduled.');
    }

    public function evaluate(Request $request, Interview $interview): RedirectResponse
    {
        Gate::authorize('evaluate', $interview);

        $validated = $request->validate([
            'score' => ['required', 'integer', 'between:0,100'],
            'evaluation_notes' => ['required', 'string', 'max:10000'],
        ]);

        DB::transaction(function () use ($interview, $validated): void {
            $lockedInterview = Interview::query()
                ->lockForUpdate()
                ->findOrFail($interview->id);

            Gate::authorize('evaluate', $lockedInterview);

            $lockedInterview->update([
                'score' => $validated['score'],
                'evaluation_notes' => $validated['evaluation_notes'],
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            $lockedInterview->statusHistories()->create([
                'old_status' => 'scheduled',
                'new_status' => 'completed',
                'changed_by' => Auth::id(),
                'reason' => $validated['evaluation_notes'],
                'created_at' => now(),
            ]);
        });

        return redirect()->route('hrd.interviews.index')->with('success', 'Interview evaluation saved.');
    }
}
