<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $customerId = session('customer_id');

        $notifications = Notification::where('user_type', 'customer')
            ->where('user_id', $customerId)
            ->orderBy('notification_id', 'desc')
            ->get();

        return view('customer.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('notification_id', $id)
            ->where('user_type', 'customer')
            ->where('user_id', session('customer_id'))
            ->firstOrFail();

        $notification->update([
            'is_read' => 1
        ]);

        return redirect()->back()->with('success', 'Đã đọc thông báo!');
    }

    public function markAllAsRead()
    {
        Notification::where('user_type', 'customer')
            ->where('user_id', session('customer_id'))
            ->where('is_read', 0)
            ->update([
                'is_read' => 1
            ]);

        return redirect()->back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc!');
    }
}