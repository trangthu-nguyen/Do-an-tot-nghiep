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
            'customer',
            'booking.bookingDetails.service'
        ])->orderBy('feedback_id', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $feedbacks = $query->get();

        $selectedFeedback = null;

        if ($request->filled('feedback_id')) {
            $selectedFeedback = Feedback::with([
                'customer',
                'booking.bookingDetails.service'
            ])->find($request->feedback_id);
        }

        if (!$selectedFeedback) {
            $selectedFeedback = $feedbacks->first();
        }

        $totalCount = Feedback::count();
        $pendingCount = Feedback::where('status', 0)->count();
        $approvedCount = Feedback::where('status', 1)->count();
        $rejectedCount = Feedback::where('status', 2)->count();

        return view('admin.feedbacks.index', compact(
            'feedbacks',
            'selectedFeedback',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    public function approve($id)
    {
        Feedback::where('feedback_id', $id)->update([
            'status' => 1
        ]);

        return redirect()
            ->route('admin.feedbacks.index', ['status' => 1, 'feedback_id' => $id])
            ->with('success', 'Đã duyệt đánh giá thành công!');
    }

    public function reject($id)
    {
        Feedback::where('feedback_id', $id)->update([
            'status' => 2
        ]);

        return redirect()
            ->route('admin.feedbacks.index', ['status' => 2, 'feedback_id' => $id])
            ->with('success', 'Đã từ chối đánh giá!');
    }

    public function destroy($id)
    {
        Feedback::where('feedback_id', $id)->delete();

        return redirect()
            ->route('admin.feedbacks.index')
            ->with('success', 'Đã xóa đánh giá!');
    }
}