<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class ProfileController extends Controller
{
    public function index()
    {
        $customer = Customer::findOrFail(session('customer_id'));
        return view('customer.profile.index', compact('customer'));
    }

    public function edit()
    {
        $customer = Customer::findOrFail(session('customer_id'));
        return view('customer.profile.edit', compact('customer'));
    }

    public function update(Request $request)
    {
        $customer = Customer::findOrFail(session('customer_id'));

        $request->validate([
            'full_name'  => 'required|string|max:255',
            'phone'      => 'required|string|max:20',
            'email'      => 'required|email|max:255',
            'birth_date' => 'nullable|date',
            'gender'     => 'nullable|in:Nam,Nữ,Khác',
            'avatar'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'full_name',
            'phone',
            'email',
            'birth_date',
            'gender',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarName = time() . '_' . $request->file('avatar')->getClientOriginalName();
            $request->file('avatar')->move(public_path('uploads/customers'), $avatarName);
            $data['avatar'] = $avatarName;
        }

        $customer->update($data);

        return redirect()
            ->route('customer.profile.index')
            ->with('success', 'Cập nhật thông tin thành công!');
    }

    public function address()
    {
        $customer = Customer::findOrFail(session('customer_id'));
        return view('customer.profile.address', compact('customer'));
    }

    public function updateAddress(Request $request)
    {
        $customer = Customer::findOrFail(session('customer_id'));

        $request->validate([
            'address' => 'required|string|max:500'
        ]);

        $customer->update([
            'address' => $request->address
        ]);

        return redirect()
            ->route('customer.profile.index')
            ->with('success', 'Lưu địa chỉ thành công!');
    }
}