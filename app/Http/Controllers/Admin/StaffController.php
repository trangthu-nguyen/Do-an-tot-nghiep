<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;

class StaffController extends Controller
{
    // Danh sách nhân viên
    public function index()
    {
        $staffs = Staff::orderBy('staff_id', 'desc')->get();
        return view('admin.staffs.index', compact('staffs'));
    }

    // Form thêm nhân viên
    public function create()
    {
        return view('admin.staffs.create');
    }

    // Lưu nhân viên mới
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|max:100',
            'email'     => 'required|email|unique:staffs,email',
            'phone'     => 'required|max:15',
            'password'  => 'required|min:6',
            'address'   => 'nullable|max:255',
            'skill'     => 'nullable|max:255',
            'status'    => 'required|in:0,1'
        ]);

        Staff::create([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => bcrypt($request->password),
            'address'   => $request->address,
            'skill'     => $request->skill,
            'status'    => $request->status,
        ]);

        return redirect()->route('admin.staffs.index')->with('success', 'Thêm nhân viên thành công!');
    }

    // Form sửa nhân viên
    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        return view('admin.staffs.edit', compact('staff'));
    }

    // Cập nhật nhân viên
    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $request->validate([
            'full_name' => 'required|max:100',
            'email'     => 'required|email|unique:staffs,email,' . $staff->staff_id . ',staff_id',
            'phone'     => 'required|max:15',
            'password'  => 'nullable|min:6',
            'address'   => 'nullable|max:255',
            'skill'     => 'nullable|max:255',
            'status'    => 'required|in:0,1'
        ]);

        $data = [
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'skill'     => $request->skill,
            'status'    => $request->status,
        ];

        // nếu có nhập password thì update
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $staff->update($data);

        return redirect()->route('admin.staffs.index')->with('success', 'Cập nhật nhân viên thành công!');
    }

    // Xóa nhân viên
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);

        if ($staff->bookings()->count() > 0) {
            return redirect()->route('admin.staffs.index')->with('error', 'Không thể xóa nhân viên này vì đã có dữ liệu lịch đặt. Vui lòng chuyển trạng thái sang Nghỉ việc.');
        }

        $staff->delete();

        return redirect()->route('admin.staffs.index')->with('success', 'Xóa nhân viên thành công!');
    }
}