<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;

class EmployeeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Mengambil data pelamar yang diterima di divisi manajer ini
        $newEmployees = Application::with(['user', 'jobPosting.requisition.position.division'])
            ->where('status', 'accepted')
            ->whereHas('jobPosting.requisition.requester', function ($query) use ($user) {
                $query->where('users.division_id', $user->division_id);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        // Mengarahkan ke file view di dalam folder manager/employees/
        return view('manager.employees.index', compact('newEmployees'));
    }
}