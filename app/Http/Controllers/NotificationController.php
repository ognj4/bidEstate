<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->latest('created_at')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request, \App\Models\Notification $notification)
    {
        abort_if($notification->user_id !== $request->user()->id, 403);

        $notification->markAsRead();

        return back();
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()
            ->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Sve notifikacije oznacene kao procitane.');
    }
}
