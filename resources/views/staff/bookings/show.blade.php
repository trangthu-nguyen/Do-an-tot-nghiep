@extends('staff.layout')

@section('title','Chi tiết lịch hẹn')

@section('page-title','Chi tiết lịch hẹn')

@section('content')

<style>
    :root{
        --primary:#7b5554;
        --primary-dark:#684847;
        --accent:#ebbab9;
        --soft:#fff7f7;
        --text:#2f2323;
        --muted:#7d7272;
        --border:#eadede;
    }

    .detail-header{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:20px;
        margin-bottom:22px;
        flex-wrap:wrap;
    }

    .status-mini{
        display:inline-flex;
        align-items:center;
        gap:7px;
        padding:7px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
        background:rgba(235,186,185,0.35);
        color:var(--primary);
        border:1px solid rgba(123,85,84,0.12);
        margin-bottom:10px;
    }

    .detail-title{
        font-size:38px;
        font-weight:900;
        color:var(--text);
        font-family:'Noto Serif', serif;
        margin-bottom:8px;
    }

    .detail-sub{
        display:flex;
        gap:12px;
        flex-wrap:wrap;
        color:var(--muted);
        font-size:14px;
        font-weight:700;
    }

    .detail-sub span{
        display:inline-flex;
        align-items:center;
        gap:7px;
    }

    .action-group{
        display:flex;
        gap:12px;
        flex-wrap:wrap;
    }

    .btn-action{
        border:none;
        padding:12px 18px;
        border-radius:14px;
        font-weight:900;
        font-size:14px;
        transition:0.25s;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:8px;
    }

    .btn-start{
        background:var(--primary);
        color:white;
        box-shadow:0 10px 25px rgba(123,85,84,0.16);
    }

    .btn-start:hover{
        background:var(--primary-dark);
        color:white;
        transform:translateY(-2px);
    }

    .btn-soft{
        background:white;
        color:var(--primary);
        border:1px solid var(--border);
    }

    .btn-soft:hover{
        background:rgba(235,186,185,0.22);
        color:var(--primary);
        transform:translateY(-2px);
    }

    .btn-finish{
        background:rgba(235,186,185,0.35);
        color:var(--primary);
        border:1px solid rgba(123,85,84,0.16);
    }

    .btn-finish:hover{
        background:rgba(235,186,185,0.55);
        color:var(--primary-dark);
        transform:translateY(-2px);
    }

    .content-grid{
        display:grid;
        grid-template-columns:1fr 330px;
        gap:22px;
    }

    @media(max-width: 992px){
        .content-grid{
            grid-template-columns:1fr;
        }
    }

    .ui-card{
        background:white;
        border:1px solid var(--border);
        border-radius:24px;
        padding:22px;
        box-shadow:0 14px 38px rgba(123,85,84,0.08);
        margin-bottom:20px;
    }

    .card-title-ui{
        font-size:18px;
        font-weight:900;
        color:var(--text);
        margin-bottom:16px;
        display:flex;
        align-items:center;
        gap:9px;
    }

    .card-title-ui i{
        color:var(--primary);
    }

    .customer-box{
        display:flex;
        gap:16px;
        align-items:flex-start;
    }

    .avatar{
        width:76px;
        height:76px;
        border-radius:50%;
        object-fit:cover;
        border:4px solid rgba(235,186,185,0.55);
    }

    .customer-name{
        font-size:18px;
        font-weight:900;
        color:var(--text);
        margin-bottom:4px;
    }

    .customer-info{
        color:var(--muted);
        font-size:14px;
        font-weight:700;
        margin-bottom:8px;
    }

    .note-box{
        background:rgba(235,186,185,0.18);
        border-left:4px solid var(--accent);
        border-radius:14px;
        padding:14px 16px;
        color:#5f5656;
        font-size:14px;
        line-height:1.7;
        margin-top:12px;
    }

    .service-row{
        display:flex;
        justify-content:space-between;
        gap:14px;
        padding:14px 0;
        border-bottom:1px dashed rgba(123,85,84,0.16);
    }

    .service-row:last-child{
        border-bottom:none;
    }

    .service-name{
        font-weight:900;
        color:var(--text);
        margin-bottom:4px;
    }

    .service-meta{
        color:var(--muted);
        font-size:13px;
        font-weight:700;
    }

    .service-price{
        color:#ba1a1a;
        font-weight:900;
        white-space:nowrap;
    }

    .check-grid{
        display:grid;
        grid-template-columns:repeat(2, minmax(0,1fr));
        gap:12px;
    }

    @media(max-width: 576px){
        .check-grid{
            grid-template-columns:1fr;
        }
    }

    .check-item{
        background:#fff;
        border:1px solid var(--border);
        border-radius:14px;
        padding:12px 14px;
        color:#5f5656;
        font-size:13px;
        font-weight:800;
        display:flex;
        align-items:center;
        gap:9px;
    }

    .check-item input{
        accent-color:var(--primary);
    }

    .note-area{
        width:100%;
        min-height:130px;
        border:1px solid var(--border);
        background:#fbf8f8;
        border-radius:18px;
        padding:16px;
        color:#5f5656;
        outline:none;
        resize:vertical;
        font-weight:600;
    }

    .side-info{
        display:flex;
        flex-direction:column;
        gap:14px;
    }

    .info-line{
        display:flex;
        align-items:flex-start;
        gap:10px;
        color:#5f5656;
        font-size:14px;
        line-height:1.7;
        font-weight:700;
    }

    .info-line i{
        color:var(--primary);
        margin-top:4px;
    }

    .map-box{
        height:155px;
        border-radius:18px;
        overflow:hidden;
        border:1px solid var(--border);
        background:
            linear-gradient(135deg, rgba(235,186,185,0.25), rgba(255,255,255,0.2)),
            url('https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=900&auto=format&fit=crop');
        background-size:cover;
        background-position:center;
        position:relative;
    }

    .map-pin{
        position:absolute;
        left:50%;
        top:48%;
        transform:translate(-50%, -50%);
        width:38px;
        height:38px;
        border-radius:50%;
        background:var(--primary);
        color:white;
        display:flex;
        align-items:center;
        justify-content:center;
        box-shadow:0 10px 25px rgba(123,85,84,0.25);
    }

    .payment-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:14px;
        padding:10px 0;
        color:#5f5656;
        font-size:14px;
        font-weight:700;
    }

    .payment-total{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:14px;
        padding-top:14px;
        margin-top:10px;
        border-top:1px dashed rgba(123,85,84,0.18);
        font-size:18px;
        font-weight:900;
        color:var(--text);
    }

    .paid-badge{
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:8px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
        background:rgba(25,135,84,0.12);
        color:#198754;
    }

    .unpaid-badge{
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:8px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
        background:rgba(255,193,7,0.16);
        color:#8a5b00;
    }

    .btn-outline-pink{
        width:100%;
        padding:12px 14px;
        border-radius:14px;
        background:white;
        border:1px solid var(--border);
        color:var(--primary);
        font-weight:900;
        text-decoration:none;
        display:flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        transition:0.25s;
    }

    .btn-outline-pink:hover{
        background:rgba(235,186,185,0.22);
        color:var(--primary);
    }

    .btn-report{
        width:100%;
        padding:12px 14px;
        border-radius:14px;
        background:rgba(186,26,26,0.06);
        border:1px solid rgba(186,26,26,0.16);
        color:#ba1a1a;
        font-weight:900;
        text-decoration:none;
        display:flex;
        align-items:center;
        justify-content:center;
        gap:8px;
    }

    .btn-back{
        display:inline-flex;
        align-items:center;
        gap:8px;
        color:var(--primary);
        font-weight:900;
        text-decoration:none;
        margin-bottom:16px;
    }
</style>

@php
    $firstDetail = $booking->bookingDetails->first();
    $firstService = $firstDetail->service ?? null;

    $statusText = 'Chờ xác nhận';
    if($booking->status == 1) $statusText = 'Đã xác nhận';
    if($booking->status == 2) $statusText = 'Đang thực hiện';
    if($booking->status == 3) $statusText = 'Hoàn thành';
    if($booking->status == 4) $statusText = 'Đã hủy';

    $payment = $booking->payment ?? null;
    $isPaid = $payment && ($payment->payment_status == 'paid' || $payment->payment_status == 'đã thanh toán');
@endphp

<a href="{{ route('staff.bookings.index') }}" class="btn-back">
    <i class="bi bi-arrow-left"></i>
    Quay lại lịch làm việc
</a>

<div class="detail-header">

    <div>
        <div class="status-mini">
            <i class="bi bi-circle-fill" style="font-size:8px;"></i>
            {{ $statusText }}
        </div>

        <div class="detail-title">
            {{ $firstService->service_name ?? 'Chi tiết lịch hẹn' }}
        </div>

        <div class="detail-sub">
            <span>
                <i class="bi bi-calendar-event"></i>
                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
            </span>

            <span>
                <i class="bi bi-clock"></i>
                {{ $booking->booking_time }}
                @if($firstService)
                    - {{ $firstService->duration }} phút
                @endif
            </span>

            <span>
                <i class="bi bi-hash"></i>
                Booking {{ $booking->booking_id }}
            </span>
        </div>
    </div>

    <div class="action-group">

        @if($booking->status == 1)
            <form action="{{ route('staff.bookings.updateStatus', $booking->booking_id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="2">
                <button class="btn-action btn-start" type="submit">
                    <i class="bi bi-play-fill"></i>
                    Bắt đầu dịch vụ
                </button>
            </form>
        @endif

        @if($booking->status == 2)
            <a href="#" class="btn-action btn-soft">
                <i class="bi bi-pause-fill"></i>
                Tạm dừng
            </a>

            <form action="{{ route('staff.bookings.updateStatus', $booking->booking_id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="3">
                <button class="btn-action btn-finish" type="submit">
                    <i class="bi bi-check-circle"></i>
                    Hoàn thành
                </button>
            </form>
        @endif

        @if($booking->status == 3)
            <span class="btn-action btn-finish">
                <i class="bi bi-check2-circle"></i>
                Đã hoàn thành
            </span>
        @endif

    </div>

</div>

<div class="content-grid">

    {{-- LEFT --}}
    <div>

        <div class="ui-card">
            <div class="customer-box">
                <img class="avatar"
                     src="https://i.pravatar.cc/120?img={{ ($booking->booking_id % 60) + 1 }}"
                     alt="avatar">

                <div style="flex:1;">
                    <div class="customer-name">
                        {{ $booking->customer->full_name ?? 'Không rõ khách hàng' }}
                    </div>

                    <div class="customer-info">
                        <i class="bi bi-telephone"></i>
                        {{ $booking->customer->phone ?? 'Chưa có số điện thoại' }}
                    </div>

                    <div class="customer-info">
                        <i class="bi bi-envelope"></i>
                        {{ $booking->customer->email ?? 'Chưa có email' }}
                    </div>

                    <div class="note-box">
                        <b>Ghi chú từ khách:</b><br>
                        {{ $booking->note ?? 'Khách hàng chưa để lại ghi chú cho lịch hẹn này.' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="ui-card">
            <div class="card-title-ui">
                <i class="bi bi-stars"></i>
                Dịch vụ thực hiện
            </div>

            @foreach($booking->bookingDetails as $detail)
                <div class="service-row">
                    <div>
                        <div class="service-name">
                            {{ $detail->service->service_name ?? 'Không rõ dịch vụ' }}
                        </div>

                        <div class="service-meta">
                            Thời lượng: {{ $detail->service->duration ?? '---' }} phút
                        </div>
                    </div>

                    <div class="service-price">
                        {{ number_format($detail->price) }} VNĐ
                    </div>
                </div>
            @endforeach
        </div>

        <div class="ui-card">
            <div class="card-title-ui">
                <i class="bi bi-bag-check"></i>
                Dụng cụ cần chuẩn bị
            </div>

            <div class="check-grid">
                <label class="check-item">
                    <input type="checkbox">
                    Khăn sạch / khăn nóng
                </label>

                <label class="check-item">
                    <input type="checkbox">
                    Bộ dụng cụ dịch vụ
                </label>

                <label class="check-item">
                    <input type="checkbox">
                    Sản phẩm chăm sóc chuyên sâu
                </label>

                <label class="check-item">
                    <input type="checkbox">
                    Phiếu xác nhận hoàn thành
                </label>
            </div>
        </div>

        <div class="ui-card">
            <div class="card-title-ui">
                <i class="bi bi-pencil-square"></i>
                Ghi chú chuyên môn
            </div>

            <textarea class="note-area"
                      placeholder="Nhập quan sát về tình trạng của khách và các lưu ý đặc biệt trong quá trình thực hiện..."></textarea>
        </div>

    </div>

    {{-- RIGHT --}}
    <div>

        <div class="ui-card">
            <div class="card-title-ui">
                <i class="bi bi-geo-alt"></i>
                Địa điểm thực hiện
            </div>

            <div class="side-info">
                <div class="info-line">
                    <i class="bi bi-pin-map"></i>
                    <div>
                        {{ $booking->address }}
                    </div>
                </div>

                <div class="map-box">
                    <div class="map-pin">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="ui-card">
            <div class="card-title-ui">
                <i class="bi bi-credit-card"></i>
                Chi tiết thanh toán
            </div>

            <div class="payment-row">
                <span>Dịch vụ chính</span>
                <b>{{ number_format($booking->total_amount) }} VNĐ</b>
            </div>

            <div class="payment-row">
                <span>Phụ phí di chuyển</span>
                <b>0 VNĐ</b>
            </div>

            <div class="payment-total">
                <span>Tổng cộng</span>
                <span style="color:#ba1a1a;">{{ number_format($booking->total_amount) }} VNĐ</span>
            </div>

            <div class="mt-3">
                @if($isPaid)
                    <span class="paid-badge">
                        <i class="bi bi-check-circle"></i>
                        Đã thanh toán
                    </span>
                @else
                    <span class="unpaid-badge">
                        <i class="bi bi-hourglass-split"></i>
                        Chờ thanh toán
                    </span>
                @endif
            </div>
        </div>

        <div class="d-grid gap-3">
            <a href="{{ route('staff.bookings.index') }}" class="btn-outline-pink">
                <i class="bi bi-clock-history"></i>
                Lịch sử lịch trình
            </a>

            <a href="#" class="btn-report">
                <i class="bi bi-exclamation-circle"></i>
                Báo cáo sự cố
            </a>
        </div>

    </div>

</div>

@endsection