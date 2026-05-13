<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->with('error', 'Email hoặc mật khẩu không đúng!');
        }

        // ✅ Lưu session đầy đủ để middleware hoạt động
        session([
            'admin'      => true,
            'admin_id'   => $admin->admin_id,
            'admin_name' => $admin->full_name
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
    }

    public function logout()
    {
        session()->forget(['admin', 'admin_id', 'admin_name']);
        return redirect()->route('admin.login')->with('success', 'Đã đăng xuất!');
    }
}