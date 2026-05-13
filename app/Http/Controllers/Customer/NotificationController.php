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
        $notification = Notification::findOrFail($id);

        $notification->update([
            'is_read' => 1
        ]);

        return redirect()->back()->with('success', 'Đã đọc thông báo!');
    }
}