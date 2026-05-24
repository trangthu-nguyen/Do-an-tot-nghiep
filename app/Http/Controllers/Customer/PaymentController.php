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
    public function index()
    {
        $bookings = Booking::where('customer_id', session('customer_id'))
            ->with(['bookingDetails', 'payment'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->get();

        return view('customer.payments.index', compact('bookings'));
    }

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

        $booking = Booking::create([
            'customer_id' => session('customer_id'),
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'address' => $request->address,
            'status' => 0,
            'total_amount' => $totalAmount
        ]);

        $booking->bookingDetails()->create([
            'service_id' => $request->service_id,
            'price' => $service->price
        ]);

        Payment::create([
            'booking_id' => $booking->booking_id,
            'amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'payment_date' => now()
        ]);

        foreach (Admin::all() as $admin) {
            Notification::create([
                'user_type' => 'admin',
                'user_id' => $admin->admin_id,
                'title' => 'Có lịch đặt mới',
                'content' => 'Khách hàng vừa đặt lịch và chọn thanh toán trực tuyến. Mã lịch: #' . $booking->booking_id,
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

        return redirect()->route('customer.payments.wait', $booking->booking_id)
            ->with('success', 'Đặt lịch thành công, vui lòng hoàn tất thanh toán!');
    }

    public function wait($booking_id)
    {
        $booking = Booking::with(['bookingDetails.service', 'payment'])
            ->where('booking_id', $booking_id)
            ->where('customer_id', session('customer_id'))
            ->firstOrFail();

        return view('customer.payments.wait', compact('booking'));
    }
}