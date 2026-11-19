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
        
        $newEmployees = Application::with(['user', 'jobPosting.requisition.position'])
            ->where('status', 'accepted')
            ->whereHas('jobPosting.requisition.requester', function ($query) use ($user) {
                $query->where('users.division_id', $user->division_id);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('manager.dashboard.index', compact('newEmployees'));
    }
}
