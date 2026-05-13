<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\Booking;

class FeedbackController extends Controller
{
    public function create($booking_id)
    {
        $booking = Booking::with('bookingDetails.service')->findOrFail($booking_id);

        // Chỉ đúng customer mới được đánh giá booking của mình
        if ($booking->customer_id != session('customer_id')) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'Bạn không có quyền đánh giá booking này!');
        }

        // Chỉ được đánh giá khi booking hoàn thành
        if ($booking->status != 3) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'Chỉ được đánh giá khi booking hoàn thành!');
        }

        // Nếu đã đánh giá rồi thì không cho đánh giá lại
        $check = Feedback::where('booking_id', $booking_id)->first();
        if ($check) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'Booking này đã được đánh giá!');
        }

        return view('customer.feedback.create', compact('booking'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|max:500'
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        // Chỉ đúng customer mới được đánh giá booking của mình
        if ($booking->customer_id != session('customer_id')) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'Bạn không có quyền đánh giá booking này!');
        }

        // Chỉ được đánh giá khi booking hoàn thành
        if ($booking->status != 3) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'Chỉ được đánh giá khi booking hoàn thành!');
        }

        // Nếu đã đánh giá rồi thì không cho đánh giá lại
        $check = Feedback::where('booking_id', $request->booking_id)->first();
        if ($check) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'Booking này đã được đánh giá!');
        }

        Feedback::create([
            'booking_id' => $request->booking_id,
            'customer_id' => session('customer_id'),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'created_at' => now()
        ]);

        return redirect()->route('customer.bookings.index')->with('success', 'Gửi đánh giá thành công!');
    }
    public function show($booking_id)
    {
    $feedback = Feedback::with(['customer', 'booking.bookingDetails.service'])
        ->where('booking_id', $booking_id)
        ->first();

    if (!$feedback) {
        return redirect()->route('customer.bookings.index')->with('error', 'Booking này chưa có đánh giá!');
    }

    if ($feedback->customer_id != session('customer_id')) {
        return redirect()->route('customer.bookings.index')->with('error', 'Bạn không có quyền xem đánh giá này!');
    }

    return view('customer.feedback.show', compact('feedback'));
}
}