<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function index()
    {
        $countBooking = Booking::count();
        $countService = Service::count();
        $countCustomer = Customer::count();

        // Doanh thu theo tháng trong năm nay
        $revenueByMonth = Booking::selectRaw('MONTH(booking_date) as month, SUM(total_amount) as total')
            ->where('status', 3)
            ->whereYear('booking_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')->toArray();

        $months = [];
        $revenues = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = "Tháng $i";
            $revenues[] = $revenueByMonth[$i] ?? 0;
        }

        // Doanh thu theo nhân viên
        $revenueByStaff = Booking::selectRaw('staff_id, SUM(total_amount) as total')
            ->where('status', 3)
            ->whereNotNull('staff_id')
            ->groupBy('staff_id')
            ->with('staff')
            ->get();
        
        $staffNames = [];
        $staffRevenues = [];
        foreach ($revenueByStaff as $rb) {
            $staffNames[] = $rb->staff->full_name ?? 'Không rõ';
            $staffRevenues[] = $rb->total;
        }

        return view('admin.dashboard', compact(
            'countBooking', 'countService', 'countCustomer',
            'months', 'revenues', 'staffNames', 'staffRevenues'
        ));
    }
}