<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $staff = Staff::findOrFail(session('staff_id'));

        return view('staff.profile.index', compact('staff'));
    }

    public function update(Request $request)
    {
        $staff = Staff::findOrFail(session('staff_id'));

        $request->validate([
            'full_name' => 'required|max:100',
            'email'     => 'nullable|email|max:100',
            'phone'     => 'required|max:20',
            'address'   => 'nullable|max:255',
            'skill'     => 'nullable|max:255',
            'bio'       => 'nullable|string',
            'password'  => 'nullable|min:6',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'skill'     => $request->skill,
            'bio'       => $request->bio,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('uploads/staff'), $fileName);

            $data['image'] = $fileName;
        }

        $staff->update($data);

        session([
            'staff_name'  => $staff->full_name,
            'staff_image' => $staff->image,
        ]);

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }
}