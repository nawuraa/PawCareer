<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function manager()
    {
        $user = Auth::user();
        
        $newEmployees = Application::with(['user', 'jobPosting.requisition.position'])
            ->where('status', 'accepted')
            ->whereHas('jobPosting.requisition', function ($query) use ($user) {
                $query->where('division_id', $user->division_id);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('dashboard.manager', compact('newEmployees'));
    }
}
