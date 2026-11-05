<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PositionController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Position::class);

        $positions = Position::query()->with('division')->orderBy('name')->get();

        return view('positions.index', compact('positions'));
    }

    public function create(): View
    {
        Gate::authorize('create', Position::class);

        return view('positions.form', [
            'position' => new Position(),
            'divisions' => Division::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Position::class);

        $validated = $this->validatePosition($request);
        Position::create($validated);

        return redirect()->route('positions.index')->with('success', 'Position created.');
    }

    public function edit(Position $position): View
    {
        Gate::authorize('update', $position);

        return view('positions.form', [
            'position' => $position,
            'divisions' => Division::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Position $position): RedirectResponse
    {
        Gate::authorize('update', $position);

        $position->update($this->validatePosition($request, $position));

        return redirect()->route('positions.index')->with('success', 'Position updated.');
    }

    public function destroy(Position $position): RedirectResponse
    {
        Gate::authorize('delete', $position);

        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Position deleted.');
    }

    /**
     * @return array{division_id: int, name: string, description: ?string}
     */
    private function validatePosition(Request $request, ?Position $position = null): array
    {
        $validated = $request->validate([
            'division_id' => [
                'required',
                'integer',
                Rule::exists('divisions', 'id')->whereNull('deleted_at'),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('positions', 'name')
                    ->where(fn ($query) => $query->where('division_id', $request->input('division_id')))
                    ->ignore($position),
            ],
            'description' => ['nullable', 'string'],
        ]);

        return $validated;
    }
}
