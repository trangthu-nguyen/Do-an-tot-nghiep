<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Admin;
use App\Models\Service;
use App\Models\Staff;

class PaymentController extends Controller
{
    // Danh sách booking + trạng thái thanh toán của customer
    public function index()
    {
        $bookings = Booking::where('customer_id', session('customer_id'))
            ->with(['bookingDetails', 'payment'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->get();

        return view('customer.payments.index', compact('bookings'));
    }

    // Xem trang thanh toán chi tiết
    public function show($booking_id)
    {
        $booking = Booking::with(['bookingDetails', 'payment'])->findOrFail($booking_id);

        if ($booking->customer_id != session('customer_id')) {
            return redirect()->route('customer.payments.index')
                ->with('error', 'Bạn không có quyền thanh toán lịch hẹn này!');
        }

        if ($booking->status != 3) {
            return redirect()->route('customer.payments.index')
                ->with('error', 'Lịch hẹn chưa hoàn thành nên không thể thanh toán!');
        }

        return view('customer.payments.show', compact('booking'));
    }

    // Xử lý thanh toán sau khi hoàn thành dịch vụ
    public function pay(Request $request, $booking_id)
    {
        $request->validate([
            'payment_method' => 'required'
        ]);

        $booking = Booking::with(['bookingDetails', 'payment'])->findOrFail($booking_id);

        if ($booking->customer_id != session('customer_id')) {
            return redirect()->route('customer.payments.index')
                ->with('error', 'Bạn không có quyền thanh toán lịch hẹn này!');
        }

        if ($booking->status != 3) {
            return redirect()->route('customer.payments.index')
                ->with('error', 'Lịch hẹn chưa hoàn thành nên không thể thanh toán!');
        }

        if ($booking->payment && $booking->payment->payment_status == 'paid') {
            return redirect()->route('customer.payments.index')
                ->with('error', 'Lịch hẹn này đã được thanh toán!');
        }

        if (!$booking->payment) {
            Payment::create([
                'booking_id' => $booking_id,
                'amount' => $booking->total_amount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'paid',
                'payment_date' => now()
            ]);
        } else {
            $booking->payment->update([
                'amount' => $booking->total_amount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'paid',
                'payment_date' => now()
            ]);
        }

        return redirect()->route('customer.payments.index')
            ->with('success', 'Thanh toán thành công!');
    }

    // Đặt lịch kèm thanh toán trực tuyến
    public function init(Request $request)
    {
        $request->validate([
            'service_id' => 'required',
            'booking_date' => 'required',
            'booking_time' => 'required',
            'address' => 'required',
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'payment_method' => 'required'
        ]);

        if (!session('customer_id')) {
            return redirect()->route('customer.login')
                ->with('error', 'Vui lòng đăng nhập để đặt lịch!');
        }

        $service = Service::findOrFail($request->service_id);
        $totalAmount = $service->price;

        // 1) Tạo booking mới
        $booking = Booking::create([
            'customer_id' => session('customer_id'),
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'address' => $request->address,
            'status' => 0,
            'total_amount' => $totalAmount
        ]);

        // 2) Tạo booking detail
        $booking->bookingDetails()->create([
            'service_id' => $request->service_id,
            'price' => $service->price
        ]);

        // 3) Tạo payment đã thanh toán
        Payment::create([
            'booking_id' => $booking->booking_id,
            'amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'payment_status' => 'paid',
            'payment_date' => now()
        ]);

        // 4) Tạo thông báo cho admin
        $admins = Admin::all();

        foreach ($admins as $admin) {
            Notification::create([
                'user_type' => 'admin',
                'user_id' => $admin->admin_id,
                'title' => 'Có lịch đặt mới',
                'content' => 'Khách hàng vừa đặt lịch và thanh toán trực tuyến. Mã lịch: #' . $booking->booking_id,
                'is_read' => 0,
                'created_at' => now()
            ]);
        }
        $staffs = Staff::where('status', 1)->get();

foreach ($staffs as $staff) {
    Notification::create([
        'user_type' => 'staff',
        'user_id' => $staff->staff_id,
        'title' => 'Có lịch đặt mới đang chờ nhận',
        'content' => 'Khách hàng vừa đặt Booking #' . $booking->booking_id .
            ' - Dịch vụ: ' . $service->service_name .
            '. Bạn có thể vào Danh sách lịch đặt để nhận lịch.',
        'is_read' => 0,
        'created_at' => now()
    ]);
}

        // 5) Chuyển về lịch đặt
        return redirect()->route('customer.bookings.index')
            ->with('success', 'Đặt lịch và thanh toán thành công!');
    }
}