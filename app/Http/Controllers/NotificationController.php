<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = UserNotification::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(UserNotification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        return redirect()->back();
    }

    public function markAllRead()
    {
        UserNotification::where('user_id', auth()->id())
            ->unread()
            ->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Get unread count (for AJAX polling if needed)
     */
    public function unreadCount()
    {
        $count = UserNotification::where('user_id', auth()->id())->unread()->count();
        return response()->json(['count' => $count]);
    }
}
