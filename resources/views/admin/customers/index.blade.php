@extends('admin.layout')

@section('title', 'Quản lý khách hàng')

@section('content')

<style>
    .cus-page{display:grid;grid-template-columns:1fr 340px;gap:24px}
    .cus-card,.profile-card{background:white;border:1px solid #f0e4e4;border-radius:26px;box-shadow:0 12px 32px rgba(123,85,84,.06)}
    .cus-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
    .cus-title{font-size:34px;font-weight:900;color:#7b5554;font-family:'Noto Serif',serif;margin:0}
    .filter-form{display:flex;gap:10px;align-items:center}
    .filter-form input{border:1px solid #eadede;border-radius:999px;padding:10px 16px;min-width:260px}
    .btn-filter{background:#fff;border:1px solid #eadede;color:#7b5554;border-radius:999px;padding:10px 16px;font-weight:800}
    .btn-main{background:#7b5554;color:white;border:0;border-radius:999px;padding:10px 18px;font-weight:800;text-decoration:none}
    .table-wrap{padding:18px}
    .table th{font-size:11px;text-transform:uppercase;color:#9b8f8f;border-bottom:1px solid #f2e7e7;padding:14px}
    .table td{padding:16px 14px;vertical-align:middle;border-bottom:1px solid #f7eeee}
    .customer-link{text-decoration:none;color:inherit;display:block}
    .customer-row.active{background:#fff6f6;border-left:5px solid #7b5554}
    .avatar{width:42px;height:42px;border-radius:50%;object-fit:cover;border:3px solid #f1dddd}
    .name{font-weight:900;color:#2f2323}
    .muted{font-size:12px;color:#8d8181}
    .pill{display:inline-block;padding:6px 10px;border-radius:999px;background:#f4eeee;color:#7b5554;font-size:11px;font-weight:900}
    .profile-card{padding:28px;position:sticky;top:24px}
    .profile-avatar{width:96px;height:96px;border-radius:50%;object-fit:cover;border:5px solid #f1dddd}
    .profile-name{font-size:24px;font-weight:900;color:#7b5554;font-family:'Noto Serif',serif;margin-top:14px}
    .profile-sub{font-size:13px;color:#8d8181;margin-bottom:20px}
    .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:22px}
    .info-label{font-size:11px;text-transform:uppercase;color:#9b8f8f;font-weight:900}
    .info-value{font-size:13px;color:#2f2323;font-weight:800}
    .history-title{display:flex;justify-content:space-between;align-items:center;margin:28px 0 16px}
    .history-item{display:flex;gap:12px;align-items:center;margin-bottom:16px}
    .history-icon{width:42px;height:42px;border-radius:14px;background:#fff1f1;color:#7b5554;display:flex;align-items:center;justify-content:center}
    .history-name{font-weight:900;color:#2f2323;font-size:13px}
    .history-date{font-size:12px;color:#8d8181}
    .history-price{font-size:13px;font-weight:900;color:#7b5554}
    .status{font-size:10px;background:#dcfce7;color:#15803d;border-radius:999px;padding:4px 8px;font-weight:900}
    .profile-actions{display:flex;gap:12px;margin-top:26px}
    .btn-soft{flex:1;text-align:center;text-decoration:none;border:1px solid #eadede;color:#7b5554;border-radius:14px;padding:12px;font-weight:900}
    .btn-dark{flex:1;text-align:center;text-decoration:none;background:#7b5554;color:white;border-radius:14px;padding:12px;font-weight:900}
    @media(max-width:1100px){.cus-page{grid-template-columns:1fr}.profile-card{position:static}}
</style>

@php
    $avatars = [
        'https://randomuser.me/api/portraits/women/44.jpg',
        'https://randomuser.me/api/portraits/women/65.jpg',
        'https://randomuser.me/api/portraits/women/68.jpg',
        'https://randomuser.me/api/portraits/women/71.jpg',
        'https://randomuser.me/api/portraits/women/72.jpg',
        'https://randomuser.me/api/portraits/men/32.jpg',
    ];
@endphp

<div class="cus-head">
    <h1 class="cus-title">Customers</h1>

    <form method="GET" action="{{ route('admin.customers.index') }}" class="filter-form">
        <input type="text"
               name="keyword"
               value="{{ request('keyword') }}"
               placeholder="Search customers...">

        <button class="btn-filter">
            <i class="bi bi-funnel"></i> Filter
        </button>
    </form>
</div>

<div class="cus-page">

    <div class="cus-card table-wrap">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>tên</th>
                        <th>Thông tin liên hệ</th>
                        <th>Tổng số đặt chỗ</th>
                        <th>Thành viên</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($customers as $customer)
                        @php
                            $avatar = $avatars[$customer->customer_id % count($avatars)];
                            $totalSpent = $customer->bookings_sum_total_amount ?? 0;

                            if ($totalSpent >= 5000000) {
                                $rank = 'PLATINUM';
                            } elseif ($totalSpent >= 2000000) {
                                $rank = 'GOLD MEMBER';
                            } else {
                                $rank = 'REGULAR';
                            }
                        @endphp

                        <tr class="customer-row {{ $selectedCustomer && $selectedCustomer->customer_id == $customer->customer_id ? 'active' : '' }}">
                            <td>
                                <a class="customer-link"
                                   href="{{ route('admin.customers.index', ['customer_id' => $customer->customer_id, 'keyword' => request('keyword')]) }}">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $avatar }}" class="avatar">
                                        <div>
                                            <div class="name">{{ $customer->full_name }}</div>
                                            <div class="muted">
                                                Joined {{ $customer->created_at ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </td>

                            <td>
                                <div>{{ $customer->email ?? 'Đang cập nhật' }}</div>
                                <div class="muted">{{ $customer->phone ?? 'Đang cập nhật' }}</div>
                            </td>

                            <td>
                                <strong style="color:#7b5554;">{{ $customer->bookings_count }}</strong>
                                <div class="muted">Services</div>
                            </td>

                            <td>
                                <span class="pill">{{ $rank }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                Chưa có khách hàng nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 text-muted" style="font-size:13px;">
            <span>Showing 1-{{ $customers->count() }} of {{ $customers->count() }} customers</span>
            <div class="d-flex gap-2">
                <button class="btn-filter">&lt;</button>
                <button class="btn-main">1</button>
            </div>
        </div>
    </div>

    @if($selectedCustomer)
        @php
            $profileAvatar = $avatars[$selectedCustomer->customer_id % count($avatars)];
            $profileTotal = $selectedCustomer->bookings_sum_total_amount ?? 0;
        @endphp

        <div class="profile-card text-center">
            <img src="{{ $profileAvatar }}" class="profile-avatar">

            <div class="profile-name">
                {{ $selectedCustomer->full_name }}
            </div>

            <div class="profile-sub">
                Thành viên PLATINUM · Khách hàng từ {{ $selectedCustomer->created_at ?? 'N/A' }}
            </div>

            <div class="info-grid text-start">
                <div>
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $selectedCustomer->email ?? 'N/A' }}</div>
                </div>

                <div>
                    <div class="info-label">Số điện thoại</div>
                    <div class="info-value">{{ $selectedCustomer->phone ?? 'N/A' }}</div>
                </div>

                <div>
                    <div class="info-label">Địa chỉ</div>
                    <div class="info-value">{{ $selectedCustomer->address ?? 'N/A' }}</div>
                </div>

                <div>
                    <div class="info-label">Tổng chi tiêu</div>
                    <div class="info-value">{{ number_format($profileTotal) }}đ</div>
                </div>
            </div>

            <div class="history-title">
                <strong style="color:#7b5554;">Lịch sử đặt lịch</strong>
                <span class="muted">Xem tất cả</span>
            </div>

            <div class="text-start">
                @forelse($selectedCustomer->bookings->take(3) as $booking)
                    @php
                        $serviceName = optional(optional($booking->bookingDetails->first())->service)->service_name ?? 'Dịch vụ';
                    @endphp

                    <div class="history-item">
                        <div class="history-icon">
                            <i class="bi bi-flower1"></i>
                        </div>

                        <div class="flex-grow-1">
                            <div class="history-name">{{ \Illuminate\Support\Str::limit($serviceName, 18) }}</div>
                            <div class="history-date">
                                {{ $booking->booking_date }} · {{ $booking->booking_time }}
                            </div>
                        </div>

                        <div class="text-end">
                            <div class="history-price">{{ number_format($booking->total_amount) }}đ</div>
                            <span class="status">Hoàn thành</span>
                        </div>
                    </div>
                @empty
                    <div class="text-muted text-start">
                        Khách hàng chưa có lịch đặt.
                    </div>
                @endforelse
            </div>

            <div class="profile-actions">
                <a href="#" class="btn-dark">Sửa hồ sơ</a>
                <a href="{{ route('admin.bookings.index') }}" class="btn-soft">Tạo đặt lịch mới</a>
            </div>
        </div>
    @endif

</div>

@endsection