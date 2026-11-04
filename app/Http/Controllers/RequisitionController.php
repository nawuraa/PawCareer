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

        // Mengarahkan data ke file blade (nantinya dibuat di resources/views/requisitions/index.blade.php)
        return view('requisitions.index', compact('requisitions'));
    }

    // Menyimpan pengajuan baru ke database
    public function store(Request $request)
    {
        // 1. Validasi Input sesuai PRD
        $validated = $request->validate([
            'position_id' => 'required|exists:positions,id',
            'quantity' => 'required|integer|min:1|max:50', // Aturan jumlah 1-50[cite: 1, 2]
            'reason' => 'required|string', // Alasan wajib[cite: 2]
        ]);

        $user = Auth::user();
        
        // 3. Simpan data pengajuan
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

    // Menampilkan halaman form tambah
    public function create()
    {
        $user = Auth::user();
        // Mengambil posisi yang sesuai dengan divisi manajer[cite: 1, 2]
        $positions = \App\Models\Position::where('division_id', $user->division_id)->get();
        return view('requisitions.create', compact('positions'));
    }

    // Menampilkan halaman form ubah
    public function edit(Requisition $requisition)
    {
        // Aturan: Hanya bisa diubah oleh pembuatnya dan saat status 'Diajukan'[cite: 1, 2]
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

    // Menghapus pengajuan
    public function destroy(Requisition $requisition)
    {
        if ($requisition->user_id !== Auth::id() || $requisition->status !== 'Diajukan') {
            abort(403, 'Akses ditolak: Hanya pengajuan Anda sendiri dengan status Diajukan yang bisa dihapus.');
        }

        // Mengeksekusi soft delete pada pengajuan[cite: 1, 2].
        $requisition->delete(); 

        return redirect()->route('requisitions.index')->with('success', 'Pengajuan berhasil dihapus.');
    }
}