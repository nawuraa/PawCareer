<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class HiringDecisionController extends Controller
{
    public function store(Request $request, Application $application): RedirectResponse
    {
        Gate::authorize('update', $application);

        $validated = $request->validate([
            'result' => ['required', 'in:accepted,rejected'],
            'start_date' => ['nullable', 'required_if:result,accepted', 'date_format:Y-m-d', 'after_or_equal:today'],
            'note' => ['required', 'string', 'max:10000'],
        ]);

        DB::transaction(function () use ($application, $validated): void {
            $lockedApplication = Application::query()
                ->lockForUpdate()
                ->findOrFail($application->id);

            if ($lockedApplication->status !== 'interview') {
                throw ValidationException::withMessages([
                    'application' => 'A final decision can only be recorded after the interview stage.',
                ]);
            }

            $hasCompletedInterview = $lockedApplication->interviews()
                ->where('status', 'completed')
                ->exists();
            $hasPendingInterview = $lockedApplication->interviews()
                ->where('status', 'scheduled')
                ->exists();

            if (! $hasCompletedInterview || $hasPendingInterview) {
                throw ValidationException::withMessages([
                    'application' => 'Complete all scheduled interviews before recording a final decision.',
                ]);
            }

            if ($lockedApplication->hiringDecision()->exists()) {
                throw ValidationException::withMessages([
                    'application' => 'A final hiring decision has already been recorded.',
                ]);
            }

            $result = $validated['result'];
            $oldStatus = $lockedApplication->status;

            $lockedApplication->hiringDecision()->create([
                'decided_by' => Auth::id(),
                'result' => $result,
                'start_date' => $result === 'accepted' ? $validated['start_date'] : null,
                'note' => $validated['note'],
                'decided_at' => now(),
            ]);

            $lockedApplication->update(['status' => $result]);
            $lockedApplication->statusHistories()->create([
                'old_status' => $oldStatus,
                'new_status' => $result,
                'changed_by' => Auth::id(),
                'reason' => 'Final hiring decision recorded.',
                'created_at' => now(),
            ]);
        });

        return redirect()->route('hrd.applications.index')->with('success', 'Final hiring decision recorded.');
    }
}
