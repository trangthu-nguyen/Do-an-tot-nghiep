@extends('admin.layout')

@section('title', 'Quản lý đặt lịch')

@section('content')

<style>
    .bk-top,
    .bk-card,
    .bk-info{
        background:white;
        border:1px solid #f0e4e4;
        border-radius:22px;
        box-shadow:0 12px 32px rgba(123,85,84,.06);
    }

    .bk-top{
        padding:24px;
        margin-bottom:20px;
        display:flex;
        justify-content:space-between;
        gap:18px;
        align-items:center;
    }

    .bk-label{
        font-size:13px;
        font-weight:700;
        color:#9b8f8f;
        margin-bottom:8px;
    }

    .bk-heading{
        font-size:30px;
        font-weight:900;
        color:#2f2323;
        margin:6px 0;
    }

    .bk-sub{
        color:#7d7272;
        font-size:14px;
        margin:0;
    }

    .btn-bk{
        background:#7b5554;
        color:white;
        border-radius:14px;
        padding:11px 16px;
        text-decoration:none;
        font-weight:800;
        border:0;
    }

    .btn-bk:hover{
        background:#684847;
        color:white;
    }

    /* ===== 4 ô thống kê ===== */
    .stats{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:20px;
        margin-bottom:24px;
    }

    .stat{
        padding:24px;
        min-height:125px;
        display:flex;
        flex-direction:column;
        justify-content:center;
        transition:.3s;
    }

    .stat:hover{
        transform:translateY(-4px);
    }

    .stat-num{
        font-size:38px;
        font-weight:900;
        color:#7b5554;
        line-height:1;
        margin-bottom:10px;
    }

    .stat-note{
        font-size:13px;
        color:#9b8f8f;
        font-weight:600;
    }

    /* ===== Filter ===== */
    .filter-box{
        padding:16px 20px;
        margin-bottom:20px;
    }

    .filter-form{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
        align-items:center;
    }

    .filter-form input,
    .filter-form select{
        border-radius:14px;
        border:1px solid #eadede;
        padding:10px 14px;
        min-width:180px;
    }

    .filter-form input[name="keyword"]{
        min-width:280px;
    }

    .btn-filter{
        background:#7b5554;
        color:white;
        border:0;
        border-radius:14px;
        padding:10px 16px;
        font-weight:800;
    }

    .btn-reset{
        background:#f4eeee;
        color:#7b5554;
        border-radius:14px;
        padding:10px 16px;
        font-weight:800;
        text-decoration:none;
    }

    /* ===== Layout ===== */
    .bk-layout{
        display:grid;
        grid-template-columns:1fr 300px;
        gap:20px;
    }

    .table-title{
        padding:20px 24px;
        border-bottom:1px solid #f1e7e7;
        font-size:20px;
        font-weight:900;
        color:#2f2323;
    }

    .table th{
        font-size:12px;
        text-transform:uppercase;
        color:#8b8080;
        padding:15px 16px;
    }

    .table td{
        padding:16px;
        vertical-align:middle;
        border-bottom:1px solid #f7eeee;
    }

    .customer-name,
    .staff-name{
        font-weight:900;
        color:#2f2323;
    }

    .small-muted{
        font-size:12px;
        color:#8d8181;
    }

    .service-pill{
        display:inline-block;
        background:#f4eeee;
        color:#7b5554;
        padding:6px 10px;
        border-radius:999px;
        font-size:12px;
        font-weight:800;
        margin:2px;
    }

    .status{
        padding:6px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
    }

    .s0{
        background:#fef3c7;
        color:#b45309;
    }

    .s1{
        background:#dbeafe;
        color:#1d4ed8;
    }

    .s2{
        background:#ede9fe;
        color:#7c3aed;
    }

    .s3{
        background:#dcfce7;
        color:#15803d;
    }

    .s4{
        background:#fee2e2;
        color:#b91c1c;
    }

    .action-btn{
        border:0;
        background:transparent;
        color:#7b5554;
        font-size:17px;
        text-decoration:none;
    }

    .side-box{
        padding:20px;
        margin-bottom:18px;
    }

    .staff-row{
        display:flex;
        align-items:center;
        justify-content:space-between;
        border-bottom:1px solid #f1e7e7;
        padding:10px 0;
    }

    .avatar{
        width:34px;
        height:34px;
        border-radius:50%;
        object-fit:cover;
        margin-right:8px;
    }

    /* ===== Responsive ===== */

    @media (max-width:1200px){
        .stats{
            grid-template-columns:repeat(2,1fr);
        }
    }

    @media (max-width:991px){

        .bk-top{
            flex-direction:column;
            align-items:flex-start;
        }

        .bk-layout{
            grid-template-columns:1fr;
        }

        .stats{
            grid-template-columns:1fr;
        }

        .filter-form input,
        .filter-form select{
            width:100%;
            min-width:100%;
        }
    }
</style>
<div class="bk-top">
    <div>
        <h1 class="bk-heading">Lịch đặt dịch vụ</h1>
        <p class="bk-sub">Theo dõi lịch đặt, trạng thái thanh toán, nhân viên phụ trách và tiến độ thực hiện.</p>
    </div>
</div>

<div class="stats">
    <div class="bk-card stat">
        <div class="bk-label">Tổng lịch</div>
        <div class="stat-num">{{ $totalBookings }}</div>
        <div class="stat-note">Toàn bộ lịch đặt</div>
    </div>

    <div class="bk-card stat">
        <div class="bk-label">Chờ xác nhận</div>
        <div class="stat-num">{{ $pendingBookings }}</div>
        <div class="stat-note">Cần xử lý</div>
    </div>

    <div class="bk-card stat">
        <div class="bk-label">Lịch hôm nay</div>
        <div class="stat-num">{{ $todayBookings }}</div>
        <div class="stat-note">Cần thực hiện</div>
    </div>

    <div class="bk-card stat">
        <div class="bk-label">Lịch hủy</div>
        <div class="stat-num text-danger">{{ $cancelledBookings }}</div>
        <div class="stat-note">Đã bị hủy</div>
    </div>

    
</div>

<div class="bk-card filter-box">
    <form method="GET" action="{{ route('admin.bookings.index') }}" class="filter-form">
        <input type="text"
               name="keyword"
               value="{{ request('keyword') }}"
               placeholder="Tìm mã booking, tên khách, SĐT, nhân viên, dịch vụ...">

        <select name="status">
            <option value="">Tất cả trạng thái</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Chờ xác nhận</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đã xác nhận</option>
            <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Đang thực hiện</option>
            <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Hoàn thành</option>
            <option value="4" {{ request('status') === '4' ? 'selected' : '' }}>Đã hủy</option>
        </select>

        <input type="date"
               name="booking_date"
               value="{{ request('booking_date') }}">

        <button type="submit" class="btn-filter">
            <i class="bi bi-search"></i> Tìm kiếm
        </button>

        <a href="{{ route('admin.bookings.index') }}" class="btn-reset">
            Làm mới
        </a>
    </form>
</div>

<div class="bk-layout">

    <div class="bk-card overflow-hidden">
        <div class="table-title">
            Booking Details
            <div class="small-muted mt-1">
                Hiển thị {{ $bookings->count() }} lịch đặt
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Booking</th>
                        <th>Khách hàng</th>
                        <th>Dịch vụ</th>
                        <th>Nhân viên</th>
                        <th>Ngày & giờ</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Chi tiết</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bookings as $booking)
                        @php
                            $statusText = [
                                0 => 'Chờ xác nhận',
                                1 => 'Đã xác nhận',
                                2 => 'Đang thực hiện',
                                3 => 'Hoàn thành',
                                4 => 'Đã hủy'
                            ][$booking->status] ?? 'Unknown';

                            $statusClass = 's' . $booking->status;
                        @endphp

                        <tr>
                            <td>
                                <strong>#{{ $booking->booking_id }}</strong>
                                <div class="small-muted">{{ $booking->payment->payment_method ?? 'cod' }}</div>
                            </td>

                            <td>
                                <div class="customer-name">
                                    {{ $booking->customer->full_name ?? 'Chưa có' }}
                                </div>
                                <div class="small-muted">
                                    {{ $booking->customer->phone ?? 'Chưa có SĐT' }}
                                </div>
                            </td>

                            <td>
                                @foreach($booking->bookingDetails as $detail)
                                    <span class="service-pill">
                                        {{ $detail->service->service_name ?? 'N/A' }}
                                    </span>
                                @endforeach
                            </td>

                            <td>
                                @if($booking->staff)
                                    <div class="staff-name">
                                        {{ $booking->staff->full_name }}
                                    </div>
                                @else
                                    <span class="text-danger small-muted">Chưa phân công</span>
                                @endif
                            </td>

                            <td>
                                <strong>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</strong>
                                <div class="small-muted">{{ $booking->booking_time }}</div>
                            </td>

                            <td>
                                <span class="status {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>

                            <td class="text-end">
                                <a href="{{ route('admin.bookings.show', $booking->booking_id) }}"
                                   class="action-btn"
                                   title="Xem chi tiết">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                Không tìm thấy booking phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 text-muted" style="font-size:13px;">
            Hiển thị {{ $bookings->count() }} lịch đặt
        </div>
    </div>

    <div>
        <div class="bk-info side-box">
            <h5 class="fw-bold mb-3" style="color:#7b5554;">Hoạt động của nhân viên</h5>

            @forelse($staffs as $staff)
                @php
                    $staffPortraits = [
                        'https://randomuser.me/api/portraits/women/44.jpg',
                        'https://randomuser.me/api/portraits/women/65.jpg',
                        'https://randomuser.me/api/portraits/women/68.jpg',
                        'https://randomuser.me/api/portraits/women/71.jpg',
                        'https://randomuser.me/api/portraits/women/72.jpg',
                        'https://randomuser.me/api/portraits/women/76.jpg',
                        'https://randomuser.me/api/portraits/women/79.jpg',
                        'https://randomuser.me/api/portraits/women/81.jpg',
                    ];

                    $staffAvatar = $staffPortraits[$staff->staff_id % count($staffPortraits)];
                @endphp

                <div class="staff-row">
                    <div class="d-flex align-items-center">
                        <img src="{{ $staffAvatar }}" class="avatar">
                        <div>
                            <div class="fw-bold">{{ $staff->full_name }}</div>
                            <div class="small-muted">{{ $staff->skill ?? 'Nhân viên' }}</div>
                        </div>
                    </div>

                    @if($staff->status == 1)
                        <span class="text-success fw-bold" style="font-size:12px;">Hoạt động</span>
                    @else
                        <span class="text-danger fw-bold" style="font-size:12px;">Bận</span>
                    @endif
                </div>
            @empty
                <div class="text-muted">Chưa có nhân viên.</div>
            @endforelse
        </div>

        <div class="bk-info side-box">
            <h5 class="fw-bold" style="color:#7b5554;">Gợi ý quản lý</h5>
            <p class="mb-0 text-muted" style="font-size:14px;line-height:1.7;">
                Có thể tìm kiếm theo mã booking, khách hàng, số điện thoại, nhân viên, dịch vụ hoặc lọc theo trạng thái/ngày đặt.
            </p>
        </div>
    </div>

</div>

@endsection