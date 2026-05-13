@extends('customer.layout')

@section('title','Giỏ hàng')

@section('content')

<style>
    :root{
        --primary:#7b5554;
        --primary-dark:#6d4848;
        --primary-light:#ebbab9;

        --background:#faf9f9;
        --surface:#ffffff;
        --surface-container:#efeded;

        --text:#1b1c1c;
        --text-muted:#504443;

        --outline:#d4c2c2;
    }

    body{
        background:var(--background);
        font-family:'Manrope',sans-serif;
    }

    .cart-header{
        margin-bottom:50px;
    }

    .cart-label{
        color:var(--primary);
        font-size:13px;
        font-weight:800;
        letter-spacing:1px;
        margin-bottom:14px;
    }

    .cart-title{
        font-family:'Noto Serif',serif;
        font-size:52px;
        color:var(--primary);
        margin-bottom:18px;
        font-weight:700;
    }

    .cart-text{
        color:var(--text-muted);
        line-height:1.9;
    }

    .cart-card{
        background:rgba(255,255,255,0.92);
        backdrop-filter:blur(10px);
        border-radius:28px;
        border:1px solid rgba(212,194,194,0.7);
        padding:24px;
        margin-bottom:24px;
        box-shadow:0 18px 45px rgba(123,85,84,0.08);
    }

    .cart-image{
        width:140px;
        height:140px;
        object-fit:cover;
        border-radius:22px;
        border:1px solid rgba(212,194,194,0.6);
    }

    .cart-service-name{
        font-size:28px;
        color:var(--text);
        margin-bottom:10px;
        font-weight:700;
    }

    .cart-service-desc{
        color:var(--text-muted);
        line-height:1.8;
        margin-bottom:10px;
    }

    .cart-price{
        font-size:26px;
        font-weight:800;
        color:var(--primary);
    }

    .cart-summary{
        background:rgba(255,255,255,0.92);
        backdrop-filter:blur(10px);
        border-radius:28px;
        border:1px solid rgba(212,194,194,0.7);
        padding:34px;
        position:sticky;
        top:120px;
        box-shadow:0 18px 45px rgba(123,85,84,0.08);
    }

    .summary-title{
        font-family:'Noto Serif',serif;
        font-size:30px;
        margin-bottom:26px;
        color:var(--primary);
        font-weight:700;
    }

    .summary-row{
        display:flex;
        justify-content:space-between;
        margin-bottom:18px;
        color:var(--text-muted);
        font-weight:600;
    }

    .summary-total{
        border-top:1px solid rgba(212,194,194,0.7);
        margin-top:20px;
        padding-top:20px;
        font-size:26px;
        font-weight:800;
        color:var(--text);
    }

    .btn-checkout{
        width:100%;
        border:none;
        background:var(--primary);
        color:white;
        padding:16px;
        border-radius:999px;
        font-weight:700;
        margin-top:26px;
        text-decoration:none;
        display:block;
        text-align:center;
        transition:0.25s;
    }

    .btn-checkout:hover{
        background:var(--primary-dark);
        color:white;
    }

    .btn-remove{
        border:none;
        background:rgba(235,186,185,0.25);
        color:var(--primary);
        padding:10px 18px;
        border-radius:999px;
        font-weight:700;
        transition:0.25s;
    }

    .btn-remove:hover{
        background:rgba(235,186,185,0.45);
    }

    .empty-cart{
        background:rgba(255,255,255,0.92);
        backdrop-filter:blur(10px);
        border-radius:28px;
        border:1px solid rgba(212,194,194,0.7);
        padding:70px;
        text-align:center;
        box-shadow:0 18px 45px rgba(123,85,84,0.08);
    }

    .empty-cart h3{
        font-family:'Noto Serif',serif;
        font-size:38px;
        margin-bottom:18px;
        color:var(--primary);
        font-weight:700;
    }

    .empty-cart p{
        color:var(--text-muted);
        margin-bottom:30px;
    }

    .btn-discover{
        background:var(--primary);
        color:white;
        text-decoration:none;
        padding:14px 26px;
        border-radius:999px;
        font-weight:700;
        transition:0.25s;
    }

    .btn-discover:hover{
        background:var(--primary-dark);
        color:white;
    }
</style>

<div class="cart-header">
    <div class="cart-label">BOOKING CART</div>

    <h1 class="cart-title">Giỏ hàng dịch vụ</h1>

    <div class="cart-text">
        Kiểm tra các dịch vụ bạn đã chọn trước khi thanh toán.
    </div>
</div>

@if($services->isEmpty())

    <div class="empty-cart">
        <h3>Giỏ hàng trống</h3>

        <p>Bạn chưa thêm dịch vụ nào vào giỏ hàng.</p>

        <a href="{{ route('customer.services.index') }}" class="btn-discover">
            Khám phá dịch vụ
        </a>
    </div>

@else

<div class="row g-4">

    <div class="col-lg-8">

        @foreach($services as $sv)

            <div class="cart-card">

                <div class="row align-items-center g-4">

                    <div class="col-md-3">
                        <img
                            src="{{ $sv->image ?? 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?q=80&w=1200&auto=format&fit=crop' }}"
                            class="cart-image"
                        >
                    </div>

                    <div class="col-md-6">

                        <h3 class="cart-service-name">
                            {{ $sv->service_name }}
                        </h3>

                        <div class="cart-service-desc">
                            Luxury beauty service tại nhà.
                        </div>

                        <div class="cart-service-desc">
                            ⏱ {{ $sv->duration }} phút
                        </div>

                        <div class="cart-price">
                            {{ number_format($sv->price) }}đ
                        </div>

                    </div>

                    <div class="col-md-3 text-md-end">
                        <form action="{{ route('customer.cart.remove', $sv->service_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-remove">
                                Xóa
                            </button>
                        </form>
                    </div>

                </div>

            </div>

        @endforeach

    </div>

    <div class="col-lg-4">

        <div class="cart-summary">

            <h2 class="summary-title">Tổng đơn</h2>

            <div class="summary-row">
                <span>Số dịch vụ</span>
                <span>{{ $services->count() }}</span>
            </div>

            <div class="summary-row">
                <span>Phí dịch vụ</span>
                <span>0đ</span>
            </div>

            <div class="summary-row summary-total">
                <span>Tổng cộng</span>
                <span>{{ number_format($total) }}đ</span>
            </div>

            <a href="{{ route('customer.bookings.create') }}" class="btn-checkout">
                Tiếp tục đặt lịch
            </a>

        </div>

    </div>

</div>

@endif

@endsection