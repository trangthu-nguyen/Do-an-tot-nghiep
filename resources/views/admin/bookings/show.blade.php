@extends('admin.layout')

@section('title', 'Chi tiết lịch hẹn')

@section('content')

@php
    $statusText = [
        0 => 'Chờ xác nhận',
        1 => 'Đã xác nhận',
        2 => 'Đang thực hiện',
        3 => 'Hoàn thành',
        4 => 'Đã hủy',
    ][$booking->status] ?? 'Không rõ';

    $statusClass = [
        0 => 'warning',
        1 => 'success',
        2 => 'primary',
        3 => 'success',
        4 => 'danger',
    ][$booking->status] ?? 'secondary';

    $paymentMethod = $booking->payment->payment_method ?? 'cod';

    $paymentText = match($paymentMethod) {
        'cod' => 'Thanh toán khi hoàn thành',
        'momo' => 'Ví MoMo',
        'vnpay' => 'VNPAY',
        'bank' => 'Chuyển khoản ngân hàng',
        default => $paymentMethod,
    };

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

$staffAvatar = $booking->staff
    ? $staffPortraits[$booking->staff->staff_id % count($staffPortraits)]
    : asset('uploads/avatar/default-avatar.png');
@endphp

<style>
    .booking-page{color:#2f2323}
    .page-head{display:flex;justify-content:space-between;gap:18px;align-items:center;margin-bottom:24px}
    .mini{font-size:12px;font-weight:900;color:#9b8f8f}
    .title{font-size:32px;font-weight:900;margin:6px 0;color:#2f2323}
    .actions{display:flex;gap:10px;flex-wrap:wrap}
    .btn-soft,.btn-main{border:0;border-radius:999px;padding:10px 16px;font-weight:900;text-decoration:none}
    .btn-soft{background:white;color:#7b5554;border:1px solid #eadede}
    .btn-main{background:#7b5554;color:white}
    .btn-main:hover{background:#684847;color:white}
    .grid{display:grid;grid-template-columns:1fr 330px;gap:22px}
    .left-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
    .cardx{background:white;border:1px solid #f0e4e4;border-radius:24px;box-shadow:0 12px 32px rgba(123,85,84,.06);padding:22px}
    .card-title{font-size:13px;text-transform:uppercase;color:#9b8f8f;font-weight:900;margin-bottom:16px}
    .customer-box{display:flex;gap:14px}
    .avatar{width:58px;height:58px;border-radius:50%;object-fit:cover;border:4px solid #ffe1e0}
    .name{font-size:18px;font-weight:900;color:#2f2323}
    .muted{color:#7d7272;font-size:13px}
    .note{background:#f7f2f2;border-radius:16px;padding:14px;margin-top:14px;font-size:13px;color:#5f5656;line-height:1.7}
    .service-row{border-bottom:1px solid #f3eeee;padding-bottom:12px;margin-bottom:12px}
    .service-row:last-child{border-bottom:0;margin-bottom:0;padding-bottom:0}
    .price{font-weight:900;color:#ba1a1a}
    .staff-card{margin-top:18px}
    .staff-line{display:flex;gap:14px;align-items:center}
    .rating{background:#fff1f1;color:#7b5554;border-radius:16px;padding:12px;text-align:center;font-weight:900}
    .activity{margin-top:18px}
    .activity-item{display:flex;gap:12px;padding:12px 0;border-bottom:1px solid #f3eeee}
    .activity-dot{width:32px;height:32px;border-radius:50%;background:#ffe1e0;color:#7b5554;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .payment-card{background:#2f2929;color:white;border-radius:24px;padding:24px;box-shadow:0 18px 40px rgba(0,0,0,.18)}
    .payment-title{font-size:15px;text-transform:uppercase;color:#d8cccc;font-weight:900;margin-bottom:18px}
    .pay-row{display:flex;justify-content:space-between;margin-bottom:12px;color:#e9dddd;font-size:14px}
    .pay-total{display:flex;justify-content:space-between;border-top:1px solid rgba(255,255,255,.15);padding-top:16px;margin-top:16px;font-size:18px;font-weight:900}
    .pay-status{background:#dcfce7;color:#15803d;border-radius:999px;padding:6px 12px;font-size:12px;font-weight:900}
    .pay-status.pending{background:#fef3c7;color:#b45309}
    .side-card{margin-top:18px}
    .form-select{border-radius:14px;border:1px solid #eadede;padding:11px}
    .quick-btn{display:block;width:100%;background:white;border:1px solid #eadede;border-radius:14px;padding:12px;text-align:left;margin-bottom:10px;color:#5f5656;font-weight:800;text-decoration:none}
    .status-badge{border-radius:999px;padding:8px 14px;font-weight:900}
    @media(max-width:991px){.grid,.left-grid{grid-template-columns:1fr}.page-head{flex-direction:column;align-items:flex-start}}
</style>

<div class="booking-page">

    <div class="page-head">
        <div>
            <div class="mini">
                ĐẶT CHỖ #BH-{{ $booking->booking_id }}
                <span class="badge bg-{{ $statusClass }} ms-2">{{ $statusText }}</span>
            </div>

            <h1 class="title">Chi tiết lịch hẹn</h1>
        </div>

        <div class="actions">
            <a href="{{ route('admin.bookings.index') }}" class="btn-soft">
                ← Quay lại
            </a>

            <button type="button" class="btn-soft" data-bs-toggle="modal" data-bs-target="#statusModal">
                Xử lý
            </button>

            <button type="button" class="btn-main" data-bs-toggle="modal" data-bs-target="#staffModal">
                Phân công
            </button>
        </div>
    </div>

    <div class="grid">

        <div>
            <div class="left-grid">
                <div class="cardx">
                    <div class="card-title">Thông tin khách hàng</div>

                    <div class="customer-box">
                        <img src="{{ $booking->customer->avatar_url ?? asset('uploads/avatar/default-avatar.png') }}"
                            class="avatar">

                        <div>
                            <div class="name">{{ $booking->customer->full_name ?? 'Khách hàng' }}</div>
                            <div class="muted">
                                <i class="bi bi-telephone"></i>
                                {{ $booking->customer->phone ?? 'Chưa có SĐT' }}
                            </div>
                            <div class="muted">
                                <i class="bi bi-envelope"></i>
                                {{ $booking->customer->email ?? 'Chưa có email' }}
                            </div>
                        </div>
                    </div>

                    <div class="note">
                        <strong>Địa chỉ:</strong><br>
                        {{ $booking->address ?? 'Chưa cập nhật địa chỉ' }}
                    </div>

                    @if($booking->note)
                        <div class="note">
                            <strong>Ghi chú:</strong><br>
                            {{ $booking->note }}
                        </div>
                    @endif
                </div>

                <div class="cardx">
                    <div class="card-title">Chi tiết dịch vụ</div>

                    @foreach($booking->bookingDetails as $detail)
                        <div class="service-row">
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <div class="name" style="font-size:17px;">
                                        {{ $detail->service->service_name ?? 'N/A' }}
                                    </div>

                                    <div class="muted">
                                        {{ $detail->service->description ?? 'Không có mô tả' }}
                                    </div>
                                </div>

                                <div class="price">
                                    {{ number_format($detail->price) }}đ
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="row mt-3 g-2">
                        <div class="col-6">
                            <div class="note mb-0">
                                <strong>Ngày:</strong><br>
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="note mb-0">
                                <strong>Giờ:</strong><br>
                                {{ $booking->booking_time }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cardx staff-card">
                <div class="card-title">Nhân viên phụ trách</div>

                @if($booking->staff)
                    <div class="d-flex justify-content-between align-items-center gap-3">
                        <div class="staff-line">
                            <img src="{{ $staffAvatar }}" class="avatar">
                            <div>
                                <div class="name">{{ $booking->staff->full_name }}</div>
                                <div class="muted">{{ $booking->staff->skill ?? 'Nhân viên làm đẹp' }}</div>
                                <div class="muted">
                                    <i class="bi bi-telephone"></i>
                                    {{ $booking->staff->phone ?? 'Chưa có SĐT' }}
                                </div>
                            </div>
                        </div>

                        <div class="rating">
                            4.9 ★
                            <div class="muted">Đánh giá</div>
                        </div>
                    </div>
                @else
                    <div class="text-muted">
                        Lịch này chưa được phân công nhân viên.
                    </div>

                    <button type="button" class="btn-main mt-3" data-bs-toggle="modal" data-bs-target="#staffModal">
                        Phân công ngay
                    </button>
                @endif
            </div>

            <div class="cardx activity">
                <div class="card-title">Lịch sử hoạt động</div>

                <div class="activity-item">
                    <div class="activity-dot">
                        <i class="bi bi-plus"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Lịch hẹn được tạo</div>
                        <div class="muted">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                            · {{ $booking->booking_time }}
                        </div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-dot">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Thanh toán: {{ $paymentText }}</div>
                        <div class="muted">
                            Trạng thái:
                            {{ $booking->payment && $booking->payment->payment_status == 'paid' ? 'Đã thanh toán' : 'Chờ thanh toán' }}
                        </div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-dot">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <div>
                        <div class="fw-bold">
                            {{ $booking->staff ? 'Đã phân công nhân viên' : 'Chưa phân công nhân viên' }}
                        </div>
                        <div class="muted">
                            {{ $booking->staff->full_name ?? 'Đang chờ xử lý' }}
                        </div>
                    </div>
                </div>

                <div class="activity-item border-0">
                    <div class="activity-dot">
                        <i class="bi bi-flag"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Trạng thái hiện tại</div>
                        <div class="muted">{{ $statusText }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="payment-card">
                <div class="payment-title">Tóm tắt thanh toán</div>

                <div class="pay-row">
                    <span>Phí dịch vụ</span>
                    <strong>{{ number_format($booking->total_amount) }}đ</strong>
                </div>

                <div class="pay-row">
                    <span>Phụ phí</span>
                    <strong>0đ</strong>
                </div>

                <div class="pay-row">
                    <span>Giảm giá</span>
                    <strong>0đ</strong>
                </div>

                <div class="pay-total">
                    <span>Tổng cộng</span>
                    <span>{{ number_format($booking->total_amount) }}đ</span>
                </div>

                <div class="mt-4 p-3" style="background:rgba(255,255,255,.08);border-radius:18px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="muted" style="color:#d8cccc;">{{ $paymentText }}</div>
                            <strong>#BH-{{ $booking->booking_id }}</strong>
                        </div>

                        @if($booking->payment && $booking->payment->payment_status == 'paid')
                            <span class="pay-status">Đã thanh toán</span>
                        @else
                            <span class="pay-status pending">Chờ thanh toán</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="cardx side-card">
                <div class="card-title">Cập nhật nhanh</div>

                <form action="{{ route('admin.bookings.updateStatus', $booking->booking_id) }}" method="POST">
                    @csrf

                    <label class="form-label fw-bold">Trạng thái</label>
                    <select name="status" class="form-select mb-3">
                        <option value="0" {{ $booking->status == 0 ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="1" {{ $booking->status == 1 ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="2" {{ $booking->status == 2 ? 'selected' : '' }}>Đang thực hiện</option>
                        <option value="3" {{ $booking->status == 3 ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="4" {{ $booking->status == 4 ? 'selected' : '' }}>Đã hủy</option>
                    </select>

                    <button class="btn-main w-100">
                        Cập nhật trạng thái
                    </button>
                </form>
            </div>

            <div class="cardx side-card">
                <div class="card-title">Hỗ trợ nhanh</div>

                <a href="#" class="quick-btn">
                    <i class="bi bi-telephone"></i> Gọi khách hàng
                </a>

                <a href="#" class="quick-btn">
                    <i class="bi bi-chat-dots"></i> Nhắn tin Zalo
                </a>

                <a href="#" class="quick-btn">
                    <i class="bi bi-share"></i> Gửi vị trí cho KTV
                </a>
            </div>
        </div>

    </div>

</div>

<div class="modal fade" id="staffModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:24px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Phân công nhân viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.bookings.assignStaff', $booking->booking_id) }}" method="POST">
                @csrf

                <div class="modal-body">
                    <label class="form-label fw-bold">Chọn nhân viên</label>

                    <select name="staff_id" class="form-select" required>
                        <option value="">-- Chọn nhân viên --</option>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->staff_id }}"
                                {{ $booking->staff_id == $staff->staff_id ? 'selected' : '' }}>
                                {{ $staff->full_name }} - {{ $staff->skill ?? 'Nhân viên' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn-soft" data-bs-dismiss="modal">Hủy</button>
                    <button class="btn-main">Phân công</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:24px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Cập nhật trạng thái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.bookings.updateStatus', $booking->booking_id) }}" method="POST">
                @csrf

                <div class="modal-body">
                    <label class="form-label fw-bold">Trạng thái</label>

                    <select name="status" class="form-select" required>
                        <option value="0" {{ $booking->status == 0 ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="1" {{ $booking->status == 1 ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="2" {{ $booking->status == 2 ? 'selected' : '' }}>Đang thực hiện</option>
                        <option value="3" {{ $booking->status == 3 ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="4" {{ $booking->status == 4 ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn-soft" data-bs-dismiss="modal">Hủy</button>
                    <button class="btn-main">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection