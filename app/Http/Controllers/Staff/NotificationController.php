<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $staffId = session('staff_id');

        $notifications = Notification::where('user_type', 'staff')
            ->where('user_id', $staffId)
            ->orderBy('notification_id', 'desc')
            ->get();

        return view('staff.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('notification_id', $id)
            ->where('user_type', 'staff')
            ->where('user_id', session('staff_id'))
            ->firstOrFail();

        $notification->update([
            'is_read' => 1
        ]);

        return redirect()->back()->with('success', 'Đã đọc thông báo!');
    }

    public function markAllAsRead()
    {
        Notification::where('user_type', 'staff')
            ->where('user_id', session('staff_id'))
            ->where('is_read', 0)
            ->update([
                'is_read' => 1
            ]);

        return redirect()->back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc!');
    }
}