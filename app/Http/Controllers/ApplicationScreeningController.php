<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ApplicationScreeningController extends Controller
{
    public function update(Request $request, Application $application): RedirectResponse
    {
        Gate::authorize('update', $application);

        $validated = $request->validate([
            'status' => ['required', 'in:screening_passed,screening_failed'],
            'reason' => ['required', 'string', 'max:5000'],
        ]);

        DB::transaction(function () use ($application, $validated): void {
            $lockedApplication = Application::query()
                ->lockForUpdate()
                ->findOrFail($application->id);

            if ($lockedApplication->status !== 'submitted') {
                throw ValidationException::withMessages([
                    'status' => 'Only submitted applications can receive a screening decision.',
                ]);
            }

            $oldStatus = $lockedApplication->status;
            $lockedApplication->update(['status' => $validated['status']]);

            $lockedApplication->statusHistories()->create([
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'changed_by' => Auth::id(),
                'reason' => $validated['reason'],
                'created_at' => now(),
            ]);
        });

        return redirect()->route('hrd.applications.index')->with('success', 'Application screening decision recorded.');
    }
}
