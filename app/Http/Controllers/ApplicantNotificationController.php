<?php

namespace App\Http\Controllers;

use App\Models\ApplicationNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ApplicantNotificationController extends Controller
{
    public function index(): View
    {
        $notifications = ApplicationNotification::query()
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('applicant.notifications.index', compact('notifications'));
    }

    public function markAsRead(ApplicationNotification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === Auth::id(), 403);

        if (is_null($notification->read_at)) {
            $notification->update(['read_at' => now()]);
        }

        return redirect()->route('applicant.notifications.index');
    }

    public function markAllAsRead(): RedirectResponse
    {
        ApplicationNotification::query()
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()->route('applicant.notifications.index')->with('success', 'All notifications marked as read.');
    }
}