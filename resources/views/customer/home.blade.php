@extends('customer.layout')

@section('title','BeautyHome')

@section('content')

@php
    $femalePortraits = [
        'https://randomuser.me/api/portraits/women/44.jpg',
        'https://randomuser.me/api/portraits/women/65.jpg',
        'https://randomuser.me/api/portraits/women/68.jpg',
        'https://randomuser.me/api/portraits/women/71.jpg',
        'https://randomuser.me/api/portraits/women/72.jpg',
        'https://randomuser.me/api/portraits/women/76.jpg',
        'https://randomuser.me/api/portraits/women/79.jpg',
        'https://randomuser.me/api/portraits/women/81.jpg'
    ];

    $testimonials = [
        [
            'name' => 'Lan Anh',
            'role' => 'Khách hàng thân thiết',
            'avatar' => 'https://randomuser.me/api/portraits/women/44.jpg',
            'text' => 'Dịch vụ tại nhà của BeautyHome thực sự thay đổi cách tôi chăm sóc bản thân. Tiết kiệm thời gian và chất lượng như spa 5 sao.'
        ],
        [
            'name' => 'Minh Quân',
            'role' => 'Doanh nhân',
            'avatar' => 'https://randomuser.me/api/portraits/men/32.jpg',
            'text' => 'Tôi rất ấn tượng với sự chuyên nghiệp của các nhân viên. Massage đá nóng là liệu pháp tuyệt vời sau mỗi ngày làm việc căng thẳng.'
        ],
        [
            'name' => 'Thanh Hằng',
            'role' => 'Người mẫu',
            'avatar' => 'https://randomuser.me/api/portraits/women/68.jpg',
            'text' => 'Làn da của tôi luôn rạng ngời nhờ liệu trình chăm sóc da mặt định kỳ. Rất tiện lợi cho lịch trình bận rộn của tôi.'
        ],
    ];
@endphp

<style>
    .hero-section{padding:30px 0 110px}
    .hero-badge{display:inline-flex;align-items:center;gap:10px;background:white;border:1px solid #f1e8e8;padding:10px 18px;border-radius:999px;font-size:13px;font-weight:700;color:#7b5554;margin-bottom:28px}
    .hero-title{font-size:72px;line-height:1.1;color:#2f2323;margin-bottom:28px}
    .hero-text{font-size:17px;line-height:1.9;color:#7d7272;max-width:620px;margin-bottom:42px}
    .hero-actions,.hero-stats{display:flex;gap:18px;flex-wrap:wrap}
    .hero-stats{gap:50px;margin-top:55px}
    .hero-stat-number{font-size:34px;font-weight:800}
    .hero-stat-label{color:#7d7272;margin-top:8px}

    .btn-primary-home,.btn-outline-home,.btn-featured{
        display:inline-block;text-decoration:none;border-radius:999px;font-weight:700;transition:.3s
    }
    .btn-primary-home,.btn-featured{background:#7b5554;color:white}
    .btn-primary-home{padding:16px 28px}
    .btn-featured{padding:14px 22px}
    .btn-primary-home:hover,.btn-featured:hover{background:#684847;color:white}
    .btn-outline-home{background:white;color:#7b5554;border:1px solid #eadede;padding:16px 28px}

    .hero-image-wrapper{position:relative}
    .hero-image{width:100%;height:760px;object-fit:cover;border-radius:40px}
    .floating-card{position:absolute;left:-30px;bottom:40px;width:280px;background:rgba(255,255,255,.92);backdrop-filter:blur(16px);border-radius:28px;padding:22px;box-shadow:0 20px 40px rgba(123,85,84,.08)}
    .floating-title{font-size:20px;font-weight:800;margin-bottom:10px}
    .floating-text{color:#7b7070;line-height:1.8;font-size:14px}

    .featured-section,.testimonial-section,.expert-section{margin-top:90px}
    .section-label{color:#7b5554;font-size:13px;font-weight:800;letter-spacing:1px;margin-bottom:14px}
    .section-title{font-size:54px;color:#2f2323;margin-bottom:18px}
    .section-text{max-width:700px;color:#7b7070;line-height:1.9;margin-bottom:50px}

    .featured-card,.expert-card,.testimonial-card{
        background:white;border:1px solid #f1e7e7;transition:.3s
    }
    .featured-card{border-radius:32px;overflow:hidden;height:100%}
    .featured-card:hover,.expert-card:hover,.testimonial-card:hover{
        transform:translateY(-6px);box-shadow:0 18px 50px rgba(123,85,84,.12)
    }
    .featured-image{width:100%;height:280px;object-fit:cover}
    .featured-body{padding:28px}
    .featured-name{font-size:26px;margin-bottom:12px;color:#2f2323;font-weight:800;font-family:'Noto Serif',serif}
    .featured-desc{color:#7b7070;line-height:1.8;margin-bottom:24px;font-size:14px}
    .featured-bottom{display:flex;justify-content:space-between;align-items:center;gap:15px}
    .featured-price{font-size:22px;font-weight:900;color:#ba1a1a}

    .testimonial-section{margin:80px 0}
    .testimonial-title{text-align:center;font-size:46px;font-family:'Noto Serif',serif;font-weight:700;color:#2f2323;margin-bottom:40px}
    .testimonial-title span{color:#7b5554;font-weight:900}
    .testimonial-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
    .testimonial-card{border-radius:28px;padding:26px;box-shadow:0 8px 24px rgba(123,85,84,.05)}
    .testimonial-text{font-size:15px;line-height:1.8;color:#6f6464;font-style:italic;margin-bottom:22px}
    .testimonial-user{display:flex;align-items:center;gap:12px}
    .testimonial-user img{width:50px;height:50px;border-radius:50%;object-fit:cover;border:3px solid #f1dddd}
    .testimonial-name{font-size:16px;font-weight:800;color:#2f2323}
    .testimonial-role{font-size:13px;color:#9a8f8f}

    .expert-section{padding:70px 0;background:#f7f2f2;border-radius:36px}
    .expert-header{text-align:center;margin-bottom:42px}
    .expert-card{display:block;text-decoration:none;border-radius:24px;padding:26px 20px;text-align:center;height:100%;box-shadow:0 12px 35px rgba(123,85,84,.07)}
    .expert-avatar{width:92px;height:92px;border-radius:50%;object-fit:cover;border:5px solid #f1dddd;margin-bottom:16px}
    .expert-name{font-size:18px;font-weight:900;color:#2f2323;margin-bottom:6px;font-family:'Noto Serif',serif}
    .expert-skill{color:#7d7272;font-size:13px;font-weight:700;min-height:20px}
    .expert-stars{color:#f5b301;font-size:13px;margin-top:10px;letter-spacing:1px}
    .expert-link{margin-top:14px;font-size:13px;font-weight:900;color:#7b5554}

    .home-footer{
        margin-top:90px;
        background:#7b5554;
        border-radius:38px 38px 38px 38px;
        padding:64px 56px;
        color:white;
    }
    .footer-logo{font-size:42px;font-weight:900;margin-bottom:22px;font-family:'Noto Serif',serif}
    .footer-desc{color:#b9c1ca;line-height:1.9;font-size:16px;max-width:460px;margin-bottom:30px}
    .footer-social{display:flex;gap:16px}
    .footer-social a{
        width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.08);
        display:flex;align-items:center;justify-content:center;color:white;font-size:18px;
        text-decoration:none;transition:.3s
    }
    .footer-social a:hover{background:#7b5554;transform:translateY(-3px)}
    .footer-title{font-size:24px;font-weight:900;margin-bottom:26px;font-family:'Noto Serif',serif}
    .footer-links,.footer-contact{display:flex;flex-direction:column;gap:18px}
    .footer-links a{color:#b9c1ca;text-decoration:none;font-size:16px;transition:.3s}
    .footer-links a:hover{color:white;padding-left:5px}
    .footer-contact div{display:flex;align-items:center;gap:14px;color:#b9c1ca;font-size:16px;line-height:1.7}
    .footer-contact i{color:#c79a74;font-size:18px}

    @media(max-width:991px){
        .hero-title{font-size:48px}
        .hero-image{height:500px;margin-top:40px}
        .floating-card{left:20px;bottom:20px;width:240px}
        .section-title{font-size:40px}
        .testimonial-grid{grid-template-columns:1fr}
        .testimonial-title{font-size:34px}
        .expert-section{border-radius:24px;padding:50px 15px}
        .home-footer{padding:48px 28px;border-radius:28px 28px 0 0}
        .footer-logo{font-size:34px}
        .footer-title{font-size:22px}
    }
</style>

<div class="hero-section">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <div class="hero-badge">✨ Luxury Beauty At Home</div>

            <h1 class="hero-title">
                Chăm sóc sắc đẹp cao cấp ngay tại nhà
            </h1>

            <div class="hero-text">
                Trải nghiệm dịch vụ làm đẹp chuyên nghiệp với đội ngũ chuyên gia hàng đầu.
                Đặt lịch nhanh chóng, tiện lợi và thư giãn trong không gian riêng của bạn.
            </div>

            <div class="hero-actions">
                <a href="{{ route('customer.services.index') }}" class="btn-primary-home">
                    Đặt lịch ngay
                </a>

                <a href="{{ route('customer.services.index') }}" class="btn-outline-home">
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

        <div class="col-lg-6">
            <div class="hero-image-wrapper">
                <img src="{{ asset('uploads/services/hero1.jpg') }}"
                     class="hero-image"
                     alt="BeautyHome Banner">

                <div class="floating-card">
                    <div class="floating-title">Premium Home Spa</div>
                    <div class="floating-text">
                        Đội ngũ chuyên viên sẽ đến tận nơi mang lại trải nghiệm spa chuẩn luxury.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="featured-section">
    <div class="section-label">PREMIUM SERVICES</div>
    <h2 class="section-title">Dịch vụ nổi bật</h2>
    <div class="section-text">Top 3 dịch vụ được khách hàng đặt nhiều nhất.</div>

    <div class="row g-4">
        @forelse($topServices as $service)
            @php
                $serviceImage = $service->image
                    ? asset('uploads/services/' . $service->image)
                    : asset('uploads/services/default.jpg');
            @endphp

            <div class="col-lg-4">
                <div class="featured-card">
                    <a href="{{ route('customer.services.show', $service->service_id) }}" style="text-decoration:none;">
                        <img src="{{ $serviceImage }}"
                             class="featured-image"
                             alt="{{ $service->service_name }}">
                    </a>

                    <div class="featured-body">
                        <a href="{{ route('customer.services.show', $service->service_id) }}" style="text-decoration:none;">
                            <h3 class="featured-name">{{ $service->service_name }}</h3>
                        </a>

                        <div class="featured-desc">
                            {{ \Illuminate\Support\Str::limit($service->description, 120, '...') }}
                        </div>

                        <div class="featured-bottom">
                            <div class="featured-price">
                                {{ number_format($service->price) }}đ
                            </div>

                            <a href="{{ route('customer.services.show', $service->service_id) }}" class="btn-featured">
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

<section class="testimonial-section">
    <div class="testimonial-title">
        Khách hàng <span>nói gì về chúng tôi</span>
    </div>

    <div class="testimonial-grid">
        @foreach($testimonials as $item)
            <div class="testimonial-card">
                <p class="testimonial-text">
                    " {{ $item['text'] }} "
                </p>

                <div class="testimonial-user">
                    <img src="{{ $item['avatar'] }}" alt="{{ $item['name'] }}">

                    <div>
                        <div class="testimonial-name">{{ $item['name'] }}</div>
                        <div class="testimonial-role">{{ $item['role'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<div class="expert-section" id="experts-section">
    <div class="container">
        <div class="expert-header">
            <div class="section-label">BEAUTY EXPERTS</div>

            <h2 class="section-title">
                Chuyên gia tiêu biểu
            </h2>

            <div class="section-text mx-auto mb-0">
                Những chuyên gia làm đẹp nổi bật luôn sẵn sàng mang đến trải nghiệm chăm sóc tận tâm và chuyên nghiệp.
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse($experts as $expert)
                @php
                    $avatarImage = $femalePortraits[
                        $expert->staff_id % count($femalePortraits)
                    ];
                @endphp

                <div class="col-lg-3 col-md-6">
                    <a href="{{ route('customer.experts.show', $expert->staff_id) }}" class="expert-card">
                        <img src="{{ $avatarImage }}"
                             class="expert-avatar"
                             alt="{{ $expert->full_name }}">

                        <div class="expert-name">{{ $expert->full_name }}</div>

                        <div class="expert-skill">
                            {{ $expert->skill ?? 'Chuyên viên làm đẹp' }}
                        </div>

                        <div class="expert-stars">★★★★★</div>
                        <div class="expert-link">Xem hồ sơ</div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        Chưa có chuyên gia tiêu biểu.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<footer class="home-footer">
    <div class="row g-5">
        <div class="col-lg-5">
            <div class="footer-logo">BeautyHome</div>

            <div class="footer-desc">
                Mang đẳng cấp Spa đến tận nhà bạn. Chúng tôi cam kết mang lại trải nghiệm làm đẹp chuyên nghiệp, tin cậy và thư giãn tuyệt đối.
            </div>

            <div class="footer-social">
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-twitter-x"></i></a>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="footer-title">Khám phá</div>

            <div class="footer-links">
                <a href="{{ route('customer.services.index') }}">Dịch vụ</a>
                <a href="#">Về chúng tôi</a>
                <a href="#">Tin tức & mẹo</a>
                <a href="#">Hệ thống</a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="footer-title">Liên hệ</div>

            <div class="footer-contact">
                <div>
                    <i class="bi bi-geo-alt"></i>
                    175 Tây Sơn, P Quang Trung, Đống Đa, Hà Nội
                </div>

                <div>
                    <i class="bi bi-envelope"></i>
                    support@beautyhome.vn
                </div>

                <div>
                    <i class="bi bi-telephone"></i>
                    0123 456 789
                </div>
            </div>
        </div>
    </div>
</footer>

@endsection