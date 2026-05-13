@extends('customer.layout')

@section('title','BeautyHome')

@section('content')

<style>

    /* ================= HERO ================= */

    .hero-section{
        padding-top:30px;
        padding-bottom:110px;
    }

    .hero-badge{
        display:inline-flex;
        align-items:center;
        gap:10px;
        background:white;
        border:1px solid #f1e8e8;
        padding:10px 18px;
        border-radius:999px;
        font-size:13px;
        font-weight:700;
        color:#7b5554;
        margin-bottom:28px;
    }

    .hero-title{
        font-size:72px;
        line-height:1.1;
        color:#2f2323;
        margin-bottom:28px;
    }

    .hero-text{
        font-size:17px;
        line-height:1.9;
        color:#7d7272;
        max-width:620px;
        margin-bottom:42px;
    }

    .hero-actions{
        display:flex;
        gap:18px;
        flex-wrap:wrap;
    }

    .btn-primary-home{
        background:#7b5554;
        color:white;
        text-decoration:none;
        padding:16px 28px;
        border-radius:999px;
        font-weight:700;
        transition:0.3s;
    }

    .btn-primary-home:hover{
        background:#684847;
        color:white;
    }

    .btn-outline-home{
        background:white;
        color:#7b5554;
        text-decoration:none;
        border:1px solid #eadede;
        padding:16px 28px;
        border-radius:999px;
        font-weight:700;
    }

    .hero-image-wrapper{
        position:relative;
    }

    .hero-image{
        width:100%;
        height:760px;
        object-fit:cover;
        border-radius:40px;
    }

    .floating-card{
        position:absolute;
        left:-30px;
        bottom:40px;
        width:280px;
        background:rgba(255,255,255,0.92);
        backdrop-filter:blur(16px);
        border-radius:28px;
        padding:22px;
        box-shadow:
            0 20px 40px rgba(123,85,84,0.08);
    }

    .floating-title{
        font-size:20px;
        font-weight:800;
        margin-bottom:10px;
    }

    .floating-text{
        color:#7b7070;
        line-height:1.8;
        font-size:14px;
    }

    .hero-stats{
        display:flex;
        gap:50px;
        flex-wrap:wrap;
        margin-top:55px;
    }

    .hero-stat-number{
        font-size:34px;
        font-weight:800;
    }

    .hero-stat-label{
        color:#7d7272;
        margin-top:8px;
    }

    /* ================= FEATURED ================= */

    .featured-section{
        margin-top:80px;
    }

    .section-label{
        color:#7b5554;
        font-size:13px;
        font-weight:800;
        letter-spacing:1px;
        margin-bottom:14px;
    }

    .section-title{
        font-size:54px;
        color:#2f2323;
        margin-bottom:18px;
    }

    .section-text{
        max-width:700px;
        color:#7b7070;
        line-height:1.9;
        margin-bottom:50px;
    }

    .featured-card{
        background:white;
        border-radius:32px;
        overflow:hidden;
        border:1px solid #f1e7e7;
        transition:0.3s;
        height:100%;
    }

    .featured-card:hover{
        transform:translateY(-6px);
        box-shadow:0 18px 50px rgba(123,85,84,0.12);
    }

    .featured-image{
        width:100%;
        height:280px;
        object-fit:cover;
    }

    .featured-body{
        padding:28px;
    }

    .featured-name{
        font-size:26px;
        margin-bottom:12px;
        color:#2f2323;
        font-weight:800;
        font-family:'Noto Serif', serif;
    }

    .featured-desc{
        color:#7b7070;
        line-height:1.8;
        margin-bottom:24px;
        font-size:14px;
    }

    .featured-bottom{
        display:flex;
        justify-content:space-between;
        align-items:center;
    }

    .featured-price{
        font-size:22px;
        font-weight:900;
        color:#ba1a1a;
    }

    .btn-featured{
        background:#7b5554;
        color:white;
        text-decoration:none;
        padding:14px 22px;
        border-radius:999px;
        font-weight:700;
        transition:0.3s;
    }

    .btn-featured:hover{
        background:#684847;
        color:white;
    }

    @media(max-width:991px){

        .hero-title{
            font-size:48px;
        }

        .hero-image{
            height:500px;
            margin-top:40px;
        }

        .floating-card{
            left:20px;
            bottom:20px;
            width:240px;
        }

        .section-title{
            font-size:40px;
        }

    }

</style>

<!-- ================= HERO ================= -->

<div class="hero-section">

    <div class="row align-items-center">

        <!-- LEFT -->
        <div class="col-lg-6">

            <div class="hero-badge">
                ✨ Luxury Beauty At Home
            </div>

            <h1 class="hero-title">
                Chăm sóc sắc đẹp
                cao cấp ngay tại nhà
            </h1>

            <div class="hero-text">
                Trải nghiệm dịch vụ làm đẹp chuyên nghiệp với đội ngũ chuyên gia hàng đầu.
                Đặt lịch nhanh chóng, tiện lợi và thư giãn trong không gian riêng của bạn.
            </div>

            <div class="hero-actions">

                <a href="{{ route('customer.services.index') }}"
                   class="btn-primary-home">
                    Đặt lịch ngay
                </a>

                <a href="{{ route('customer.services.index') }}"
                   class="btn-outline-home">
                    Xem dịch vụ
                </a>

            </div>

            <div class="hero-stats">

                <div>
                    <div class="hero-stat-number">10K+</div>
                    <div class="hero-stat-label">Khách hàng hài lòng</div>
                </div>

                <div>
                    <div class="hero-stat-number">150+</div>
                    <div class="hero-stat-label">Chuyên gia cao cấp</div>
                </div>

                <div>
                    <div class="hero-stat-number">4.9★</div>
                    <div class="hero-stat-label">Đánh giá trung bình</div>
                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-6">

            <div class="hero-image-wrapper">

                <img src="{{ asset('uploads/services/hero1.jpg') }}"
     class="hero-image"
     alt="BeautyHome Banner">

                <div class="floating-card">
                    <div class="floating-title">
                        Premium Home Spa
                    </div>

                    <div class="floating-text">
                        Đội ngũ chuyên viên sẽ đến tận nơi
                        mang lại trải nghiệm spa chuẩn luxury.
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- ================= FEATURED SERVICES (TOP 3 BOOKED) ================= -->

<div class="featured-section">

    <div class="section-label">
        PREMIUM SERVICES
    </div>

    <h2 class="section-title">
        Dịch vụ nổi bật
    </h2>

    <div class="section-text">
        Top 3 dịch vụ được khách hàng đặt nhiều nhất.
    </div>

    <div class="row g-4">

        @forelse($topServices as $service)

            @php
                $serviceImage = $service->image
                    ? asset('uploads/services/' . $service->image)
                    : asset('uploads/services/default.jpg');
            @endphp

            <div class="col-lg-4">

                <div class="featured-card">

                    <a href="{{ route('customer.services.show', $service->service_id) }}"
                       style="text-decoration:none;">

                        <img src="{{ $serviceImage }}"
                             class="featured-image"
                             alt="{{ $service->service_name }}">
                    </a>

                    <div class="featured-body">

                        <a href="{{ route('customer.services.show', $service->service_id) }}"
                           style="text-decoration:none;">

                            <h3 class="featured-name">
                                {{ $service->service_name }}
                            </h3>
                        </a>

                        <div class="featured-desc">
                            {{ \Illuminate\Support\Str::limit($service->description, 120, '...') }}
                        </div>

                        <div class="featured-bottom">

                            <div class="featured-price">
                                {{ number_format($service->price) }}đ
                            </div>

                            <a href="{{ route('customer.services.show', $service->service_id) }}"
                               class="btn-featured">
                                Xem chi tiết
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">
                <div class="alert alert-info text-center">
                    Chưa có dịch vụ nổi bật.
                </div>
            </div>

        @endforelse

    </div>

</div>

@endsection