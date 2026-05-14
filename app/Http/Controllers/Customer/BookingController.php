<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Service;
use App\Services\BookingService;
use Exception;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        $bookings = Booking::where('customer_id', session('customer_id'))
            ->with(['bookingDetails.service', 'feedback', 'payment', 'staff'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->get();

        return view('customer.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::where('booking_id', $id)
            ->where('customer_id', session('customer_id'))
            ->with(['bookingDetails.service', 'feedback', 'payment', 'staff'])
            ->firstOrFail();

        return view('customer.bookings.show', compact('booking'));
    }

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

    public function store(StoreBookingRequest $request)
    {
        try {
            if (!session('customer_id')) {
                return redirect()
                    ->route('customer.login')
                    ->with('error', 'Bạn cần đăng nhập để đặt lịch!');
            }

            $this->bookingService->createBookingSingleService(
                $request->validated(),
                session('customer_id')
            );

            return redirect()
                ->route('customer.bookings.index')
                ->with('success', 'Đặt lịch thành công!');
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        $booking = Booking::with('bookingDetails.service')->findOrFail($id);

        try {
            $this->bookingService->cancelBookingByCustomer(
                $booking,
                session('customer_id')
            );

            return redirect()
                ->back()
                ->with('success', 'Hủy lịch thành công!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}