<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Staff;
use App\Models\StaffSchedule;
use App\Services\BookingService;
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

        $query = Booking::with(['customer', 'bookingDetails.service'])
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
        $booking = Booking::with([
            'customer',
            'bookingDetails.service',
            'payment',
            'staff'
        ])->findOrFail($id);

        if ($booking->staff_id != session('staff_id')) {
            return redirect()
                ->route('staff.bookings.index')
                ->with('error', 'Bạn không có quyền xem booking này!');
        }

        return view('staff.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|min:0|max:4'
        ]);

        $booking = Booking::with(['customer', 'staff'])->findOrFail($id);

        if ($booking->staff_id != session('staff_id')) {
            return redirect()
                ->back()
                ->with('error', 'Bạn không có quyền cập nhật booking này!');
        }

        $booking->update([
            'status' => (int) $request->status
        ]);

        $statusText = $this->getStatusText((int) $request->status);
        $staff = Staff::find(session('staff_id'));

        Notification::create([
            'user_type'  => 'customer',
            'user_id'    => $booking->customer_id,
            'title'      => 'Cập nhật trạng thái lịch đặt',
            'content'    => 'Booking #' . $booking->booking_id . ' đã được cập nhật sang trạng thái: ' . $statusText . '.',
            'is_read'    => 0,
            'created_at' => now()
        ]);

        Notification::create([
            'user_type'  => 'admin',
            'user_id'    => 1,
            'title'      => 'Nhân viên cập nhật trạng thái lịch',
            'content'    => ($staff->full_name ?? 'Nhân viên') . ' đã cập nhật Booking #' . $booking->booking_id . ' sang trạng thái: ' . $statusText . '.',
            'is_read'    => 0,
            'created_at' => now()
        ]);

        return redirect()
            ->back()
            ->with('success', 'Cập nhật trạng thái thành công!');
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
        $booking = Booking::with(['customer'])->findOrFail($id);
        $staff = Staff::find(session('staff_id'));

        try {
            $this->bookingService->acceptBookingByStaff($booking, session('staff_id'));
        } catch (Exception $e) {
            return redirect()
                ->route('staff.jobMarket')
                ->with('error', $e->getMessage());
        }

        Notification::create([
            'user_type'  => 'customer',
            'user_id'    => $booking->customer_id,
            'title'      => 'Nhân viên đã nhận lịch',
            'content'    => 'Booking #' . $booking->booking_id . ' đã có nhân viên nhận và đang chờ thực hiện.',
            'is_read'    => 0,
            'created_at' => now()
        ]);

        Notification::create([
            'user_type'  => 'admin',
            'user_id'    => 1,
            'title'      => 'Nhân viên đã nhận lịch',
            'content'    => ($staff->full_name ?? 'Nhân viên') . ' đã nhận Booking #' . $booking->booking_id . '.',
            'is_read'    => 0,
            'created_at' => now()
        ]);

        return redirect()
            ->route('staff.bookings.index')
            ->with('success', 'Bạn đã nhận lịch thành công!');
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
        'work_date'  => 'required|date',
        'shift_name' => 'required',
        'start_time' => 'required',
        'end_time'   => 'required',
    ]);

    $staff = Staff::find(session('staff_id'));

    StaffSchedule::updateOrCreate(
        [
            'staff_id'   => session('staff_id'),
            'work_date'  => $request->work_date,
            'shift_name' => $request->shift_name,
        ],
        [
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'status'     => 'available',
            'note'       => $request->note,
            'updated_at' => now()
        ]
    );

    Notification::create([
        'user_type'  => 'admin',
        'user_id'    => 1,
        'title'      => 'Nhân viên đăng ký lịch làm',
        'content'    => ($staff->full_name ?? 'Nhân viên') .
                        ' đã đăng ký ' . $request->shift_name .
                        ' ngày ' . \Carbon\Carbon::parse($request->work_date)->format('d/m/Y') .
                        ' (' . $request->start_time . ' - ' . $request->end_time . ').',
        'is_read'    => 0,
        'created_at' => now()
    ]);

    return redirect()
        ->back()
        ->with('success', 'Đăng ký ca làm thành công!');
}
    public function markBusy(Request $request)
    {
        $request->validate([
            'work_date' => 'required|date',
        ]);

        StaffSchedule::updateOrCreate(
            [
                'staff_id'  => session('staff_id'),
                'work_date' => $request->work_date,
            ],
            [
                'shift_name' => 'Nghỉ / Bận',
                'start_time' => null,
                'end_time'   => null,
                'status'     => 'busy',
                'note'       => $request->note ?? 'Nhân viên xin nghỉ hoặc bận ngày này.',
                'updated_at' => now()
            ]
        );

        return redirect()
            ->back()
            ->with('success', 'Đã đánh dấu bận / xin nghỉ thành công!');
    }

    private function getStatusText($status)
    {
        return match ((int) $status) {
            0 => 'Chờ xác nhận',
            1 => 'Đã xác nhận',
            2 => 'Đang thực hiện',
            3 => 'Hoàn thành',
            4 => 'Đã hủy',
            default => 'Không rõ',
        };
    }
}