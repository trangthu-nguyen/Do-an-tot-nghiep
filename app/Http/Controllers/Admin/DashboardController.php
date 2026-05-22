<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Feedback;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::count();
        $totalServices = Service::count();
        $totalCustomers = Customer::count();
        $totalStaff = Staff::count();

        // Tổng doanh thu từ các lịch đã hoàn thành
        $totalRevenue = Payment::where('payment_status', 'paid')
            ->sum('amount');

        // Nhân viên có nhiều lịch hẹn nhất
        $topStaff = Staff::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->first();

        $topStaffName = $topStaff->full_name ?? 'Chưa có dữ liệu';
        $topStaffBookings = $topStaff->bookings_count ?? 0;
        $topStaffId = $topStaff->staff_id ?? null;

        // Ảnh nhân viên, khớp với cột image trong bảng staffs
        $femalePortraits = [
    'https://randomuser.me/api/portraits/women/44.jpg',
    'https://randomuser.me/api/portraits/women/65.jpg',
    'https://randomuser.me/api/portraits/women/68.jpg',
    'https://randomuser.me/api/portraits/women/71.jpg',
    'https://randomuser.me/api/portraits/women/72.jpg',
    'https://randomuser.me/api/portraits/women/76.jpg',
    'https://randomuser.me/api/portraits/women/79.jpg',
    'https://randomuser.me/api/portraits/women/81.jpg'
];

$topStaffImage = $topStaff
    ? $femalePortraits[$topStaff->staff_id % count($femalePortraits)]
    : asset('images/default-avatar.png');

        // Đánh giá auto 5 sao theo yêu cầu
        $topStaffRating = '5.0';
        $topStaffStars = '★★★★★';

        $totalFeedbacks = Feedback::count();

        $recentBookings = Booking::with([
                'customer',
                'staff',
                'bookingDetails.service',
                'payment'
            ])
            ->orderBy('booking_id', 'desc')
            ->limit(5)
            ->get();

        // Doanh thu theo tháng trong năm hiện tại
        $revenueByMonth = Payment::selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
    ->where('payment_status', 'paid')
    ->whereYear('payment_date', date('Y'))
    ->groupBy('month')
    ->orderBy('month')
    ->pluck('total', 'month')
    ->toArray();

        $months = [];
        $revenues = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = "Tháng $i";
            $revenues[] = $revenueByMonth[$i] ?? 0;
        }

        $bookingStatus = [
            'Chờ xác nhận' => Booking::where('status', 0)->count(),
            'Đã xác nhận' => Booking::where('status', 1)->count(),
            'Đang thực hiện' => Booking::where('status', 2)->count(),
            'Hoàn thành' => Booking::where('status', 3)->count(),
            'Đã hủy' => Booking::where('status', 4)->count(),
        ];

        return view('admin.dashboard', compact(
            'totalBookings',
            'totalServices',
            'totalCustomers',
            'totalStaff',
            'totalRevenue',
            'topStaffName',
            'topStaffBookings',
            'topStaffImage',
            'topStaffRating',
            'topStaffStars',
            'totalFeedbacks',
            'recentBookings',
            'months',
            'revenues',
            'bookingStatus',
            'topStaffId',
        ));
    }
}