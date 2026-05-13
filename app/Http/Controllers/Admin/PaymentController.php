<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::orderBy('payment_id', 'desc')->get();
        return view('admin.payments.index', compact('payments'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required'
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update([
            'payment_status' => $request->payment_status
        ]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái thanh toán thành công!');
    }
}