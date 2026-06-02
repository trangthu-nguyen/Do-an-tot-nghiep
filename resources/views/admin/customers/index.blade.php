@extends('admin.layout')

@section('title', 'Quản lý khách hàng')

@section('content')

<style>
    .cus-page{display:grid;grid-template-columns:1fr 340px;gap:24px}
    .cus-card,.profile-card,.summary-card{background:white;border:1px solid #f0e4e4;border-radius:26px;box-shadow:0 12px 32px rgba(123,85,84,.06)}
    .cus-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
    .cus-title{font-size:34px;font-weight:900;color:#7b5554;font-family:'Noto Serif',serif;margin:0}
    .filter-form{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
    .filter-form input,.filter-form select{border:1px solid #eadede;border-radius:999px;padding:10px 16px;min-width:220px}
    .btn-filter{background:#fff;border:1px solid #eadede;color:#7b5554;border-radius:999px;padding:10px 16px;font-weight:800}
    .btn-main{background:#7b5554;color:white;border:0;border-radius:999px;padding:10px 18px;font-weight:800;text-decoration:none}
    .summary-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px}
    .summary-card{padding:18px}
    .summary-label{font-size:12px;text-transform:uppercase;color:#9b8f8f;font-weight:900}
    .summary-name{font-size:18px;color:#7b5554;font-weight:900;margin-top:6px}
    .summary-value{font-size:13px;color:#2f2323;font-weight:800;margin-top:4px}
    .table-wrap{padding:18px}
    .table th{font-size:11px;text-transform:uppercase;color:#9b8f8f;border-bottom:1px solid #f2e7e7;padding:14px}
    .table td{padding:16px 14px;vertical-align:middle;border-bottom:1px solid #f7eeee}
    .customer-link{text-decoration:none;color:inherit;display:block}
    .customer-row.active{background:#fff6f6;border-left:5px solid #7b5554}
    .avatar{width:42px;height:42px;border-radius:50%;object-fit:cover;border:3px solid #f1dddd}
    .name{font-weight:900;color:#2f2323}
    .muted{font-size:12px;color:#8d8181}
    .pill{display:inline-block;padding:6px 10px;border-radius:999px;background:#f4eeee;color:#7b5554;font-size:11px;font-weight:900}
    .pill-vip{background:#fff4d6;color:#a16207}
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
    @media(max-width:1100px){.cus-page{grid-template-columns:1fr}.profile-card{position:static}.summary-grid{grid-template-columns:1fr}}
</style>

<div class="cus-head">
    <h1 class="cus-title">Khách hàng</h1>

    <form method="GET" action="{{ route('admin.customers.index') }}" class="filter-form">
        <input type="text"
               name="keyword"
               value="{{ request('keyword') }}"
               placeholder="Tìm tên, email, số điện thoại...">

        <select name="sort">
            <option value="">Sắp xếp mặc định</option>
            <option value="booking_desc" {{ request('sort') == 'booking_desc' ? 'selected' : '' }}>Nhiều lượt sử dụng nhất</option>
            <option value="spent_desc" {{ request('sort') == 'spent_desc' ? 'selected' : '' }}>Đóng góp nhiều nhất</option>
        </select>

        <button class="btn-filter">
            <i class="bi bi-funnel"></i> Lọc
        </button>
    </form>
</div>

<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-label">Khách sử dụng dịch vụ nhiều nhất</div>
        <div class="summary-name">{{ $topByBookings->full_name ?? 'Chưa có dữ liệu' }}</div>
        <div class="summary-value">{{ $topByBookings->bookings_count ?? 0 }} lượt đặt lịch</div>
    </div>

    <div class="summary-card">
        <div class="summary-label">Khách đóng góp doanh thu cao nhất</div>
        <div class="summary-name">{{ $topBySpent->full_name ?? 'Chưa có dữ liệu' }}</div>
        <div class="summary-value">{{ number_format($topBySpent->paid_total ?? 0,0,',','.') }}đ đã thanh toán</div>
    </div>
</div>

<div class="cus-page">

    <div class="cus-card table-wrap">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Thông tin liên hệ</th>
                        <th>Lượt sử dụng</th>
                        <th>Tổng chi tiêu</th>
                        <th>Phân loại</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($customers as $customer)
                        <tr class="customer-row {{ $selectedCustomer && $selectedCustomer->customer_id == $customer->customer_id ? 'active' : '' }}">
                            <td>
                                <a class="customer-link"
                                   href="{{ route('admin.customers.index', ['customer_id' => $customer->customer_id, 'keyword' => request('keyword'), 'sort' => request('sort')]) }}">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $customer->avatar_url }}"
                                             class="avatar"
                                             alt="avatar khách hàng">

                                        <div>
                                            <div class="name">{{ $customer->full_name }}</div>
                                            <div class="muted">ID: #{{ $customer->customer_id }}</div>
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
                                <div class="muted">Lịch đặt</div>
                            </td>

                            <td>
                                <strong>{{ number_format($customer->paid_total ?? 0,0,',','.') }}đ</strong>
                                <div class="muted">Đã thanh toán</div>
                            </td>

                            <td>
                                <span class="pill {{ str_contains($customer->rank_label, 'VIP') ? 'pill-vip' : '' }}">
                                    {{ $customer->rank_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                Chưa có khách hàng nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 text-muted" style="font-size:13px;">
            <span>Hiển thị {{ $customers->count() }} khách hàng</span>
        </div>
    </div>

    @if($selectedCustomer)
        <div class="profile-card text-center">
            <img src="{{ $selectedCustomer->avatar_url }}"
                 class="profile-avatar"
                 alt="avatar khách hàng">

            <div class="profile-name">
                {{ $selectedCustomer->full_name }}
            </div>

            <div class="profile-sub">
                {{ $selectedCustomer->rank_label ?? 'Khách hàng thường' }}
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
                    <div class="info-label">Lượt đặt</div>
                    <div class="info-value">{{ $selectedCustomer->bookings_count ?? 0 }}</div>
                </div>

                <div>
                    <div class="info-label">Tổng chi tiêu</div>
                    <div class="info-value">{{ number_format($selectedCustomer->paid_total ?? 0,0,',','.') }}đ</div>
                </div>
            </div>

            @if(str_contains($selectedCustomer->rank_label ?? '', 'VIP'))
                <div class="alert alert-warning rounded-4 mt-4 text-start">
                    <strong>Đề xuất:</strong> Khách hàng có giá trị cao, có thể tặng voucher tri ân.
                </div>
            @endif

            <div class="history-title">
                <strong style="color:#7b5554;">Lịch sử đặt lịch</strong>
                <span class="muted">Gần đây</span>
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
                            <div class="history-name">
                                {{ \Illuminate\Support\Str::limit($serviceName, 18) }}
                            </div>

                            <div class="history-date">
                                {{ $booking->booking_date }} · {{ $booking->booking_time }}
                            </div>
                        </div>

                        <div class="text-end">
                            <div class="history-price">
                                {{ number_format($booking->total_amount) }}đ
                            </div>

                            <span class="status">
                                {{ $booking->status == 3 ? 'Hoàn thành' : 'Đang xử lý' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-muted text-start">
                        Khách hàng chưa có lịch đặt.
                    </div>
                @endforelse
            </div>
        </div>
    @endif

</div>

@endsection