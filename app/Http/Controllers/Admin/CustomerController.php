<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month;
        $quarter = $request->quarter;

        $query = Customer::withCount('bookings')
            ->with(['bookings' => function ($q) {
                $q->with(['bookingDetails.service', 'payment'])
                    ->orderBy('booking_date', 'desc')
                    ->orderBy('booking_time', 'desc');
            }]);

        // tìm kiếm
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%")
                    ->orWhere('phone', 'like', "%$keyword%");
            });
        }

        $customers = $query->orderBy('customer_id', 'desc')->get();

        foreach ($customers as $customer) {

            // lấy bookings theo khoảng thời gian lọc
            $filteredBookings = $customer->bookings;

            // lọc theo tháng
            if ($month) {
                $filteredBookings = $filteredBookings->filter(function ($booking) use ($month) {
                    return date('n', strtotime($booking->booking_date)) == $month;
                });
            }

            // lọc theo quý
            if ($quarter) {
                $startMonth = ($quarter - 1) * 3 + 1;
                $endMonth = $startMonth + 2;

                $filteredBookings = $filteredBookings->filter(function ($booking) use ($startMonth, $endMonth) {
                    $m = date('n', strtotime($booking->booking_date));
                    return $m >= $startMonth && $m <= $endMonth;
                });
            }

            // tổng tiền đã thanh toán trong khoảng thời gian lọc
            $paidTotal = $filteredBookings->sum(function ($booking) {
                return optional($booking->payment)->payment_status == 'paid'
                    ? (float) optional($booking->payment)->amount
                    : 0;
            });

            // số booking hoàn thành trong khoảng thời gian lọc
            $completedCount = $filteredBookings->where('status', 3)->count();

            // gán lại dữ liệu động theo filter
            $customer->paid_total = $paidTotal;
            $customer->completed_count = $completedCount;

            // phân loại khách hàng
            if ($paidTotal >= 5000000 || $completedCount >= 5) {
                $customer->rank_label = 'VIP';
            } elseif ($paidTotal >= 2000000 || $completedCount >= 3) {
                $customer->rank_label = 'Khách hàng thân thiết';
            } else {
                $customer->rank_label = 'Khách hàng thường';
            }
        }

        // sort
        if ($request->get('sort') == 'spent_desc') {
            $customers = $customers->sortByDesc('paid_total')->values();
        } elseif ($request->get('sort') == 'booking_desc') {
            $customers = $customers->sortByDesc('bookings_count')->values();
        }

        // customer đang chọn
        $selectedCustomer = $customers->first();

        if ($request->filled('customer_id')) {
            $selectedCustomer = $customers->where(
                'customer_id',
                $request->customer_id
            )->first() ?? $selectedCustomer;
        }

        // top thống kê theo filter hiện tại
        $topByBookings = $customers->sortByDesc('completed_count')->first();
        $topBySpent = $customers->sortByDesc('paid_total')->first();

        return view('admin.customers.index', compact(
            'customers',
            'selectedCustomer',
            'topByBookings',
            'topBySpent'
        ));
    }
}