<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with([
            'customer.bookings.payment',
            'customer.bookings.bookingDetails.service',
            'booking.bookingDetails.service'
        ])->orderBy('feedback_id', 'desc');

        if ($request->filled('hidden')) {
            $query->where('is_hidden', $request->hidden);
        } elseif ($request->filled('status')) {
            $query->where('status', $request->status)
                ->where('is_hidden', 0);
        }

        $feedbacks = $query->get();

        foreach ($feedbacks as $feedback) {
            if ($feedback->customer) {
                $this->setCustomerRank($feedback->customer);
            }
        }

        $selectedFeedback = null;

        if ($request->filled('feedback_id')) {
            $selectedFeedback = Feedback::with([
                'customer.bookings.payment',
                'customer.bookings.bookingDetails.service',
                'booking.bookingDetails.service'
            ])->find($request->feedback_id);

            if ($selectedFeedback && $selectedFeedback->customer) {
                $this->setCustomerRank($selectedFeedback->customer);
            }
        }

        if (!$selectedFeedback) {
            $selectedFeedback = $feedbacks->first();
        }

        $totalCount = Feedback::count();

        $pendingCount = Feedback::where('status', 0)
            ->where('is_hidden', 0)
            ->count();

        $approvedCount = Feedback::where('status', 1)
            ->where('is_hidden', 0)
            ->count();

        $hiddenCount = Feedback::where('is_hidden', 1)
            ->count();

        return view('admin.feedbacks.index', compact(
            'feedbacks',
            'selectedFeedback',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'hiddenCount'
        ));
    }

    public function approve($id)
    {
        Feedback::where('feedback_id', $id)->update([
            'status' => 1,
            'is_hidden' => 0
        ]);

        return redirect()
            ->route('admin.feedbacks.index', [
                'status' => 1,
                'feedback_id' => $id
            ])
            ->with('success', 'Đã duyệt đánh giá thành công!');
    }

    public function hide($id)
    {
        Feedback::where('feedback_id', $id)->update([
            'status' => 1,
            'is_hidden' => 1
        ]);

        return redirect()
            ->route('admin.feedbacks.index', [
                'hidden' => 1,
                'feedback_id' => $id
            ])
            ->with('success', 'Đã ẩn đánh giá!');
    }

    public function showAgain($id)
    {
        Feedback::where('feedback_id', $id)->update([
            'status' => 1,
            'is_hidden' => 0
        ]);

        return redirect()
            ->route('admin.feedbacks.index', [
                'status' => 1,
                'feedback_id' => $id
            ])
            ->with('success', 'Đã hiển thị lại đánh giá!');
    }

    private function setCustomerRank($customer): void
    {
        $paidTotal = $customer->bookings->sum(function ($booking) {
            return optional($booking->payment)->payment_status == 'paid'
                ? (float) optional($booking->payment)->amount
                : 0;
        });

        $completedCount = $customer->bookings->where('status', 3)->count();

        $customer->paid_total = $paidTotal;
        $customer->completed_count = $completedCount;

        if ($paidTotal >= 5000000 || $completedCount >= 5) {
            $customer->rank_label = 'VIP';
        } elseif ($paidTotal >= 2000000 || $completedCount >= 3) {
            $customer->rank_label = 'Khách hàng thân thiết';
        } else {
            $customer->rank_label = 'Khách hàng thường';
        }
    }
}