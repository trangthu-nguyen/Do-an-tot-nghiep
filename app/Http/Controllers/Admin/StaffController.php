<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Booking;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::withCount('bookings')
            ->orderBy('staff_id', 'desc')
            ->get();

        $totalStaff = Staff::count();
        $activeStaff = Staff::where('status', 1)->count();
        $todayBookings = Booking::whereDate('booking_date', today())
            ->where('status', '!=', 4)
            ->count();

        return view('admin.staffs.index', compact(
            'staffs',
            'totalStaff',
            'activeStaff',
            'todayBookings'
        ));
    }

    public function create()
    {
        return view('admin.staffs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|max:100',
            'email'     => 'required|email|unique:staffs,email',
            'phone'     => 'required|max:15',
            'password'  => 'required|min:6',
            'address'   => 'nullable|max:255',
            'skill'     => 'nullable|max:255',
            'bio'       => 'nullable|string',
            'status'    => 'required|in:0,1'
        ]);

        Staff::create([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => bcrypt($request->password),
            'address'   => $request->address,
            'skill'     => $request->skill,
            'bio'       => $request->bio,
            'status'    => $request->status,
        ]);

        return redirect()
            ->route('admin.staffs.index')
            ->with('success', 'Thêm nhân viên thành công!');
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);

        return view('admin.staffs.edit', compact('staff'));
    }

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
            'bio'       => 'nullable|string',
            'status'    => 'required|in:0,1'
        ]);

        $data = [
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'skill'     => $request->skill,
            'bio'       => $request->bio,
            'status'    => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $staff->update($data);

        return redirect()
            ->route('admin.staffs.index')
            ->with('success', 'Cập nhật nhân viên thành công!');
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);

        if ($staff->bookings()->count() > 0) {
            return redirect()
                ->route('admin.staffs.index')
                ->with('error', 'Không thể xóa nhân viên này vì đã có dữ liệu lịch đặt. Vui lòng chuyển trạng thái sang Ngưng.');
        }

        $staff->delete();

        return redirect()
            ->route('admin.staffs.index')
            ->with('success', 'Xóa nhân viên thành công!');
    }
}