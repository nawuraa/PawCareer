<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DivisionController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Division::class);

        $divisions = Division::query()->orderBy('name')->get();

        return view('divisions.index', compact('divisions'));
    }

    public function create(): View
    {
        Gate::authorize('create', Division::class);

        return view('divisions.form', ['division' => new Division()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Division::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('divisions', 'name')],
            'description' => ['nullable', 'string'],
        ]);

        Division::create($validated);

        return redirect()->route('divisions.index')->with('success', 'Division created.');
    }

    public function edit(Division $division): View
    {
        Gate::authorize('update', $division);

        return view('divisions.form', compact('division'));
    }

    public function update(Request $request, Division $division): RedirectResponse
    {
        Gate::authorize('update', $division);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('divisions', 'name')->ignore($division)],
            'description' => ['nullable', 'string'],
        ]);

        $division->update($validated);

        return redirect()->route('divisions.index')->with('success', 'Division updated.');
    }

    public function destroy(Division $division): RedirectResponse
    {
        Gate::authorize('delete', $division);

        if ($division->users()->exists() || $division->positions()->exists()) {
            return back()->withErrors([
                'division' => 'A division with assigned users or active positions cannot be deleted.',
            ]);
        }

        $division->delete();

        return redirect()->route('divisions.index')->with('success', 'Division deleted.');
    }
}
