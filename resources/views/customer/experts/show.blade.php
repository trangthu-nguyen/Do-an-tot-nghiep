@extends('customer.layout')

@section('title', 'Hồ sơ chuyên gia')

@section('content')

@php
    $avatarId = ($staff->staff_id % 70) + 1;
@endphp

<div class="container py-5">
    <a href="{{ route('customer.home') }}"
       class="text-decoration-none fw-bold"
       style="color:#7b5554;">
        ← Quay lại trang chủ
    </a>

    <div class="row mt-4 align-items-center g-5">
        <div class="col-md-4 text-center">
            <img src="https://i.pravatar.cc/300?img={{ $avatarId }}"
                 style="width:240px; height:240px; border-radius:50%; object-fit:cover; border:8px solid #f1dddd;"
                 alt="{{ $staff->full_name }}">
        </div>

        <div class="col-md-8">
            <h1 style="font-family:'Noto Serif',serif; font-weight:900; color:#2f2323;">
                {{ $staff->full_name }}
            </h1>

            <h5 style="color:#7b5554; font-weight:800;">
                {{ $staff->skill ?? 'Chuyên viên làm đẹp' }}
            </h5>

            <p class="mt-4" style="font-size:16px; line-height:1.9; color:#6f6464;">
                {{ $staff->bio ?? 'Chuyên viên BeautyHome với kinh nghiệm chăm sóc sắc đẹp tận tâm, luôn mang đến trải nghiệm thư giãn và an toàn cho khách hàng.' }}
            </p>

            <div class="mt-4">
                <p><strong>Số điện thoại:</strong> {{ $staff->phone ?? 'Đang cập nhật' }}</p>
                <p><strong>Email:</strong> {{ $staff->email ?? 'Đang cập nhật' }}</p>
                <p><strong>Địa chỉ:</strong> {{ $staff->address ?? 'Đang cập nhật' }}</p>
            </div>
        </div>
    </div>
</div>

@endsection