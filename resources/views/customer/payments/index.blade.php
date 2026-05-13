@extends('customer.layout')

@section('title','Thanh toán')

@section('content')

<style>
    :root{
        --primary:#7b5554;
        --primary-light:#ebbab9;
        --bg:#faf9f9;
        --text:#2f2323;
        --muted:#7d7272;
        --border:#eadede;
    }

    .page-title{
        font-size:34px;
        font-weight:900;
        color:var(--text);
        font-family:'Noto Serif', serif;
        margin-bottom:22px;
    }

    .breadcrumb-mini{
        font-size:13px;
        color:var(--muted);
        margin-bottom:14px;
    }

    .breadcrumb-mini b{
        color:var(--primary);
    }

    .back-button{
        width:52px;
        height:52px;
        border-radius:50%;
        background:white;
        border:1px solid var(--border);
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:24px;
        font-weight:800;
        color:var(--primary);
        text-decoration:none;
        box-shadow:0 10px 25px rgba(123,85,84,0.08);
        transition:0.25s;
    }

    .back-button:hover{
        background:var(--primary);
        color:white;
        transform:scale(1.06);
    }

    .payment-card{
        border-radius:26px;
        border:1px solid var(--border);
        background:white;
        overflow:hidden;
        transition:0.3s;
        height:100%;
        box-shadow:0 10px 30px rgba(123,85,84,0.06);
    }

    .payment-card:hover{
        transform:translateY(-5px);
        box-shadow:0 18px 45px rgba(123,85,84,0.12);
    }

    .payment-header{
        padding:20px 22px;
        border-bottom:1px solid #f3eaea;
        background:linear-gradient(90deg, rgba(235,186,185,0.35), rgba(255,255,255,0.95));
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:16px;
    }

    .payment-title{
        font-size:18px;
        font-weight:900;
        color:var(--primary);
        margin-bottom:4px;
    }

    .payment-sub{
        font-size:13px;
        color:var(--muted);
    }

    .badge-status{
        padding:10px 16px;
        border-radius:999px;
        font-weight:800;
        font-size:13px;
        border:1px solid transparent;
        white-space:nowrap;
    }

    .badge-paid{
        background:rgba(46,125,50,0.12);
        color:#2e7d32;
        border-color:rgba(46,125,50,0.25);
    }

    .badge-pending{
        background:rgba(255,193,7,0.18);
        color:#8a5b00;
        border-color:rgba(255,193,7,0.35);
    }

    .payment-body{
        padding:20px 22px;
    }

    .info-row{
        font-size:14px;
        color:var(--text);
        margin-bottom:10px;
        line-height:1.7;
    }

    .info-row b{
        font-weight:900;
        color:var(--text);
    }

    .staff-name{
        color:var(--primary);
        font-weight:800;
    }

    .price-row{
        margin-top:18px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:18px;
        flex-wrap:wrap;
    }

    .price-text{
        font-size:20px;
        font-weight:900;
        color:#ba1a1a;
    }

    .btn-ui{
        padding:12px 18px;
        border-radius:999px;
        font-weight:800;
        font-size:13px;
        border:none;
        transition:0.25s;
        text-decoration:none;
        display:inline-block;
    }

    .btn-ui-primary{
        background:var(--primary);
        color:white;
    }

    .btn-ui-primary:hover{
        background:#684847;
        color:white;
    }

    .btn-ui-outline{
        background:white;
        color:var(--primary);
        border:1px solid var(--border);
    }

    .btn-ui-outline:hover{
        background:rgba(235,186,185,0.35);
        color:var(--primary);
    }

    .btn-ui-disabled{
        background:#e8e2e2;
        color:#9a8d8d;
        cursor:not-allowed;
        border:1px solid #e0d6d6;
    }

    .method-text{
        margin-top:14px;
        font-size:13px;
        color:var(--muted);
    }

    .method-text b{
        color:var(--primary);
    }

    .empty-box{
        border-radius:24px;
        padding:22px;
        background:white;
        border:1px solid var(--border);
        box-shadow:0 10px 30px rgba(123,85,84,0.06);
        color:var(--muted);
        text-align:center;
        font-weight:700;
    }
</style>

<!-- TOP HEADER -->
<div class="d-flex align-items-center justify-content-between mb-4">

       

        <div class="page-title">
            Lịch sử thanh toán
        </div>
    

    <!-- BACK BUTTON -->
    <a href="{{ route('customer.profile.index') }}" class="back-button" title="Quay lại">
        <i class="bi bi-arrow-left"></i>
    </a>

</div>

@if($bookings->count() == 0)

    <div class="empty-box">
        Bạn chưa có lịch hẹn nào để thanh toán.
    </div>

@else

    <div class="row g-4">

        @foreach($bookings as $bk)

            @php
                $price = $bk->total_amount;
                $isPaid = ($bk->payment && ($bk->payment->payment_status == 'paid' || $bk->payment->payment_status == 'đã thanh toán'));
            @endphp

            <div class="col-lg-6">

                <div class="payment-card">

                    <!-- HEADER -->
                    <div class="payment-header">

                        <div>
                            <div class="payment-title">
                                Booking gồm {{ count($bk->bookingDetails) }} dịch vụ
                            </div>

                            <div class="payment-sub">
                                Mã booking: <b style="color:#2f2323;">{{ $bk->booking_id }}</b>
                            </div>
                        </div>

                        @if($isPaid)
                            <span class="badge-status badge-paid">
                                 Đã thanh toán
                            </span>
                        @else
                            <span class="badge-status badge-pending">
                                 Chờ thanh toán
                            </span>
                        @endif

                    </div>

                    <!-- BODY -->
                    <div class="payment-body">

                        <div class="info-row">
                             <b>Ngày:</b> {{ \Carbon\Carbon::parse($bk->booking_date)->format('d/m/Y') }}
                        </div>

                        <div class="info-row">
                             <b>Giờ:</b> {{ $bk->booking_time }}
                        </div>

                        <div class="info-row">
                             <b>Địa chỉ:</b> {{ $bk->address }}
                        </div>

                        <div class="info-row">
                             <b>Nhân viên:</b>
                            <span class="staff-name">
                                {{ $bk->staff->full_name ?? 'Chưa phân công' }}
                            </span>
                        </div>

                        <!-- PRICE + BUTTON -->
                        <div class="price-row">

                            <div class="price-text">
                                {{ number_format($price) }} VNĐ
                            </div>

                            <div>
                                @if($bk->status != 3)
                                    <span class="btn-ui btn-ui-disabled">
                                        Chưa hoàn thành
                                    </span>
                                @else
                                    @if($isPaid)
                                        <a href="{{ route('customer.payments.show', $bk->booking_id) }}"
                                           class="btn-ui btn-ui-outline">
                                            Xem thanh toán
                                        </a>
                                    @else
                                        <a href="{{ route('customer.payments.show', $bk->booking_id) }}"
                                           class="btn-ui btn-ui-primary">
                                            Thanh toán ngay
                                        </a>
                                    @endif
                                @endif
                            </div>

                        </div>

                        <!-- METHOD -->
                        <div class="method-text">
                            @if($bk->payment)
                                Phương thức: <b>{{ ucfirst($bk->payment->payment_method) }}</b>
                            @else
                                Phương thức: <i>Chưa chọn</i>
                            @endif
                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

@endif

@endsection