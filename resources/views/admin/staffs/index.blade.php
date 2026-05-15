@extends('admin.layout')

@section('title', 'Quản lý nhân viên')

@section('content')

<style>
    .staff-top,.staff-card,.staff-stat,.add-card{background:white;border:1px solid #f0e4e4;border-radius:22px;box-shadow:0 12px 32px rgba(123,85,84,.06)}
    .staff-top{padding:24px;margin-bottom:22px;display:flex;justify-content:space-between;gap:18px;align-items:center}
    .mini{font-size:12px;font-weight:900;color:#9b8f8f}
    .heading{font-size:30px;font-weight:900;color:#2f2323;margin:6px 0}
    .sub{color:#7d7272;font-size:14px;margin:0}
    .btn-main{background:#7b5554;color:white;border-radius:14px;padding:11px 16px;text-decoration:none;font-weight:800;border:0}
    .btn-main:hover{background:#684847;color:white}
    .staff-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
    .staff-card{overflow:hidden}
    .cover{height:132px;background:#ffe1e0;position:relative}
    .cover img{width:100%;height:100%;object-fit:cover}
    .rating{position:absolute;right:12px;top:12px;background:white;border-radius:999px;padding:5px 10px;font-size:12px;font-weight:900;color:#7b5554}
    .available{position:absolute;left:12px;bottom:12px;background:#dcfce7;color:#15803d;border-radius:999px;padding:5px 10px;font-size:12px;font-weight:900}
    .busy{background:#eee;color:#777}
    .staff-body{padding:18px}
    .staff-name{font-size:20px;font-weight:900;color:#2f2323;font-family:'Noto Serif',serif;margin-bottom:4px}
    .staff-skill{font-size:13px;color:#7d7272;font-weight:700;min-height:20px}
    .tag{display:inline-block;background:#f4eeee;color:#7b5554;border-radius:999px;padding:6px 10px;font-size:12px;font-weight:800;margin:10px 4px 14px 0}
    .card-actions{display:flex;gap:10px}
    .btn-soft{flex:1;text-align:center;border-radius:13px;padding:10px;text-decoration:none;font-weight:800;border:1px solid #eadede;color:#7b5554;background:white}
    .btn-dark-soft{background:#7b5554;color:white}
    .btn-dark-soft:hover{color:white;background:#684847}
    .add-card{border:2px dashed #eadede;min-height:314px;display:flex;align-items:center;justify-content:center;text-align:center;padding:24px;text-decoration:none;color:#7b5554}
    .plus{width:58px;height:58px;border-radius:50%;background:#ffe1e0;display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 16px}
    .stats{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:28px}
    .staff-stat{padding:22px}
    .stat-num{font-size:30px;font-weight:900;color:#7b5554}
    .delete-form{flex:1}
    .delete-btn{width:100%;border:1px solid #f1d0d0;background:#fff;color:#ba1a1a;border-radius:13px;padding:10px;font-weight:800}
    @media(max-width:1100px){.staff-grid{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:768px){.staff-top{flex-direction:column;align-items:flex-start}.staff-grid,.stats{grid-template-columns:1fr}}
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
@endphp

<div class="staff-top">
    <div>
        <h1 class="heading">Staff Management</h1>
        <p class="sub">
            Quản lý nhân viên, kỹ năng chuyên môn, trạng thái làm việc và lịch được phân công.
        </p>
    </div>

    <a href="{{ route('admin.staffs.create') }}" class="btn-main">
        <i class="bi bi-plus-circle"></i> Add New Staff
    </a>
</div>

<div class="staff-grid">
    @forelse($staffs as $staff)
        @php
            $avatarImage = $femalePortraits[
                $staff->staff_id % count($femalePortraits)
            ];
        @endphp

        <div class="staff-card">
            <div class="cover">
                <img src="{{ $avatarImage }}"
                     alt="{{ $staff->full_name }}">

                <div class="rating">
                    ★ 4.9
                </div>

                @if($staff->status == 1)
                    <div class="available">
                        Hoạt động
                    </div>
                @else
                    <div class="available busy">
                        Bận
                    </div>
                @endif
            </div>

            <div class="staff-body">
                <div class="staff-name">
                    {{ $staff->full_name }}
                </div>

                <div class="staff-skill">
                    {{ $staff->skill ?? 'Chuyên viên làm đẹp' }}
                </div>

                <span class="tag">
                    {{ $staff->bookings_count }} lịch
                </span>

                <span class="tag">
                    {{ $staff->phone }}
                </span>

                <div class="card-actions">
                    <a href="{{ route('admin.staffs.edit', $staff->staff_id) }}"
                       class="btn-soft btn-dark-soft">
                        Xem/Sửa
                    </a>

                    <form action="{{ route('admin.staffs.destroy', $staff->staff_id) }}"
                          method="POST"
                          class="delete-form">
                        @csrf
                        @method('DELETE')

                        <button class="delete-btn"
                                onclick="return confirm('Bạn chắc chắn muốn xóa nhân viên này?')">
                            Xóa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="staff-card p-4 text-center text-muted">
            Chưa có nhân viên nào.
        </div>
    @endforelse

    
</div>

<div class="stats">
    <div class="staff-stat">
        <div class="mini">Total Active Staff</div>
        <div class="stat-num">{{ $activeStaff }}</div>
        <div class="text-muted">Nhân viên đang hoạt động</div>
    </div>

    <div class="staff-stat">
        <div class="mini">Total Staff</div>
        <div class="stat-num">{{ $totalStaff }}</div>
        <div class="text-muted">Tổng nhân viên hệ thống</div>
    </div>

    <div class="staff-stat">
        <div class="mini">Bookings Today</div>
        <div class="stat-num">{{ $todayBookings }}</div>
        <div class="text-muted">Lịch đặt trong hôm nay</div>
    </div>
</div>

@endsection