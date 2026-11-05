<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobPostingController extends Controller
{
    public function indexReview()
    {
        // HRD melihat pengajuan yang statusnya masih 'Diajukan'
        $requisitions = Requisition::with('position', 'manager.division')
            ->where('status', 'Diajukan')
            ->get();
            
        return view('job_postings.review', compact('requisitions'));
    }

    public function processReview(Request $request, Requisition $requisition)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'hrd_notes' => 'required_if:action,reject|nullable|string'
        ]);

        if ($validated['action'] === 'reject') {
            // ditolak bersifat final
            $requisition->update([
                'status' => 'Ditolak',
                'hrd_notes' => $validated['hrd_notes']
            ]);
            
            return redirect()->route('job_postings.review')->with('success', 'Pengajuan berhasil ditolak.');
        }

        $requisition->update([
            'status' => 'Disetujui',
            'hrd_notes' => $validated['hrd_notes'] ?? null
        ]);

        JobPosting::create([
            'requisition_id' => $requisition->id,
            'created_by' => auth()->id(),
            'title' => 'Lowongan ' . ($requisition->position->name ?? 'Baru'), 
            'quota' => $requisition->quantity,
            'status' => 'open'
        ]);

        return redirect()->route('job_postings.review')->with('success', 'Pengajuan disetujui dan lowongan berhasil diterbitkan otomatis.');
    }

    public function index()
    {
        $jobPostings = JobPosting::with('requisition.position', 'creator')->get();
        return view('job_postings.index', compact('jobPostings'));
    }

    public function edit(JobPosting $jobPosting)
    {
        return view('job_postings.edit', compact('jobPosting'));
    }

    public function update(Request $request, JobPosting $jobPosting)
    {
        //Lowongan yang sudah ditutup tidak bisa diubah atau dibuka kembali
        if ($jobPosting->status === 'closed') {
            return redirect()->back()->with('error', 'Lowongan yang sudah ditutup tidak dapat diubah.');
        }

        if ($request->has('close_job')) {
            $jobPosting->update(['status' => 'closed']);
            return redirect()->route('job_postings.index')->with('success', 'Lowongan berhasil ditutup manual.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'deadline' => 'nullable|date|after_or_equal:today',
        ]);

        $jobPosting->update($validated);

        return redirect()->route('job_postings.index')->with('success', 'Detail lowongan berhasil diperbarui.');
    }

    public function destroy(JobPosting $jobPosting)
    {
        //Lowongan yang sudah memiliki lamaran tidak dapat dihapus, hanya bisa ditutup
        if ($jobPosting->applications()->exists()) {
            return redirect()->back()->with('error', 'Gagal dihapus! Lowongan ini sudah memiliki pelamar. Anda hanya bisa menutupnya.');
        }

        $jobPosting->delete();

        return redirect()->route('job_postings.index')->with('success', 'Lowongan berhasil dihapus (masuk ke tempat sampah).');
    }
}
