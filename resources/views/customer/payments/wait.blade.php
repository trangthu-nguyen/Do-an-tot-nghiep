@extends('customer.layout')

@section('title', 'Thanh toán')

@section('content')

@php
    $method = strtolower($booking->payment->payment_method ?? '');

    $transactionPrefix =
        $method == 'momo'
            ? 'MOMO'
            : ($method == 'vnpay'
                ? 'VNPAY'
                : 'BANK');

    $transactionCode =
        $transactionPrefix .
        '-' .
        now()->format('Ymd') .
        '-' .
        str_pad($booking->booking_id, 4, '0', STR_PAD_LEFT);
@endphp

<style>

    body{
        background:#f7f3f3;
    }

    .payment-wrap{
        max-width:430px;
        margin:auto;
        padding:30px 0;
    }

    .payment-card{
        background:white;
        border-radius:28px;
        overflow:hidden;
        box-shadow:0 15px 45px rgba(0,0,0,0.08);
    }

    .top-header{
        padding:20px;
        border-bottom:1px solid #f1eaea;
    }

    .pay-title{
        font-size:24px;
        font-weight:900;
        color:#7b5554;
        line-height:1.4;
    }

    .pay-amount{
        font-size:38px;
        font-weight:900;
        color:#2f2323;
    }

    .qr-box{
        width:240px;
        height:240px;
        margin:auto;
        border-radius:24px;
        display:flex;
        align-items:center;
        justify-content:center;
        overflow:hidden;
        background:white;
    }

    .qr-image{
        width:220px;
        height:220px;
        object-fit:cover;
        border-radius:20px;
        border:1px solid #f0e4e4;
    }

    .pay-btn{
        width:100%;
        border:none;
        padding:16px;
        border-radius:16px;
        background:#7b5554;
        color:white;
        font-weight:900;
        font-size:16px;
        transition:0.25s;
    }

    .pay-btn:hover{
        background:#684847;
    }

    .info-box{
        background:#fff7f7;
        border-radius:20px;
        padding:18px;
    }

    .bank-item{
        margin-bottom:12px;
    }

    .bank-label{
        font-size:13px;
        color:#8a7d7d;
        font-weight:700;
    }

    .bank-value{
        font-size:15px;
        font-weight:900;
        color:#2f2323;
    }

    .method-badge{
        display:inline-block;
        padding:7px 14px;
        border-radius:999px;
        background:#f8e4e4;
        color:#7b5554;
        font-size:12px;
        font-weight:900;
        margin-bottom:16px;
    }

    .transaction-box{
        background:#faf7f7;
        border-radius:18px;
        padding:16px;
        text-align:center;
        margin-bottom:18px;
    }

    .transaction-label{
        font-size:12px;
        color:#8a7d7d;
        font-weight:700;
    }

    .transaction-code{
        font-size:16px;
        font-weight:900;
        color:#7b5554;
        margin-top:5px;
        letter-spacing:1px;
    }

    .countdown-box{
        background:#fff8e7;
        border-radius:18px;
        padding:14px;
        text-align:center;
        font-weight:800;
        color:#8a5a00;
        margin-bottom:18px;
    }

</style>

<div class="payment-wrap">

    <div class="payment-card">

        <div class="top-header">

            <div class="method-badge">

                @if($method == 'momo')
                    THANH TOÁN MOMO
                @elseif($method == 'vnpay')
                    THANH TOÁN VNPAY
                @else
                    CHUYỂN KHOẢN NGÂN HÀNG
                @endif

            </div>

            <div class="pay-title mb-2">
                {{ optional($booking->bookingDetails->first()?->service)->service_name }}
            </div>

            <div class="text-muted mb-2">
                Booking #{{ $booking->booking_id }}
            </div>

            <div class="pay-amount">
                {{ number_format($booking->payment->amount,0,',','.') }}đ
            </div>

        </div>

        <div class="p-4">

            {{-- MOMO --}}
            @if($method == 'momo')

                <div class="qr-box mb-4">

                    <img src="{{ asset('uploads/payments/momo-qr.jpg') }}"
                         alt="QR MOMO"
                         class="qr-image">

                </div>

            @endif


            {{-- VNPAY --}}
            @if($method == 'vnpay')

                <div class="qr-box mb-4">

                    <img src="{{ asset('uploads/payments/vnpay-qr.jpg') }}"
                         alt="QR VNPAY"
                         class="qr-image">

                </div>

            @endif


            {{-- BANK --}}
            @if($method == 'bank')

                <div class="info-box mb-4">

                    <div class="bank-item">
                        <div class="bank-label">
                            Ngân hàng
                        </div>

                        <div class="bank-value">
                            Vietcombank
                        </div>
                    </div>

                    <div class="bank-item">
                        <div class="bank-label">
                            Số tài khoản
                        </div>

                        <div class="bank-value">
                            123456789012
                        </div>
                    </div>

                    <div class="bank-item">
                        <div class="bank-label">
                            Chủ tài khoản
                        </div>

                        <div class="bank-value">
                            BEAUTYHOME SPA
                        </div>
                    </div>

                    <div class="bank-item">
                        <div class="bank-label">
                            Nội dung chuyển khoản
                        </div>

                        <div class="bank-value">
                            BOOKING{{ $booking->booking_id }}
                        </div>
                    </div>

                </div>

                <div class="qr-box mb-4">

                    <img src="{{ asset('uploads/payments/bank-qr.jpg') }}"
                         alt="QR BANK"
                         class="qr-image">

                </div>

            @endif


            {{-- Transaction ID --}}
            @if($method == 'momo' || $method == 'vnpay')

                <div class="transaction-box">

                    <div class="transaction-label">
                        Mã giao dịch
                    </div>

                    <div class="transaction-code">
                        {{ $transactionCode }}
                    </div>

                </div>

                <div class="countdown-box">
                    Mã QR hết hạn sau:
                    <span id="countdown">15:00</span>
                </div>

            @endif


            {{-- Hướng dẫn --}}
            @if($method == 'momo')

                <div class="info-box mb-4">

                    <div class="fw-bold mb-2">
                        Hướng dẫn thanh toán
                    </div>

                    <ol class="mb-0 text-muted">
                        <li>Mở ứng dụng MoMo</li>
                        <li>Quét mã QR phía trên</li>
                        <li>Xác nhận giao dịch</li>
                    </ol>

                </div>

            @endif


            @if($method == 'vnpay')

                <div class="info-box mb-4">

                    <div class="fw-bold mb-2">
                        Hướng dẫn thanh toán
                    </div>

                    <ol class="mb-0 text-muted">
                        <li>Mở ứng dụng ngân hàng</li>
                        <li>Quét mã VNPAY QR</li>
                        <li>Xác nhận giao dịch</li>
                    </ol>

                </div>

            @endif


            <div class="alert alert-warning rounded-4 mb-4">

                Trạng thái hiện tại:
                <strong>Chờ admin xác nhận thanh toán</strong>

            </div>

            <a href="{{ route('customer.bookings.index') }}"
               class="btn pay-btn">

                Tôi đã thanh toán

            </a>

        </div>

    </div>

</div>


<script>

    let timeLeft = 15 * 60;

    const countdownEl = document.getElementById('countdown');

    if(countdownEl){

        const timer = setInterval(() => {

            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;

            countdownEl.textContent =
                String(minutes).padStart(2, '0')
                + ':'
                +
                String(seconds).padStart(2, '0');

            if(timeLeft <= 0){

                clearInterval(timer);

                countdownEl.textContent = 'Đã hết hạn';

            }

            timeLeft--;

        },1000);

    }

</script>

@endsection