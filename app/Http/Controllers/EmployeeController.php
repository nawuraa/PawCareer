<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $newEmployees = Application::with([
            'applicant.applicantProfile',
            'jobPosting.requisition.position.division',
            'hiringDecision',
        ])
            ->where('status', 'accepted')
            ->whereHas('jobPosting.requisition.position', function ($query) use ($user) {
                $query->where('division_id', $user->division_id);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('manager.employees.index', compact('newEmployees'));
    }
}