<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequisitionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $requisitions = Requisition::where('division_id', $user->division_id)->get();

        return view('requisitions.index', compact('requisitions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'position_id' => 'required|exists:positions,id',
            'quantity' => 'required|integer|min:1|max:50',
            'reason' => 'required|string',
        ]);

        $user = Auth::user();
        
        Requisition::create([
            'division_id' => $user->division_id,
            'user_id' => $user->id,
            'position_id' => $validated['position_id'],
            'quantity' => $validated['quantity'],
            'reason' => $validated['reason'],
            'status' => 'Diajukan',
        ]);

        return redirect()->route('requisitions.index')->with('success', 'Pengajuan berhasil dibuat.');
    }

    public function create()
    {
        $user = Auth::user();
        // Mengambil posisi yang sesuai dengan divisi manajer
        $positions = \App\Models\Position::where('division_id', $user->division_id)->get();
        return view('requisitions.create', compact('positions'));
    }

    public function edit(Requisition $requisition)
    {
        //Hanya bisa diubah oleh pembuatnya dan saat status 'Diajukan'
        if ($requisition->user_id !== Auth::id() || $requisition->status !== 'Diajukan') {
            abort(403, 'Akses ditolak.');
        }

        $user = Auth::user();
        $positions = \App\Models\Position::where('division_id', $user->division_id)->get();
        return view('requisitions.edit', compact('requisition', 'positions'));
    }

    public function update(Request $request, Requisition $requisition)
    {
        if ($requisition->user_id !== Auth::id() || $requisition->status !== 'Diajukan') {
            abort(403, 'Akses ditolak: Hanya pengajuan Anda sendiri dengan status Diajukan yang bisa diubah.');
        }

        $validated = $request->validate([
            'position_id' => 'required|exists:positions,id',
            'quantity' => 'required|integer|min:1|max:50',
            'reason' => 'required|string',
        ]);

        $requisition->update($validated);

        return redirect()->route('requisitions.index')->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function destroy(Requisition $requisition)
    {
        if ($requisition->user_id !== Auth::id() || $requisition->status !== 'Diajukan') {
            abort(403, 'Akses ditolak: Hanya pengajuan Anda sendiri dengan status Diajukan yang bisa dihapus.');
        }

        $requisition->delete(); 

        return redirect()->route('requisitions.index')->with('success', 'Pengajuan berhasil dihapus.');
    }
}