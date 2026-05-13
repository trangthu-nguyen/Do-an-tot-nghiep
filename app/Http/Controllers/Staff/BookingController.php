<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Notification;
use App\Services\BookingService;
use App\Models\StaffSchedule;
use Carbon\Carbon;
use Exception;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }
    public function index(Request $request)
{
    $staffId = session('staff_id');

    $filter = $request->query('filter', 'day');

    $query = Booking::with(['customer','bookingDetails.service'])
        ->where('staff_id', $staffId);

    if ($filter == 'day') {
        $query->whereDate('booking_date', now()->toDateString());
    } elseif ($filter == 'week') {
        $query->whereBetween('booking_date', [
            now()->startOfWeek()->toDateString(),
            now()->endOfWeek()->toDateString()
        ]);
    } elseif ($filter == 'month') {
        $query->whereYear('booking_date', now()->year)
              ->whereMonth('booking_date', now()->month);
    }

    $bookings = $query
        ->orderBy('booking_date', 'asc')
        ->orderBy('booking_time', 'asc')
        ->get();

    return view('staff.bookings.index', compact('bookings'));
}

    public function show($id)
    {
        $booking = Booking::with(['customer', 'bookingDetails.service'])->findOrFail($id);

        // staff chỉ xem booking của mình
        if ($booking->staff_id != session('staff_id')) {
            return redirect()->route('staff.bookings.index')->with('error', 'Bạn không có quyền xem booking này!');
        }

        return view('staff.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $booking = Booking::findOrFail($id);

        if ($booking->staff_id != session('staff_id')) {
            return redirect()->back()->with('error', 'Bạn không có quyền cập nhật booking này!');
        }

        $booking->update([
            'status' => $request->status
        ]);

        // 🔔 Thông báo cho customer khi staff cập nhật trạng thái
        Notification::create([
            'user_type' => 'customer',
            'user_id' => $booking->customer_id,
            'title' => 'Cập nhật trạng thái booking',
            'content' => 'Booking ID ' . $booking->booking_id . ' đã được cập nhật trạng thái.',
            'is_read' => 0,
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function jobMarket()
    {
        $bookings = Booking::whereNull('staff_id')
            ->where('status', 0)
            ->with(['customer', 'bookingDetails.service'])
            ->orderBy('booking_date', 'asc')
            ->orderBy('booking_time', 'asc')
            ->get();

        return view('staff.bookings.job_market', compact('bookings'));
    }

    public function acceptBooking($id)
{
    $booking = Booking::findOrFail($id);

    try {
        $this->bookingService->acceptBookingByStaff($booking, session('staff_id'));
    } catch (Exception $e) {
        return redirect()->route('staff.jobMarket')->with('error', $e->getMessage());
    }

    Notification::create([
        'user_type' => 'customer',
        'user_id' => $booking->customer_id,
        'title' => 'Nhân viên đã nhận lịch',
        'content' => 'Booking #' . $booking->booking_id . ' đã có nhân viên nhận và đang chờ thực hiện.',
        'is_read' => 0,
        'created_at' => now()
    ]);

    return redirect()->route('staff.bookings.index')->with('success', 'Bạn đã nhận lịch thành công!');
    }
    public function workHistory()
{
    $staffId = session('staff_id');

    $bookings = Booking::with(['customer', 'bookingDetails.service', 'payment'])
        ->where('staff_id', $staffId)
        ->whereIn('status', [0, 1, 2, 3, 4])
        ->orderBy('booking_date', 'desc')
        ->orderBy('booking_time', 'desc')
        ->get();

    return view('staff.bookings.work_history', compact('bookings'));
}
public function scheduleRegistration()
{
    $staffId = session('staff_id');

    $schedules = StaffSchedule::where('staff_id', $staffId)
        ->orderBy('work_date', 'asc')
        ->get();

    return view('staff.bookings.schedule_registration', compact('schedules'));
}

public function storeSchedule(Request $request)
{
    $request->validate([
        'work_date' => 'required|date',
        'shift_name' => 'required',
        'start_time' => 'required',
        'end_time' => 'required',
    ]);

    StaffSchedule::updateOrCreate(
        [
            'staff_id' => session('staff_id'),
            'work_date' => $request->work_date,
        ],
        [
            'shift_name' => $request->shift_name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'available',
            'note' => $request->note,
            'updated_at' => now()
        ]
    );

    return redirect()->back()->with('success', 'Đăng ký lịch làm thành công!');
}

public function markBusy(Request $request)
{
    $request->validate([
        'work_date' => 'required|date',
    ]);

    StaffSchedule::updateOrCreate(
        [
            'staff_id' => session('staff_id'),
            'work_date' => $request->work_date,
        ],
        [
            'shift_name' => 'Nghỉ / Bận',
            'start_time' => null,
            'end_time' => null,
            'status' => 'busy',
            'note' => $request->note ?? 'Nhân viên xin nghỉ hoặc bận ngày này.',
            'updated_at' => now()
        ]
    );

    return redirect()->back()->with('success', 'Đã đánh dấu bận / xin nghỉ thành công!');
}
}