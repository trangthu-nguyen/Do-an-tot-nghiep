@extends('admin.layout')

@section('title', 'Quản lý thanh toán')

@section('content')

<style>
    :root{--primary:#7b5554;--dark:#2f2323;--muted:#8a7e7e;--border:#eadede;--soft:#faf7f7}

    .pay-sub{color:var(--muted);font-weight:600;margin-bottom:24px}
    .stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:24px}
    .stat-card,.filter-card,.table-card{background:white;border:1px solid var(--border);border-radius:24px;box-shadow:0 12px 32px rgba(123,85,84,.06)}
    .stat-card{padding:22px}
    .stat-label{font-size:12px;font-weight:900;color:#9b8f8f;text-transform:uppercase;margin-bottom:8px}
    .stat-value{font-size:26px;font-weight:900;color:var(--primary)}
    .stat-note{font-size:12px;color:#16a34a;font-weight:800;margin-top:8px}

    .pending-card{background:linear-gradient(135deg,#7b5554,#9a6d6c);color:white}
    .pending-card .stat-label,.pending-card .stat-note{color:rgba(255,255,255,.78)}
    .pending-card .stat-value{color:white}

    .filter-card{padding:18px;margin-bottom:22px}
    .filter-card select,.filter-card input{border-radius:14px;border:1px solid var(--border);font-weight:700}
    .btn-filter{border:0;background:var(--primary);color:white;border-radius:14px;padding:10px 18px;font-weight:900}
    .btn-reset{background:#f1eeee;color:#6f6161;border-radius:14px;padding:10px 18px;font-weight:900;text-decoration:none}

    .table-card{overflow:hidden}
    .card-head{display:flex;justify-content:space-between;align-items:center;padding:22px 24px;border-bottom:1px solid #f3eeee}
    .card-title{font-size:22px;font-weight:900;color:var(--dark);font-family:'Noto Serif',serif}
    .table th{font-size:11px;text-transform:uppercase;color:#9b8f8f;padding:16px;border-bottom:1px solid #f3eeee}
    .table td{padding:16px;vertical-align:middle;border-bottom:1px solid #f7eeee}

    .transaction{font-weight:900;color:#7b5554}
    .booking-code{font-weight:900;color:#2f2323}
    .amount{font-weight:900;color:#2f2323}
    .method{font-weight:800;color:#6f6464;font-size:13px}
    .date{font-size:13px;color:#8a7e7e;font-weight:700}

    .badge-pay{border-radius:999px;padding:7px 12px;font-size:11px;font-weight:900}
    .paid{background:#dcfce7;color:#15803d}
    .pending{background:#fff4d6;color:#a16207}
    .unpaid{background:#ffe4e6;color:#be123c}

    .select-status{border-radius:12px;border:1px solid var(--border);font-size:13px;font-weight:700;padding:8px}
    .btn-save{border:0;background:var(--primary);color:white;border-radius:12px;padding:8px 12px;font-size:13px;font-weight:900}

    @media(max-width:991px){.stat-grid{grid-template-columns:1fr}}
</style>

<div class="pay-sub">
    Quản lý giao dịch thanh toán, tìm kiếm, lọc và xác nhận các khoản thanh toán của khách hàng.
</div>

@if(session('success'))
    <div class="alert alert-success rounded-4">{{ session('success') }}</div>
@endif

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Doanh thu hôm nay</div>
        <div class="stat-value">{{ number_format($todayRevenue,0,',','.') }}đ</div>
        <div class="stat-note">Đã thanh toán hôm nay</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Doanh thu tháng này</div>
        <div class="stat-value">{{ number_format($monthRevenue,0,',','.') }}đ</div>
        <div class="stat-note">Tính theo ngày thanh toán</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Doanh thu trong năm</div>
        <div class="stat-value">{{ number_format($yearRevenue,0,',','.') }}đ</div>
        <div class="stat-note">Tổng giao dịch đã thanh toán</div>
    </div>

    <div class="stat-card pending-card">
        <div class="stat-label">Doanh thu chờ xác nhận</div>
        <div class="stat-value">{{ number_format($pendingAmount,0,',','.') }}đ</div>
        <div class="stat-note">{{ $pendingCount }} giao dịch chưa được xử lý</div>
    </div>
</div>

<form method="GET" action="{{ route('admin.payments.index') }}" class="filter-card">
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-bold" style="color:#7b5554;">Tìm kiếm</label>
            <input type="text"
                   name="keyword"
                   class="form-control"
                   value="{{ request('keyword') }}"
                   placeholder="Mã booking, tên khách, SĐT...">
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold" style="color:#7b5554;">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="">Tất cả</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold" style="color:#7b5554;">Phương thức</label>
            <select name="method" class="form-select">
                <option value="">Tất cả</option>
                <option value="cod" {{ request('method') == 'cod' ? 'selected' : '' }}>COD</option>
                <option value="momo" {{ request('method') == 'momo' ? 'selected' : '' }}>MoMo</option>
                <option value="vnpay" {{ request('method') == 'vnpay' ? 'selected' : '' }}>VNPAY</option>
                <option value="bank" {{ request('method') == 'bank' ? 'selected' : '' }}>Chuyển khoản</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold" style="color:#7b5554;">Từ ngày</label>
            <input type="date"
                   name="from_date"
                   class="form-control"
                   value="{{ request('from_date') }}">
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold" style="color:#7b5554;">Đến ngày</label>
            <input type="date"
                   name="to_date"
                   class="form-control"
                   value="{{ request('to_date') }}">
        </div>

        <div class="col-md-1">
            <button class="btn-filter w-100">
                <i class="bi bi-search"></i>
            </button>
        </div>

        <div class="col-md-12">
            <a href="{{ route('admin.payments.index') }}" class="btn-reset d-inline-block">
                Đặt lại bộ lọc
            </a>
        </div>
    </div>
</form>

<div class="table-card">
    <div class="card-head">
        <div class="card-title">Danh sách giao dịch</div>
        <div class="text-muted fw-bold" style="font-size:13px;">
            {{ $payments->count() }} giao dịch
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>ID giao dịch</th>
                    <th>Mã booking</th>
                    <th>Khách hàng</th>
                    <th>Số tiền</th>
                    <th>Phương thức</th>
                    <th>Ngày thanh toán</th>
                    <th>Trạng thái</th>
                    <th width="230">Cập nhật</th>
                </tr>
            </thead>

            <tbody>
                @forelse($payments as $payment)
                    @php
                        $method = strtolower($payment->payment_method ?? '');
                        $status = strtolower($payment->payment_status ?? 'unpaid');

                        $methodText = match($method) {
                            'cod' => 'Thanh toán khi hoàn thành',
                            'momo' => 'MoMo',
                            'vnpay' => 'VNPAY',
                            'bank' => 'Chuyển khoản',
                            'online' => 'Online',
                            default => $payment->payment_method ?? '---',
                        };

                        $statusText = match($status) {
                            'paid' => 'Đã thanh toán',
                            'pending' => 'Chờ xác nhận',
                            default => 'Chưa thanh toán',
                        };

                        $badgeClass = match($status) {
                            'paid' => 'paid',
                            'pending' => 'pending',
                            default => 'unpaid',
                        };
                    @endphp

                    <tr>
                        <td class="transaction">#TXN-{{ str_pad($payment->payment_id, 4, '0', STR_PAD_LEFT) }}</td>

                        <td class="booking-code">#BK-{{ $payment->booking_id }}</td>

                        <td>
                            <div class="fw-bold">
                                {{ $payment->booking->customer->full_name ?? 'Không rõ' }}
                            </div>
                            <div class="text-muted small">
                                {{ $payment->booking->customer->phone ?? '' }}
                            </div>
                        </td>

                        <td class="amount">
                            {{ number_format($payment->amount,0,',','.') }}đ
                        </td>

                        <td class="method">
                            <i class="bi bi-credit-card"></i>
                            {{ $methodText }}
                        </td>

                        <td class="date">
                            {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : '—' }}
                        </td>

                        <td>
                            <span class="badge-pay {{ $badgeClass }}">
                                {{ $statusText }}
                            </span>
                        </td>

                        <td>
                            <form action="{{ route('admin.payments.updateStatus', $payment->payment_id) }}" method="POST">
                                @csrf

                                <div class="d-flex gap-2">
                                    <select name="payment_status" class="select-status flex-fill">
                                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>
                                            Chờ xác nhận
                                        </option>
                                        <option value="paid" {{ $status == 'paid' ? 'selected' : '' }}>
                                            Đã thanh toán
                                        </option>
                                        <option value="unpaid" {{ $status == 'unpaid' ? 'selected' : '' }}>
                                            Chưa thanh toán
                                        </option>
                                    </select>

                                    <button class="btn-save">
                                        Lưu
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5 fw-bold">
                            Không có giao dịch phù hợp.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection