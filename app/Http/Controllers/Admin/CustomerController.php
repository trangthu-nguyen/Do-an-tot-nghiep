<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount('bookings')
            ->withSum('bookings', 'total_amount')
            ->with(['bookings' => function ($q) {
                $q->with('bookingDetails.service')
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

        $selectedCustomer = $customers->first();

        if ($request->filled('customer_id')) {
            $selectedCustomer = Customer::withCount('bookings')
                ->withSum('bookings', 'total_amount')
                ->with(['bookings.bookingDetails.service'])
                ->find($request->customer_id) ?? $selectedCustomer;
        }

        return view('admin.customers.index', compact(
            'customers',
            'selectedCustomer'
        ));
    }
}