<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $topServices = Service::select('services.*')
            ->leftJoin('booking_details', 'services.service_id', '=', 'booking_details.service_id')
            ->selectRaw('COUNT(booking_details.service_id) as total_bookings')
            ->groupBy(
                'services.service_id',
                'services.category_id',
                'services.service_name',
                'services.price',
                'services.duration',
                'services.description',
                'services.image',
                'services.status'
            )
            ->orderByDesc('total_bookings')
            ->limit(3)
            ->get();

        return view('customer.home', compact('topServices'));
    }
}