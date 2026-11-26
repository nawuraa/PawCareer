<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;

class DashboardController extends Controller
{
    public function manager()
    {
        $user = Auth::user();
        $divisionId = $user->division_id;

        // Ambil semua requisition di divisi ini (untuk kartu metrik)
        $allRequisitions = \App\Models\Requisition::with(['position', 'requester'])
            ->whereHas('position', fn($q) => $q->where('division_id', $divisionId))
            ->latest()
            ->get();

        $metrics = [
            'pending'       => $allRequisitions->whereIn('status', ['Diajukan', 'submitted'])->count(),
            'approved'      => $allRequisitions->whereIn('status', ['Disetujui', 'approved'])->count(),
            'rejected'      => $allRequisitions->whereIn('status', ['Ditolak', 'rejected'])->count(),
            'new_employees' => \App\Models\Application::where('status', 'accepted')
                ->whereHas('jobPosting.requisition.requester', fn($q) => $q->where('users.division_id', $divisionId))
                ->count(),
        ];

        $requisitions = $allRequisitions->take(5);

        $jobPostings = \App\Models\JobPosting::with(['requisition'])
            ->whereHas('requisition.position', fn($q) => $q->where('division_id', $divisionId))
            ->latest()
            ->get();

        $newEmployees = \App\Models\Application::with(['user', 'jobPosting.requisition.position'])
            ->where('status', 'accepted')
            ->whereHas('jobPosting.requisition.requester', fn($q) => $q->where('users.division_id', $divisionId))
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('manager.dashboard.index', compact('newEmployees', 'metrics', 'requisitions', 'jobPostings'));
    }
}
