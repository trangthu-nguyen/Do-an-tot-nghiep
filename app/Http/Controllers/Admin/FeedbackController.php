<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with(['customer', 'booking.bookingDetails.service'])
            ->orderBy('feedback_id', 'desc')
            ->get();
        return view('admin.feedbacks.index', compact('feedbacks'));
    }
}