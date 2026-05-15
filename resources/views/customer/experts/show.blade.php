@extends('customer.layout')

@section('title', 'Hồ sơ chuyên gia')

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

    $avatarImage = $femalePortraits[
        $staff->staff_id % count($femalePortraits)
    ];
@endphp

<style>
    .expert-wrapper{
        max-width:1200px;
        margin:auto;
    }

    .back-btn{
        text-decoration:none;
        color:#7b5554;
        font-weight:800;
        display:inline-flex;
        align-items:center;
        gap:8px;
        margin-bottom:30px;
    }

    .expert-card{
        background:white;
        border-radius:34px;
        padding:50px;
        box-shadow:0 18px 50px rgba(123,85,84,0.08);
        border:1px solid #f1e7e7;
    }

    .expert-avatar{
        width:260px;
        height:260px;
        border-radius:32px;
        object-fit:cover;
        border:8px solid #f7e5e5;
        box-shadow:0 14px 40px rgba(123,85,84,0.12);
    }

    .expert-name{
        font-size:52px;
        font-family:'Noto Serif', serif;
        font-weight:900;
        color:#2f2323;
        line-height:1.15;
        margin-bottom:12px;
    }

    .expert-role{
        display:inline-block;
        background:#f7e7e7;
        color:#7b5554;
        padding:10px 18px;
        border-radius:999px;
        font-weight:800;
        font-size:14px;
        margin-bottom:28px;
    }

    .expert-bio{
        font-size:16px;
        line-height:2;
        color:#756b6b;
        margin-bottom:32px;
    }

    .info-box{
        background:#faf7f7;
        border-radius:22px;
        padding:22px 24px;
        margin-bottom:18px;
    }

    .info-label{
        font-size:13px;
        font-weight:900;
        color:#9c8f8f;
        margin-bottom:8px;
        text-transform:uppercase;
        letter-spacing:1px;
    }

    .info-text{
        color:#2f2323;
        font-weight:700;
        font-size:16px;
    }

    .expert-stats{
        display:flex;
        gap:20px;
        flex-wrap:wrap;
        margin-top:32px;
    }

    .stat-item{
        background:white;
        border:1px solid #f0e3e3;
        border-radius:20px;
        padding:20px 24px;
        min-width:170px;
        text-align:center;
    }

    .stat-number{
        font-size:28px;
        font-weight:900;
        color:#7b5554;
    }

    .stat-label{
        color:#8a7d7d;
        font-size:13px;
        font-weight:700;
        margin-top:6px;
    }

    @media(max-width:991px){
        .expert-card{
            padding:30px;
        }

        .expert-name{
            font-size:38px;
            margin-top:25px;
        }

        .expert-avatar{
            width:220px;
            height:220px;
        }
    }
</style>

<div class="container py-5 expert-wrapper">

    <a href="{{ route('customer.home') }}" class="back-btn">
        <i class="bi bi-arrow-left"></i>
        Quay lại trang chủ
    </a>

    <div class="expert-card">
        <div class="row align-items-center g-5">
            <div class="col-lg-4 text-center">
                <img src="{{ $avatarImage }}"
                     class="expert-avatar"
                     alt="{{ $staff->full_name }}">
            </div>

            <div class="col-lg-8">
                <div class="expert-name">
                    {{ $staff->full_name }}
                </div>

                <div class="expert-role">
                    ✨ {{ $staff->skill ?? 'Chuyên viên làm đẹp cao cấp' }}
                </div>

                <div class="expert-bio">
                    {{ $staff->bio ?? 'Chuyên viên BeautyHome với kinh nghiệm chăm sóc sắc đẹp chuyên sâu, luôn mang đến trải nghiệm thư giãn, tinh tế và chuyên nghiệp cho khách hàng.' }}
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Số điện thoại</div>
                            <div class="info-text">
                                {{ $staff->phone ?? 'Đang cập nhật' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Email</div>
                            <div class="info-text">
                                {{ $staff->email ?? 'Đang cập nhật' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="info-box">
                            <div class="info-label">Địa chỉ</div>
                            <div class="info-text">
                                {{ $staff->address ?? 'Đang cập nhật' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="expert-stats">
                    <div class="stat-item">
                        <div class="stat-number">4.9★</div>
                        <div class="stat-label">Đánh giá</div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-number">100+</div>
                        <div class="stat-label">Khách hàng</div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-number">5+</div>
                        <div class="stat-label">Năm kinh nghiệm</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection