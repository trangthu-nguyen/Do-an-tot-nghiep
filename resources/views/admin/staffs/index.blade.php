@extends('admin.layout')

@section('title', 'Quản lý nhân sự & lịch làm việc')

@section('content')

<style>
    :root{--primary:#7b5554;--dark:#2f2323;--muted:#8a7e7e;--border:#eadede;--soft:#faf7f7}
    .staff-page{display:grid;grid-template-columns:260px 1fr;gap:22px}
    .top-box,.filter-box,.list-box,.weekly-box{background:white;border:1px solid var(--border);border-radius:24px;box-shadow:0 12px 32px rgba(123,85,84,.06)}
    .top-box{padding:22px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;gap:18px;flex-wrap:wrap}
    .page-title{font-family:'Noto Serif',serif;font-size:30px;font-weight:900;color:var(--primary)}
    .page-sub{color:var(--muted);font-weight:600;font-size:14px}
    .tab-btn{border:0;border-radius:14px;padding:11px 18px;font-weight:900;background:#f2eeee;color:#7b6d6d;text-decoration:none}
    .tab-btn.active{background:white;color:var(--primary);border:1px solid var(--border)}
    .btn-main{border:0;background:var(--primary);color:white;border-radius:14px;padding:11px 18px;font-weight:900;text-decoration:none}
    .btn-outline-main{border:1px solid var(--primary);background:white;color:var(--primary);border-radius:14px;padding:11px 18px;font-weight:900}
    .filter-box{padding:22px}
    .filter-title{font-weight:900;color:var(--primary);margin-bottom:18px}
    .filter-label{font-size:12px;text-transform:uppercase;color:#9b8f8f;font-weight:900;margin-bottom:8px}
    .filter-chip{display:inline-flex;align-items:center;gap:6px;border-radius:999px;background:#f4eeee;color:#7b5554;padding:7px 11px;font-size:12px;font-weight:900;margin:0 6px 8px 0;text-decoration:none}
    .filter-chip.active{background:var(--primary);color:white}
    .side-stat{background:#fff6f6;border-radius:22px;padding:20px;margin-top:22px}
    .side-num{font-size:34px;font-weight:900;color:var(--primary)}
    .list-box{padding:24px}
    .box-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
    .box-title{font-family:'Noto Serif',serif;font-weight:900;color:var(--dark);font-size:20px}
    .table th{font-size:11px;text-transform:uppercase;color:#9b8f8f;border-bottom:1px solid #f2eeee;padding:14px}
    .table td{padding:16px 14px;border-bottom:1px solid #f7eeee;vertical-align:middle}
    .avatar{width:42px;height:42px;border-radius:50%;object-fit:cover;border:3px solid #f1dddd}
    .staff-name{font-weight:900;color:var(--dark)}
    .small-muted{font-size:12px;color:var(--muted);font-weight:700}
    .skill-tag{display:inline-block;background:#f4eeee;color:#7b5554;border-radius:999px;padding:5px 9px;font-size:11px;font-weight:900;margin:2px}
    .status-pill{border-radius:999px;padding:6px 10px;font-size:11px;font-weight:900}
    .st-active{background:#dcfce7;color:#15803d}.st-off{background:#f1f1f1;color:#777}
    .action-wrap{display:flex;align-items:center;gap:10px}
    .action-btn{width:36px;height:36px;border-radius:12px;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;text-decoration:none;background:white;font-size:16px}
    .edit-btn{color:#7b5554}.edit-btn:hover{background:#f7eeee;color:#7b5554}
    .delete-btn{color:#ba1a1a}.delete-btn:hover{background:#fff0f0;color:#ba1a1a}
    .weekly-box{padding:24px;margin-top:22px}
    .week-nav{display:flex;align-items:center;gap:10px}
    .week-btn{width:34px;height:34px;border-radius:50%;border:1px solid var(--border);background:white;color:var(--primary)}
    .schedule-table{width:100%;border-collapse:separate;border-spacing:0}
    .schedule-table th{background:#fbf8f8;color:#8a7e7e;font-size:11px;text-transform:uppercase;padding:13px;border-bottom:1px solid #f2eeee}
    .schedule-table td{padding:12px;border-bottom:1px solid #f4eeee;vertical-align:middle}
    .shift-pill{display:inline-block;border-radius:10px;padding:7px 11px;font-size:11px;font-weight:900}
    .shift-approved{background:#f8e9e9;color:#7b5554;border:1px solid #e8baba}
    .shift-pending{background:#fff4d6;color:#a16207;border:1px solid #f5d88f}
    .shift-busy{background:#ffe4e6;color:#be123c;border:1px solid #fecdd3}
    .shift-empty{color:#c0b5b5;font-weight:800;font-size:12px}
    .approve-mini{border:0;background:#7b5554;color:white;border-radius:9px;padding:5px 8px;font-size:10px;font-weight:900;margin-top:5px}
    .legend{display:flex;gap:14px;flex-wrap:wrap;font-size:12px;color:#8a7e7e;font-weight:800;margin-top:14px}
    .dot{width:9px;height:9px;border-radius:50%;display:inline-block;margin-right:5px}
    .dot-approved{background:#7b5554}.dot-pending{background:#f59e0b}.dot-busy{background:#be123c}
    @media(max-width:1100px){.staff-page{grid-template-columns:1fr}}
</style>

@php
    $femalePortraits = [
        'https://randomuser.me/api/portraits/women/44.jpg',
        'https://randomuser.me/api/portraits/women/65.jpg',
        'https://randomuser.me/api/portraits/women/68.jpg',
        'https://randomuser.me/api/portraits/women/71.jpg',
        'https://randomuser.me/api/portraits/women/72.jpg',
        'https://randomuser.me/api/portraits/women/76.jpg',
        'https://randomuser.me/api/portraits/women/79.jpg',
        'https://randomuser.me/api/portraits/women/81.jpg'
    ];

    $prevWeek = $weekStart->copy()->subWeek()->toDateString();
    $nextWeek = $weekStart->copy()->addWeek()->toDateString();

    function scheduleClassName($status){
        return match($status){
            'approved' => 'shift-approved',
            'busy' => 'shift-busy',
            default => 'shift-pending',
        };
    }

    function scheduleStatusText($status){
        return match($status){
            'approved' => 'Đã duyệt',
            'busy' => 'Nghỉ phép',
            default => 'Chờ duyệt',
        };
    }
@endphp

<div class="top-box">
    <div>
        
        <div class="page-sub">Theo dõi nhân viên và lịch làm việc do nhân viên tự đăng ký.</div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <a href="#staff-list" class="tab-btn active">Danh sách nhân viên</a>
        <a href="#weekly-schedule" class="tab-btn">Lịch làm việc</a>

        <form action="{{ route('admin.staffs.schedules.approveAll') }}" method="POST">
            @csrf
            <button class="btn-outline-main"
                    onclick="return confirm('Duyệt tất cả lịch đang chờ?')">
                <i class="bi bi-check2-circle"></i> Duyệt tất cả lịch
            </button>
        </form>

        <a href="{{ route('admin.staffs.create') }}" class="btn-main">
            <i class="bi bi-plus"></i> Thêm nhân viên
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-4">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger rounded-4">{{ session('error') }}</div>
@endif

<div class="staff-page">

    <aside>
        <div class="filter-box">
            <div class="filter-title">
                <i class="bi bi-sliders"></i> Bộ lọc
            </div>

            <div class="filter-label">Dịch vụ / kỹ năng</div>

            <a href="{{ route('admin.staffs.index', request()->except('skill')) }}"
               class="filter-chip {{ request('skill') ? '' : 'active' }}">
                Tất cả
            </a>

            <a href="{{ route('admin.staffs.index', array_merge(request()->except('skill'), ['skill' => 'Skincare'])) }}"
               class="filter-chip {{ request('skill') == 'Skincare' ? 'active' : '' }}">
                Skincare
            </a>

            <a href="{{ route('admin.staffs.index', array_merge(request()->except('skill'), ['skill' => 'Nails'])) }}"
               class="filter-chip {{ request('skill') == 'Nails' ? 'active' : '' }}">
                Nails
            </a>

            <a href="{{ route('admin.staffs.index', array_merge(request()->except('skill'), ['skill' => 'Massage'])) }}"
               class="filter-chip {{ request('skill') == 'Massage' ? 'active' : '' }}">
                Massage
            </a>

            <div class="filter-label mt-3">Trạng thái</div>

            <a href="{{ route('admin.staffs.index', request()->except('status')) }}"
               class="filter-chip {{ request('status') === null ? 'active' : '' }}">
                Tất cả
            </a>

            <a href="{{ route('admin.staffs.index', array_merge(request()->except('status'), ['status' => 1])) }}"
               class="filter-chip {{ request('status') === '1' ? 'active' : '' }}">
                Hoạt động
            </a>

            <a href="{{ route('admin.staffs.index', array_merge(request()->except('status'), ['status' => 0])) }}"
               class="filter-chip {{ request('status') === '0' ? 'active' : '' }}">
                Nghỉ phép
            </a>

            <div class="side-stat">
                <div class="filter-label">Thống kê tuần</div>
                <div class="side-num">{{ $pendingSchedules + $approvedSchedules }}</div>
                <div class="small-muted">Tổng số ca đã đăng ký</div>
                <div class="small-muted mt-2">Chờ duyệt: {{ $pendingSchedules }}</div>
                <div class="small-muted">Đã duyệt: {{ $approvedSchedules }}</div>
                <div class="small-muted">Nghỉ / bận: {{ $busySchedules }}</div>
            </div>
        </div>
    </aside>

    <section>
        <div class="list-box" id="staff-list">
            <div class="box-head">
                <div class="box-title">Danh sách nhân sự</div>
                <div class="small-muted">{{ $staffs->count() }} nhân viên</div>
            </div>

            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Nhân viên</th>
                            <th>Vị trí / kỹ năng</th>
                            <th>Số điện thoại</th>
                            <th>Trạng thái</th>
                            <th width="130">Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($staffs as $staff)
                            @php
                                $avatarImage = $femalePortraits[$staff->staff_id % count($femalePortraits)];
                            @endphp

                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $avatarImage }}" class="avatar" alt="{{ $staff->full_name }}">
                                        <div>
                                            <div class="staff-name">{{ $staff->full_name }}</div>
                                            <div class="small-muted">ID #{{ $staff->staff_id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @foreach(explode(',', $staff->skill ?? 'Chuyên viên') as $skill)
                                        <span class="skill-tag">{{ trim($skill) }}</span>
                                    @endforeach
                                </td>

                                <td>{{ $staff->phone }}</td>

                                <td>
                                    @if($staff->status == 1)
                                        <span class="status-pill st-active">Hoạt động</span>
                                    @else
                                        <span class="status-pill st-off">Nghỉ phép</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="action-wrap">
                                        <a href="{{ route('admin.staffs.edit', $staff->staff_id) }}"
                                           class="action-btn edit-btn"
                                           title="Sửa nhân viên">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('admin.staffs.destroy', $staff->staff_id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Bạn chắc chắn muốn xóa nhân viên này?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="action-btn delete-btn"
                                                    title="Xóa nhân viên">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    Chưa có nhân viên nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="weekly-box" id="weekly-schedule">
            <div class="box-head">
                <div>
                    <div class="box-title">Lịch làm việc tuần này</div>
                    <div class="small-muted">
                        {{ $weekStart->format('d/m/Y') }} - {{ $weekEnd->format('d/m/Y') }}
                    </div>
                </div>

                <div class="week-nav">
                    <a href="{{ route('admin.staffs.index', array_merge(request()->except('week'), ['week' => $prevWeek])) }}"
                       class="week-btn d-flex align-items-center justify-content-center text-decoration-none">
                        <i class="bi bi-chevron-left"></i>
                    </a>

                    <a href="{{ route('admin.staffs.index') }}"
                       class="btn-outline-main text-decoration-none">
                        Tuần này
                    </a>

                    <a href="{{ route('admin.staffs.index', array_merge(request()->except('week'), ['week' => $nextWeek])) }}"
                       class="week-btn d-flex align-items-center justify-content-center text-decoration-none">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>Nhân viên</th>
                            @foreach($weekDays as $day)
                                <th>
                                    {{ $day->isoFormat('dd') }}<br>
                                    {{ $day->format('d/m') }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($staffs as $staff)
                            <tr>
                                <td>
                                    <div class="staff-name">{{ $staff->full_name }}</div>
                                    <div class="small-muted">{{ $staff->skill }}</div>
                                </td>

                                @foreach($weekDays as $day)
                                    @php
                                        $dateKey = $day->format('Y-m-d');
                                        $daySchedules = $schedules->get($staff->staff_id . '_' . $dateKey, collect());
                                    @endphp

                                    <td>
                                        @if($daySchedules->count())
    @foreach($daySchedules as $schedule)
        <div class="mb-2">
            <div class="shift-pill {{ scheduleClassName($schedule->status) }}">
                {{ $schedule->status == 'busy' ? 'Nghỉ' : $schedule->shift_name }}
            </div>

            <div class="small-muted mt-1">
                {{ $schedule->status == 'busy'
                    ? 'Nghỉ / bận'
                    : substr($schedule->start_time, 0, 5) . ' - ' . substr($schedule->end_time, 0, 5) }}
            </div>

            <div class="small-muted">
                {{ scheduleStatusText($schedule->status) }}
            </div>

            @if($schedule->status == 'available')
                <form action="{{ route('admin.staffs.schedules.approve', $schedule->schedule_id) }}"
                      method="POST">
                    @csrf
                    <button class="approve-mini">
                        Duyệt
                    </button>
                </form>
            @endif
        </div>
    @endforeach
@else
    <span class="shift-empty">OFF</span>
@endif
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    Chưa có dữ liệu lịch làm.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="legend">
                <span><i class="dot dot-approved"></i> Đã duyệt</span>
                <span><i class="dot dot-pending"></i> Chờ duyệt</span>
                <span><i class="dot dot-busy"></i> Nghỉ phép</span>
            </div>
        </div>
    </section>
</div>

@endsection