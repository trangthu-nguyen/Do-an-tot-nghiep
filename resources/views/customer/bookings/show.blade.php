@extends('customer.layout')

@section('title', 'Chi tiết Booking')

@section('content')

<style>
    :root {
        --primary: #7b5554;
        --primary-dark: #6d4848;
        --outline: #d4c2c2;
        --text-muted: #504443;
    }

    .page-title {
        font-family: 'Noto Serif', serif;
        font-weight: 800;
        color: var(--primary);
    }

    .card-ui {
        border-radius: 24px;
        border: 1px solid rgba(212, 194, 194, 0.7);
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(10px);
        box-shadow: 0 18px 45px rgba(123, 85, 84, 0.08);
    }

    .label-muted {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .value-bold {
        font-weight: 800;
        font-size: 16px;
        color: #2f2323;
    }

    .badge-ui {
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        display: inline-block;
    }

    .badge-wait { background: rgba(235, 186, 185, 0.45); color: #6d4848; }
    .badge-confirm { background: rgba(123, 85, 84, 0.15); color: #7b5554; }
    .badge-doing { background: rgba(96, 94, 93, 0.15); color: #605e5d; }
    .badge-done { background: rgba(40, 167, 69, 0.15); color: #198754; }
    .badge-cancel { background: rgba(186, 26, 26, 0.15); color: #ba1a1a; }

    .btn-primary-ui {
        background: var(--primary);
        border: none;
        border-radius: 14px;
        padding: 10px 16px;
        font-weight: 700;
        color: white;
        transition: 0.2s;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-primary-ui:hover {
        background: var(--primary-dark);
        color: white;
    }

    .btn-danger-ui {
        background: #7b5554;
        border: none;
        border-radius: 14px;
        padding: 10px 16px;
        font-weight: 700;
        color: white;
        transition: 0.2s;
    }

    .btn-danger-ui:hover {
        background: #93000a;
    }

    .btn-muted-ui {
        background: #e9e8e8;
        border: none;
        border-radius: 14px;
        padding: 10px 16px;
        font-weight: 700;
        color: var(--text-muted);
    }

    .service-item {
        padding: 12px 0;
        border-bottom: 1px dashed rgba(212, 194, 194, 0.6);
    }

    .service-item:last-child {
        border-bottom: none;
    }

    .price-text {
        font-weight: 800;
        color: #ba1a1a;
    }

    /* ================== BACK BUTTON (GIỐNG SERVICES/SHOW) ================== */
    .back-button {
        position: absolute;
        top: 30px;
        left: 30px;
        background: white;
        color: var(--primary);
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 6px 20px rgba(123,85,84,0.15);
        border: 1px solid #f2e8e8;
        transition: all 0.3s ease;
        z-index: 20;
    }

    .back-button:hover {
        background: var(--primary);
        color: white;
        transform: scale(1.08);
    }
</style>

<div class="container py-5 position-relative">

    {{-- NÚT QUAY LẠI TRÒN GÓC TRÁI --}}
    <a href="{{ route('customer.bookings.index') }}" class="back-button" title="Quay lại">←</a>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3 class="page-title mb-0 flex-grow-1 text-end">
            Chi tiết Booking #{{ $booking->booking_id }}
        </h3>
    </div>

    <div class="row g-4">

        {{-- LEFT --}}
        <div class="col-lg-8">

            {{-- Thông tin booking --}}
            <div class="card card-ui mb-4">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">Thông tin lịch đặt</h5>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="label-muted">Ngày đặt</div>
                            <div class="value-bold">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="label-muted">Giờ</div>
                            <div class="value-bold">
                                {{ $booking->booking_time }}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="label-muted">Địa chỉ</div>
                            <div class="value-bold">
                                {{ $booking->address }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="label-muted">Nhân viên</div>
                            <div class="value-bold">
                                {{ $booking->staff->full_name ?? 'Chưa phân công' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="label-muted">Trạng thái</div>

                            <div>
                                @if($booking->status == 0)
                                    <span class="badge-ui badge-wait">Chờ xác nhận</span>
                                @elseif($booking->status == 1)
                                    <span class="badge-ui badge-confirm">Đã xác nhận</span>
                                @elseif($booking->status == 2)
                                    <span class="badge-ui badge-doing">Đang thực hiện</span>
                                @elseif($booking->status == 3)
                                    <span class="badge-ui badge-done">Hoàn thành</span>
                                @elseif($booking->status == 4)
                                    <span class="badge-ui badge-cancel">Đã hủy</span>
                                @else
                                    <span class="badge-ui" style="background:#eee;color:#333;">Không rõ</span>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            {{-- Danh sách dịch vụ --}}
            <div class="card card-ui">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">Dịch vụ đã chọn</h5>

                    @foreach($booking->bookingDetails as $detail)
                        <div class="service-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold">{{ $detail->service->service_name ?? 'Không rõ' }}</div>
                                <small class="text-muted">
                                    Thời gian: {{ $detail->service->duration ?? '---' }} phút
                                </small>
                            </div>

                            <div class="fw-bold">
                                {{ number_format($detail->price) }} VNĐ
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">

            {{-- Tổng tiền --}}
            <div class="card card-ui mb-4">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">Thanh toán</h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tổng tiền</span>
                        <span class="price-text">{{ number_format($booking->total_amount) }} VNĐ</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Phương thức</span>
                        <span class="fw-bold">
                            {{ $booking->payment->payment_method ?? 'Chưa chọn' }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Trạng thái</span>
                        <span class="fw-bold">
                            @if($booking->payment && $booking->payment->payment_status == 'paid')
                                <span class="text-success">Đã thanh toán</span>
                            @else
                                <span class="text-danger">Chưa thanh toán</span>
                            @endif
                        </span>
                    </div>

                </div>
            </div>

            {{-- Actions --}}
            <div class="card card-ui">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">Hành động</h5>

                    {{-- Cho phép hủy nếu status = 0 --}}
                    @if($booking->status == 0)
                        <form action="{{ route('customer.bookings.cancel', $booking->booking_id) }}" method="POST">
                            @csrf
                            <button class="btn-danger-ui w-100"
                                    onclick="return confirm('Bạn chắc chắn muốn hủy lịch?')">
                                Hủy lịch
                            </button>
                        </form>

                    {{-- status = 3 -> đánh giá --}}
                    @elseif($booking->status == 3)

                        @if($booking->feedback)
                            <a href="{{ route('customer.feedback.show', $booking->booking_id) }}"
                               class="btn-primary-ui w-100">
                                Xem đánh giá
                            </a>
                        @else
                            <a href="{{ route('customer.feedback.create', $booking->booking_id) }}"
                               class="btn-primary-ui w-100">
                                Đánh giá ngay
                            </a>
                        @endif

                    {{-- Các status khác --}}
                    @else
                        <button class="btn-muted-ui w-100" disabled>
                            Không có hành động
                        </button>
                    @endif

                    {{-- Nếu booking hoàn thành thì hiện nút thanh toán --}}
                    @if($booking->status == 3)
                        <a href="{{ route('customer.payments.show', $booking->booking_id) }}"
                           class="btn-muted-ui w-100 mt-3 text-center"
                           style="display:block;">
                            Xem thanh toán
                        </a>
                    @endif

                </div>
            </div>

        </div>

    </div>

</div>

@endsection