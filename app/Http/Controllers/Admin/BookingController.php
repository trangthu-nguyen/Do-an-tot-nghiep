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

    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'bookingDetails.service', 'staff', 'payment'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc');

        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->where('booking_id', 'like', "%{$keyword}%")
                    ->orWhereHas('customer', function ($cus) use ($keyword) {
                        $cus->where('full_name', 'like', "%{$keyword}%")
                            ->orWhere('phone', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('staff', function ($staff) use ($keyword) {
                        $staff->where('full_name', 'like', "%{$keyword}%")
                            ->orWhere('phone', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('bookingDetails.service', function ($service) use ($keyword) {
                        $service->where('service_name', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('booking_date')) {
            $query->whereDate('booking_date', $request->booking_date);
        }

        $bookings = $query->get();

        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 0)->count();

        $todayBookings = Booking::whereDate('booking_date', today())
            ->where('status', '!=', 4)
            ->count();

        $cancelledBookings = Booking::where('status', 4)->count();
        $totalRevenue = Booking::where('status', 3)->sum('total_amount');

        $staffs = Staff::limit(4)->get();

        return view('admin.bookings.index', compact(
            'bookings',
            'totalBookings',
            'pendingBookings',
            'todayBookings',
            'cancelledBookings',
            'totalRevenue',
            'staffs'
        ));
    }

    public function show($id)
    {
        $booking = Booking::with([
            'customer',
            'bookingDetails.service',
            'staff',
            'payment'
        ])->findOrFail($id);

        $bookingDate = $booking->booking_date;
        $bookingTime = $booking->booking_time;

        $staffs = Staff::where('status', 1)
            ->whereIn('staff_id', function ($query) use ($bookingDate, $bookingTime) {
                $query->select('staff_id')
                    ->from('staff_schedules')
                    ->whereDate('work_date', $bookingDate)
                    ->whereIn('status', ['available', 'approved'])
                    ->whereTime('start_time', '<=', $bookingTime)
                    ->whereTime('end_time', '>=', $bookingTime);
            })
            ->whereDoesntHave('bookings', function ($q) use ($bookingDate, $bookingTime, $booking) {
                $q->whereDate('booking_date', $bookingDate)
                    ->where('booking_time', $bookingTime)
                    ->where('booking_id', '!=', $booking->booking_id)
                    ->whereIn('status', [0, 1, 2]);
            })
            ->get();

        return view('admin.bookings.show', compact('booking', 'staffs'));
    }

    public function assignStaff(Request $request, $id)
    {
        $request->validate([
            'staff_id' => 'required'
        ]);

        $booking = Booking::with([
            'customer',
            'bookingDetails.service'
        ])->findOrFail($id);

        $staffBusy = Booking::where('staff_id', $request->staff_id)
            ->whereDate('booking_date', $booking->booking_date)
            ->where('booking_time', $booking->booking_time)
            ->where('booking_id', '!=', $booking->booking_id)
            ->whereIn('status', [0, 1, 2])
            ->exists();

        if ($staffBusy) {
            return redirect()
                ->back()
                ->with('error', 'Nhân viên này đã có lịch trong khung giờ này!');
        }

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

        return redirect()
            ->back()
            ->with('success', 'Phân công nhân viên thành công!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|min:0|max:4'
        ]);

        $booking = Booking::findOrFail($id);

        $booking->update([
            'status' => (int) $request->status
        ]);

        $statusText = match ((int) $booking->status) {
            0 => 'Chờ xác nhận',
            1 => 'Đã xác nhận',
            2 => 'Đang thực hiện',
            3 => 'Hoàn thành',
            4 => 'Đã hủy',
            default => 'Đã cập nhật trạng thái',
        };

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