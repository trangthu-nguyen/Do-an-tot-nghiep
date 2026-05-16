<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::where('user_type', 'admin')
    ->orderBy('notification_id', 'desc');

        if ($request->filled('read')) {
            $query->where('is_read', $request->read);
        }

        if ($request->filled('type')) {
            if ($request->type == 'booking') {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%booking%')
                      ->orWhere('title', 'like', '%lịch%')
                      ->orWhere('content', 'like', '%booking%')
                      ->orWhere('content', 'like', '%lịch%');
                });
            }

            if ($request->type == 'payment') {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%payment%')
                      ->orWhere('title', 'like', '%thanh toán%')
                      ->orWhere('content', 'like', '%payment%')
                      ->orWhere('content', 'like', '%thanh toán%');
                });
            }

            if ($request->type == 'system') {
                $query->whereNotIn('user_type', ['customer', 'staff']);
            }
        }

        $notifications = $query->get();

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        Notification::where('notification_id', $id)->update([
            'is_read' => 1
        ]);

        return redirect()->back()->with('success', 'Đã đánh dấu thông báo là đã đọc!');
    }

    public function markAllAsRead()
{
    Notification::where('user_type', 'admin')
        ->update([
            'is_read' => 1
        ]);

    return redirect()
        ->route('admin.notifications.index')
        ->with('success', 'Đã đánh dấu tất cả thông báo admin là đã đọc!');
}
}