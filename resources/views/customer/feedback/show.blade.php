@extends('customer.layout')

@section('title','Xem đánh giá')

@section('content')

<style>
    :root{
        --primary:#7b5554;
        --primary-dark:#6d4848;
        --primary-light:#ebbab9;

        --background:#faf9f9;
        --outline:#d4c2c2;

        --text:#1b1c1c;
        --text-muted:#504443;
    }

    .page-title{
        font-family:'Noto Serif',serif;
        font-weight:700;
        color:var(--primary);
    }

    .card-ui{
        background:rgba(255,255,255,0.92);
        backdrop-filter:blur(10px);
        border-radius:22px;
        border:1px solid rgba(212,194,194,0.7);
        box-shadow:0 18px 45px rgba(123,85,84,0.08);
    }

    .service-name{
        color:var(--primary);
        font-weight:700;
    }

    .comment-box{
        background:rgba(250,249,249,0.9);
        border-radius:16px;
        border:1px solid rgba(212,194,194,0.7);
        padding:16px;
        color:var(--text-muted);
        line-height:1.8;
    }

    .btn-outline-ui{
        border:1px solid var(--outline);
        border-radius:14px;
        padding:10px 16px;
        font-weight:600;
        color:var(--text-muted);
        background:transparent;
        transition:0.25s;
        text-decoration:none;
        display:inline-block;
    }

    .btn-outline-ui:hover{
        background:rgba(235,186,185,0.18);
        color:var(--primary);
    }
</style>

<h3 class="page-title mb-3">⭐ Đánh giá của bạn</h3>

<div class="card card-ui p-4">

    <h5 class="service-name mb-3">
        Dịch vụ:
        <b>
            @foreach($feedback->booking->bookingDetails as $detail)
                {{ $detail->service->service_name ?? 'Không rõ' }}@if(!$loop->last), @endif
            @endforeach
        </b>
    </h5>

    <p class="mb-1">
        <b>Khách hàng:</b>
        {{ $feedback->customer->full_name ?? 'Ẩn danh' }}
    </p>

    <p class="mb-3 text-muted">
        <b>Thời gian đánh giá:</b>
        {{ $feedback->created_at }}
    </p>

    <div class="mb-3">
        <label class="fw-bold">Số sao:</label>
        <div style="font-size: 30px;">
            @for($i=1; $i<=5; $i++)
                @if($i <= $feedback->rating)
                    <span style="color: gold;">★</span>
                @else
                    <span style="color: #b8aaaa;">★</span>
                @endif
            @endfor
            <span class="text-muted" style="font-size:15px;">({{ $feedback->rating }}/5)</span>
        </div>
    </div>

    <div class="mb-3">
        <label class="fw-bold">Nhận xét:</label>
        <div class="comment-box">
            {{ $feedback->comment ?? 'Không có nhận xét' }}
        </div>
    </div>

    <a href="{{ route('customer.bookings.index') }}" class="btn-outline-ui">
        Quay lại
    </a>

</div>

@endsection