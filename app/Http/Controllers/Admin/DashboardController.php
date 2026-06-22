<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Feedback;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalBookings = Booking::count();
        $todayBookings = Booking::whereDate('booking_date', today())->count();
        $totalServices = Service::count();
        $totalCustomers = Customer::count();
        $totalStaff = Staff::count();

        $todayRevenue = Payment::where('payment_status', 'paid')
            ->whereDate('payment_date', today())
            ->sum('amount');

        $monthRevenue = Payment::where('payment_status', 'paid')
            ->whereYear('payment_date', now()->year)
            ->whereMonth('payment_date', now()->month)
            ->sum('amount');

        $pendingPaymentCount = Payment::where('payment_status', 'pending')->count();
        $pendingBookingCount = Booking::where('status', 0)->count();

        $totalRevenue = $todayRevenue;

        $revenueFilter = $request->get('revenue_filter', 'month');

        if ($revenueFilter == 'week') {
            $rawRevenue = Payment::selectRaw('DATE(payment_date) as day, SUM(amount) as total')
                ->where('payment_status', 'paid')
                ->whereBetween('payment_date', [now()->startOfWeek(), now()->endOfWeek()])
                ->groupBy('day')
                ->orderBy('day')
                ->pluck('total', 'day')
                ->toArray();

            $months = [];
            $revenues = [];

            for ($date = now()->startOfWeek()->copy(); $date <= now()->endOfWeek(); $date->addDay()) {
                $key = $date->format('Y-m-d');
                $months[] = $date->format('d/m');
                $revenues[] = $rawRevenue[$key] ?? 0;
            }
        } else {
            $rawRevenue = Payment::selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
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
                $revenues[] = $rawRevenue[$i] ?? 0;
            }
        }

        $topStaff = Staff::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->first();

        $topStaffName = $topStaff->full_name ?? 'Chưa có dữ liệu';
        $topStaffBookings = $topStaff->bookings_count ?? 0;
        $topStaffId = $topStaff->staff_id ?? null;

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
            ? $femalePortraits[($topStaff->staff_id - 1) % count($femalePortraits)]
            : asset('images/default-avatar.png');

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

        $bookingStatus = [
            'Chờ xác nhận' => Booking::where('status', 0)->count(),
            'Đã xác nhận' => Booking::where('status', 1)->count(),
            'Đang thực hiện' => Booking::where('status', 2)->count(),
            'Hoàn thành' => Booking::where('status', 3)->count(),
            'Đã hủy' => Booking::where('status', 4)->count(),
        ];

        $topServices = Service::withCount('bookingDetails')
            ->withSum('bookingDetails as service_revenue', 'price')
            ->orderByDesc('booking_details_count')
            ->limit(5)
            ->get();

        $maxServiceBookings = $topServices->max('booking_details_count') ?? 0;

        return view('admin.dashboard', compact(
            'totalBookings',
            'totalServices',
            'totalCustomers',
            'totalStaff',
            'totalRevenue',
            'todayRevenue',
            'monthRevenue',
            'pendingPaymentCount',
            'pendingBookingCount',
            'revenueFilter',
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
            'topServices',
            'maxServiceBookings',
            'todayBookings',
        ));
    }
}