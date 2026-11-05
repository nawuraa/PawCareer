<?php

namespace App\Http\Controllers;

use App\Models\ApplicantProfile;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ApplicantProfileController extends Controller
{
    public function edit(): View
    {
        Gate::authorize('create', Application::class);

        $profile = Auth::user()->applicantProfile;

        return view('applicant.profile', compact('profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        Gate::authorize('create', Application::class);

        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'education' => ['required', 'string', 'max:150'],
        ]);

        ApplicantProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        return redirect()->route('applicant.profile.edit')->with('success', 'Applicant profile updated.');
    }
}
