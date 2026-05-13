<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
{
    $notifications = Notification::where('user_type', 'admin')
        ->where('user_id', session('admin_id'))
        ->orderBy('notification_id', 'desc')
        ->get();

    return view('admin.notifications.index', compact('notifications'));
}

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => 1]);

        return redirect()->back()->with('success', 'Đã đánh dấu thông báo là đã đọc!');
    }
}