<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Booking;
use App\Models\StaffSchedule;
use App\Models\Notification;
use Carbon\Carbon;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $weekStart = $request->filled('week')
            ? Carbon::parse($request->week)->startOfWeek()
            : now()->startOfWeek();

        $weekEnd = $weekStart->copy()->endOfWeek();

        $query = Staff::withCount('bookings')
            ->orderBy('staff_id', 'desc');

        if ($request->filled('skill')) {
            $query->where('skill', 'like', '%' . $request->skill . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $staffs = $query->get();

        $schedules = StaffSchedule::whereBetween('work_date', [
        $weekStart->toDateString(),
        $weekEnd->toDateString()
    ])
    ->orderBy('start_time')
    ->get()
    ->groupBy(function ($item) {
        return $item->staff_id . '_' . Carbon::parse($item->work_date)->format('Y-m-d');
    });

        $weekDays = collect(range(0, 6))->map(function ($i) use ($weekStart) {
            return $weekStart->copy()->addDays($i);
        });

        $totalStaff = Staff::count();
        $activeStaff = Staff::where('status', 1)->count();

        $todayBookings = Booking::whereDate('booking_date', today())
            ->where('status', '!=', 4)
            ->count();

        $pendingSchedules = StaffSchedule::where('status', 'available')->count();
        $approvedSchedules = StaffSchedule::where('status', 'approved')->count();
        $busySchedules = StaffSchedule::where('status', 'busy')->count();

        return view('admin.staffs.index', compact(
            'staffs',
            'schedules',
            'weekDays',
            'weekStart',
            'weekEnd',
            'totalStaff',
            'activeStaff',
            'todayBookings',
            'pendingSchedules',
            'approvedSchedules',
            'busySchedules'
        ));
    }

    public function approveSchedule($id)
    {
        $schedule = StaffSchedule::findOrFail($id);

        $schedule->update([
            'status' => 'approved',
            'updated_at' => now()
        ]);

        Notification::create([
            'user_type'  => 'staff',
            'user_id'    => $schedule->staff_id,
            'title'      => 'Lịch làm đã được duyệt',
            'content'    => 'Lịch làm ngày ' . Carbon::parse($schedule->work_date)->format('d/m/Y') . ' của bạn đã được admin duyệt.',
            'is_read'    => 0,
            'created_at' => now()
        ]);

        return redirect()
            ->back()
            ->with('success', 'Đã duyệt lịch làm của nhân viên!');
    }

    public function approveAllSchedules()
    {
        $count = StaffSchedule::where('status', 'available')->count();

        StaffSchedule::where('status', 'available')->update([
            'status' => 'approved',
            'updated_at' => now()
        ]);

        return redirect()
            ->back()
            ->with('success', 'Đã duyệt ' . $count . ' lịch làm đang chờ!');
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