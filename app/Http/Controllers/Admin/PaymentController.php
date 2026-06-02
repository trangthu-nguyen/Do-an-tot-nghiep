<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.customer'])
            ->orderBy('payment_id', 'desc');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('payment_id', 'like', "%$keyword%")
                    ->orWhere('booking_id', 'like', "%$keyword%")
                    ->orWhereHas('booking.customer', function ($cus) use ($keyword) {
                        $cus->where('full_name', 'like', "%$keyword%")
                            ->orWhere('phone', 'like', "%$keyword%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        $payments = $query->get();

        $todayRevenue = Payment::where('payment_status', 'paid')
            ->whereDate('payment_date', today())
            ->sum('amount');

        $monthRevenue = Payment::where('payment_status', 'paid')
            ->whereYear('payment_date', now()->year)
            ->whereMonth('payment_date', now()->month)
            ->sum('amount');

        $yearRevenue = Payment::where('payment_status', 'paid')
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        $pendingAmount = Payment::where('payment_status', 'pending')->sum('amount');
        $pendingCount = Payment::where('payment_status', 'pending')->count();

        return view('admin.payments.index', compact(
            'payments',
            'todayRevenue',
            'monthRevenue',
            'yearRevenue',
            'pendingAmount',
            'pendingCount'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,unpaid'
        ]);

        $payment = Payment::findOrFail($id);

        $payment->update([
            'payment_status' => $request->payment_status,
            'payment_date' => $request->payment_status == 'paid'
                ? now()
                : $payment->payment_date
        ]);

        return redirect()
            ->back()
            ->with('success', 'Cập nhật trạng thái thanh toán thành công!');
    }
}