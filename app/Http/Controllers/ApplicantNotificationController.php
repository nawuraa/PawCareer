<?php

namespace App\Http\Controllers;

use App\Models\ApplicationNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ApplicantNotificationController extends Controller
{
    public function index()
    {
        $notifications = ApplicationNotification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('applicant.notifications.index', compact('notifications'));
    }

    public function markAsRead(ApplicationNotification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === Auth::id(), 403);

        $notification->update([
            'read_at' => now(),
        ]);

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        ApplicationNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}