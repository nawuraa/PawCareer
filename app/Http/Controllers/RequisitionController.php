<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Requisition;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RequisitionController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Requisition::class);

        $user = Auth::user();
        $requisitions = Requisition::query()
            ->with(['position.division', 'requester'])
            ->whereHas('position', fn ($query) => $query->where('division_id', $user->division_id))
            ->latest()
            ->get();

        return view('requisitions.index', compact('requisitions'));
    }

    public function create(): View
    {
        Gate::authorize('create', Requisition::class);

        $positions = $this->positionsForCurrentDivision();

        return view('requisitions.create', compact('positions'));
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Requisition::class);

        $validated = $this->validateRequisition($request);
        $validated['requested_by'] = Auth::id();

        Requisition::create($validated);

        return redirect()->route('requisitions.index')->with('success', 'Requisition submitted for HRD review.');
    }

    public function edit(Requisition $requisition): View
    {
        Gate::authorize('update', $requisition);

        $positions = $this->positionsForCurrentDivision();

        return view('requisitions.edit', compact('requisition', 'positions'));
    }

    public function update(Request $request, Requisition $requisition): RedirectResponse
    {
        Gate::authorize('update', $requisition);

        $requisition->update($this->validateRequisition($request));

        return redirect()->route('requisitions.index')->with('success', 'Requisition updated.');
    }

    public function destroy(Requisition $requisition): RedirectResponse
    {
        Gate::authorize('delete', $requisition);

        $requisition->delete();

        return redirect()->route('requisitions.index')->with('success', 'Requisition deleted.');
    }

    private function positionsForCurrentDivision(): Collection
    {
        return Position::query()
            ->where('division_id', Auth::user()->division_id)
            ->with('division')
            ->orderBy('name')
            ->get();
    }

    /**
     * @return array{position_id: int, quantity: int, reason: string}
     */
    private function validateRequisition(Request $request): array
    {
        $divisionId = Auth::user()->division_id;

        return $request->validate([
            'position_id' => [
                'required',
                'integer',
                Rule::exists('positions', 'id')
                    ->where('division_id', $divisionId)
                    ->whereNull('deleted_at'),
            ],
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'reason' => ['required', 'string', 'max:10000'],
        ]);
    }
}
