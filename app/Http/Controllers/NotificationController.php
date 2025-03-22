<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back()->with('success', 'Bildirim okundu olarak işaretlendi.');
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Tüm bildirimler okundu olarak işaretlendi.');
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'Bildirim silindi.');
    }

    public function getUnreadCount()
    {
        $count = auth()->user()->notifications()
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
