<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ================= REGISTER =================
    public function showRegisterForm()
    {
        return view('customer.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:customers,email',
            'phone'     => 'required|string|max:20',
            'password'  => 'required|min:6|confirmed',
        ]);

        Customer::create([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
        ]);

        return redirect()->route('customer.login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

    // ================= LOGIN =================
    public function showLoginForm()
    {
        return view('customer.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return back()->with('error', 'Email hoặc mật khẩu không đúng!');
        }

        // ✅ Lưu session
        session()->put('customer_id', $customer->customer_id);
        session()->put('customer_name', $customer->full_name);
        session()->put('customer_phone', $customer->phone);

        return redirect()->route('customer.home')->with('success', 'Đăng nhập thành công!');
    }

    // ================= LOGOUT =================
    public function logout()
    {
        session()->forget('customer_id');
        session()->forget('customer_name');
        session()->forget('customer_phone');

        return redirect()->route('customer.login')->with('success', 'Đã đăng xuất!');
    }
}