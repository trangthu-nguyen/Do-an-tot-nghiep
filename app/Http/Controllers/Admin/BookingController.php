<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Staff;
use App\Models\Notification;
use App\Services\BookingService;


class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        $bookings = Booking::with(['customer','bookingDetails.service','staff','payment'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->get();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['customer','bookingDetails.service','staff','payment'])->findOrFail($id);
        $staffs = Staff::all();

        return view('admin.bookings.show', compact('booking', 'staffs'));
    }

    // ================== PHÂN CÔNG NHÂN VIÊN ==================
    public function assignStaff(Request $request, $id)
{
    $request->validate([
        'staff_id' => 'required'
    ]);

    $booking = Booking::with(['customer', 'bookingDetails.service'])->findOrFail($id);

    $booking->update([
        'staff_id' => $request->staff_id,
        'status' => $booking->status == 0 ? 1 : $booking->status
    ]);

    Notification::create([
        'user_type'  => 'staff',
        'user_id'    => $request->staff_id,
        'title'      => 'Bạn được phân công lịch mới',
        'content'    => 'Admin đã phân công bạn phụ trách Booking #' . $booking->booking_id . '.',
        'is_read'    => 0,
        'created_at' => now()
    ]);

    Notification::create([
        'user_type'  => 'customer',
        'user_id'    => $booking->customer_id,
        'title'      => 'Lịch đặt đã được phân công nhân viên',
        'content'    => 'Booking #' . $booking->booking_id . ' của bạn đã có nhân viên phụ trách.',
        'is_read'    => 0,
        'created_at' => now()
    ]);

    return redirect()->back()->with('success', 'Phân công nhân viên thành công!');
}

    // ================== CẬP NHẬT TRẠNG THÁI ==================
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|min:0|max:4'
        ]);

        $booking = Booking::findOrFail($id);

        $booking->update([
            'status' => (int)$request->status
        ]);

        // MESSAGE STATUS
        $statusText = "Đã cập nhật trạng thái";

        if ($booking->status == 0) $statusText = "Chờ xác nhận";
        if ($booking->status == 1) $statusText = "Đã xác nhận";
        if ($booking->status == 2) $statusText = "Đang thực hiện";
        if ($booking->status == 3) $statusText = "Hoàn thành";
        if ($booking->status == 4) $statusText = "Đã hủy";

        // THÔNG BÁO CHO CUSTOMER
        Notification::create([
            'user_type'  => 'customer',
            'user_id'    => $booking->customer_id,
            'title'      => 'Cập nhật lịch đặt',
            'content'    => 'Lịch #' . $booking->booking_id . ' đã được cập nhật trạng thái: ' . $statusText,
            'is_read'    => 0,
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}