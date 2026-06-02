<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount('bookings')
            ->with(['bookings' => function ($q) {
                $q->with(['bookingDetails.service', 'payment'])
                    ->orderBy('booking_date', 'desc')
                    ->orderBy('booking_time', 'desc');
            }]);

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
            $paidTotal = $customer->bookings->sum(function ($booking) {
                return optional($booking->payment)->payment_status == 'paid'
                    ? (float) optional($booking->payment)->amount
                    : 0;
            });

            $completedCount = $customer->bookings->where('status', 3)->count();

            $customer->paid_total = $paidTotal;
            $customer->completed_count = $completedCount;

            if ($paidTotal >= 5000000 || $completedCount >= 5) {
                $customer->rank_label = 'VIP - Đề xuất tặng voucher';
            } elseif ($paidTotal >= 2000000 || $completedCount >= 3) {
                $customer->rank_label = 'Khách hàng thân thiết';
            } else {
                $customer->rank_label = 'Khách hàng thường';
            }
        }

        if ($request->get('sort') == 'spent_desc') {
            $customers = $customers->sortByDesc('paid_total')->values();
        } elseif ($request->get('sort') == 'booking_desc') {
            $customers = $customers->sortByDesc('bookings_count')->values();
        }

        $selectedCustomer = $customers->first();

        if ($request->filled('customer_id')) {
            $selectedCustomer = Customer::withCount('bookings')
                ->with(['bookings.bookingDetails.service', 'bookings.payment'])
                ->find($request->customer_id) ?? $selectedCustomer;

            if ($selectedCustomer) {
                $selectedCustomer->paid_total = $selectedCustomer->bookings->sum(function ($booking) {
                    return optional($booking->payment)->payment_status == 'paid'
                        ? (float) optional($booking->payment)->amount
                        : 0;
                });

                $selectedCustomer->completed_count = $selectedCustomer->bookings->where('status', 3)->count();

                if ($selectedCustomer->paid_total >= 5000000 || $selectedCustomer->completed_count >= 5) {
                    $selectedCustomer->rank_label = 'VIP - Đề xuất tặng voucher';
                } elseif ($selectedCustomer->paid_total >= 2000000 || $selectedCustomer->completed_count >= 3) {
                    $selectedCustomer->rank_label = 'Khách hàng thân thiết';
                } else {
                    $selectedCustomer->rank_label = 'Khách hàng thường';
                }
            }
        }

        $topByBookings = $customers->sortByDesc('bookings_count')->first();
        $topBySpent = $customers->sortByDesc('paid_total')->first();

        return view('admin.customers.index', compact(
            'customers',
            'selectedCustomer',
            'topByBookings',
            'topBySpent'
        ));
    }
}