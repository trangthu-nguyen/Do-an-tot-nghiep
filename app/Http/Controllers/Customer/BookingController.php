<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Notification;
use App\Models\Admin;
use App\Models\Payment;
use App\Models\Staff;

use App\Http\Requests\StoreBookingRequest;
use App\Services\BookingService;

use Exception;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    // ================== LỊCH ĐẶT CỦA KHÁCH ==================
    public function index()
    {
        $bookings = Booking::where('customer_id', session('customer_id'))
            ->with(['bookingDetails.service', 'feedback', 'payment', 'staff'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->get();

        return view('customer.bookings.index', compact('bookings'));
    }

    // ================== CHI TIẾT BOOKING ==================
    public function show($id)
    {
        $booking = Booking::where('booking_id', $id)
            ->where('customer_id', session('customer_id'))
            ->with(['bookingDetails.service', 'feedback', 'payment', 'staff'])
            ->firstOrFail();

        return view('customer.bookings.show', compact('booking'));
    }

    // ================== FORM CREATE BOOKING ==================
    public function create(Request $request, $service_id)
    {
        $service = Service::where('service_id', $service_id)->firstOrFail();

        $booking_date = $request->query('booking_date');
        $booking_time = $request->query('booking_time');

        return view('customer.bookings.create', compact(
            'service',
            'booking_date',
            'booking_time'
        ));
    }

    // ================== API SLOT TRỐNG ==================
    public function availableSlots(Request $request)
    {
        $date = $request->query('date');
        $service_id = $request->query('service_id');

        if (!$date || !$service_id) {
            return response()->json(['slots' => []]);
        }

        $slots = $this->bookingService->getAvailableSlots($date, $service_id);

        return response()->json(['slots' => $slots]);
    }

    // ================== LƯU BOOKING ==================
    public function store(StoreBookingRequest $request)
    {
        try {
            $data = $request->validated();

            if (!session('customer_id')) {
                return redirect()->route('customer.login')
                    ->with('error', 'Bạn cần đăng nhập để đặt lịch!');
            }

            $service = Service::findOrFail($data['service_id']);
            $totalAmount = $service->price;

            // ================== TẠO BOOKING ==================
            $booking = Booking::create([
                'customer_id'   => session('customer_id'),
                'booking_date'  => $data['booking_date'],
                'booking_time'  => $data['booking_time'],
                'address'       => $data['address'],
                'status'        => 0, // CHỜ XÁC NHẬN
                'total_amount'  => $totalAmount
            ]);

            // ================== BOOKING DETAIL ==================
            $booking->bookingDetails()->create([
                'service_id' => $data['service_id'],
                'quantity'   => 1,
                'price'      => $service->price
            ]);

            // ================== PAYMENT ==================
            $paymentMethod = $data['payment_method'] ?? 'cod';
            $isOnline = in_array($paymentMethod, ['momo', 'vnpay', 'bank']);

            Payment::create([
                'booking_id'      => $booking->booking_id,
                'amount'          => $totalAmount,
                'payment_method'  => $paymentMethod,
                'payment_status'  => $isOnline ? 'paid' : 'pending',
                'payment_date'    => $isOnline ? now() : null,
                'transaction_id'  => $isOnline ? strtoupper($paymentMethod) . '-' . time() : null,
            ]);

            // ================== THÔNG BÁO CHO ADMIN ==================
            $admins = Admin::all();

            foreach ($admins as $admin) {
                Notification::create([
                    'user_type'  => 'admin',
                    'user_id'    => $admin->admin_id,
                    'title'      => 'Có lịch đặt mới',
                    'content'    => 'Khách hàng vừa đặt lịch #' . $booking->booking_id,
                    'is_read'    => 0,
                    'created_at' => now()
                ]);
            }

            // ================== THÔNG BÁO CHO TẤT CẢ NHÂN VIÊN ==================
            $staffs = Staff::all();

            foreach ($staffs as $staff) {
                Notification::create([
                    'user_type'  => 'staff',
                    'user_id'    => $staff->staff_id,
                    'title'      => 'Có lịch đặt mới đang chờ nhận',
                    'content'    => 'Khách hàng vừa đặt Booking #' . $booking->booking_id .
                                    ' - Dịch vụ: ' . $service->service_name .
                                    '. Bạn có thể vào Danh sách lịch đặt để nhận lịch.',
                    'is_read'    => 0,
                    'created_at' => now()
                ]);
            }

            // ================== THÔNG BÁO CHO CUSTOMER ==================
            Notification::create([
                'user_type'  => 'customer',
                'user_id'    => session('customer_id'),
                'title'      => 'Đặt lịch thành công',
                'content'    => 'Bạn đã đặt lịch #' . $booking->booking_id . ' thành công. Vui lòng chờ admin xác nhận.',
                'is_read'    => 0,
                'created_at' => now()
            ]);

            return redirect()
                ->route('customer.bookings.index')
                ->with('success', '🎉 Đặt lịch thành công!');

        } catch (Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // ================== HỦY BOOKING ==================
    public function cancel($id)
    {
        $booking = Booking::with('bookingDetails.service')->findOrFail($id);

        try {
            $this->bookingService->cancelBookingByCustomer($booking, session('customer_id'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        // ================== THÔNG BÁO CHO CUSTOMER ==================
        Notification::create([
            'user_type'  => 'customer',
            'user_id'    => session('customer_id'),
            'title'      => 'Hủy lịch thành công',
            'content'    => 'Bạn đã hủy lịch #' . $booking->booking_id . ' thành công.',
            'is_read'    => 0,
            'created_at' => now()
        ]);

        // ================== THÔNG BÁO CHO STAFF NẾU LỊCH ĐÃ ĐƯỢC PHÂN CÔNG ==================
        if ($booking->staff_id) {
            Notification::create([
                'user_type'  => 'staff',
                'user_id'    => $booking->staff_id,
                'title'      => 'Khách hàng đã hủy lịch',
                'content'    => 'Booking #' . $booking->booking_id . ' đã được khách hàng hủy.',
                'is_read'    => 0,
                'created_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Hủy lịch thành công!');
    }
}