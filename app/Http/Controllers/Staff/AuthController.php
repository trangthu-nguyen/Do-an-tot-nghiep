<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('staff.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $staff = Staff::where('email', $request->email)->first();

        if (!$staff || !Hash::check($request->password, $staff->password)) {
            return back()->with('error', 'Email hoặc mật khẩu không đúng!');
        }

        session([
            'staff_id' => $staff->staff_id,
            'staff_name' => $staff->full_name
        ]);

        return redirect()->route('staff.bookings.index')->with('success', 'Đăng nhập thành công!');
    }

    public function logout()
    {
        session()->forget(['staff_id', 'staff_name']);
        return redirect()->route('staff.login')->with('success', 'Đã đăng xuất!');
    }
}