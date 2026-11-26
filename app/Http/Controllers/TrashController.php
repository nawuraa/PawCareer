<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TrashController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $trashedRequisitions = Requisition::onlyTrashed()
            ->with(['position.division'])
            ->whereHas('position', fn($q) => $q->where('division_id', $user->division_id))
            ->where('requested_by', $user->id)
            ->latest('deleted_at')
            ->get()
            ->map(function ($req) {
                $deletedAt = $req->deleted_at;
                $expiresAt = $deletedAt->copy()->addDays(30);
                $daysLeft  = max(0, (int) now()->diffInDays($expiresAt, false));
                $pct       = round(($daysLeft / 30) * 100);

                $req->days_left = $daysLeft;
                $req->retention_pct = $pct;
                return $req;
            });

        return view('manager.trash.index', compact('trashedRequisitions'));
    }

    public function restoreRequisition(int $id)
    {
        $user = Auth::user();

        $req = Requisition::onlyTrashed()
            ->where('id', $id)
            ->where('requested_by', $user->id)
            ->whereHas('position', fn($q) => $q->where('division_id', $user->division_id))
            ->firstOrFail();

        $req->restore();

        return redirect()->route('trash.index')->with('success', 'Pengajuan berhasil dipulihkan.');
    }
}
