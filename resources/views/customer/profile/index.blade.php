@extends('customer.layout')

@section('title','Hồ sơ cá nhân')

@section('content')

<style>
    :root{
        --primary:#7b5554;
        --primary-dark:#684847;
        --outline:#eadede;
        --text:#2f2323;
        --muted:#7d7272;
        --bg:#faf9f9;
    }

    .profile-wrapper{
        max-width: 720px;
        margin: 0 auto;
        background: white;
        border-radius: 28px;
        border: 1px solid var(--outline);
        box-shadow: 0 15px 45px rgba(123,85,84,0.12);
        padding: 35px 35px 28px;
    }

    .profile-top{
        text-align:center;
        margin-bottom: 28px;
    }

    .avatar{
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(235,186,185,0.7);
        box-shadow: 0 12px 30px rgba(123,85,84,0.15);
    }

    .profile-name{
        font-family: 'Noto Serif', serif;
        font-weight: 800;
        font-size: 28px;
        margin-top: 16px;
        margin-bottom: 6px;
        color: var(--text);
    }

    .profile-role{
        display:inline-flex;
        align-items:center;
        gap:8px;
        background: rgba(235,186,185,0.25);
        padding: 8px 16px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 13px;
        color: var(--primary);
    }

    .menu-box{
        display:flex;
        flex-direction:column;
        gap:14px;
        margin-top: 22px;
    }

    .menu-item{
        display:flex;
        align-items:center;
        justify-content:space-between;
        background: #fff;
        border: 1px solid var(--outline);
        border-radius: 18px;
        padding: 16px 18px;
        text-decoration:none;
        transition:0.25s;
        color: var(--text);
        font-weight: 800;
    }

    .menu-item:hover{
        background: rgba(235,186,185,0.18);
        transform: translateY(-2px);
    }

    .menu-left{
        display:flex;
        align-items:center;
        gap:12px;
    }

    .menu-icon{
        width: 40px;
        height: 40px;
        border-radius: 14px;
        display:flex;
        align-items:center;
        justify-content:center;
        background: rgba(235,186,185,0.28);
        color: var(--primary);
        font-size: 18px;
    }

    .menu-arrow{
        color: var(--muted);
        font-size: 18px;
    }
</style>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="profile-wrapper">

    <div class="profile-top">
        <img src="{{ $customer->avatar_url }}" class="avatar">

        <div class="profile-name">
            {{ $customer->full_name }}
        </div>

        <div class="profile-role">
            <i class="bi bi-star-fill"></i> Khách hàng
        </div>
    </div>

    <div class="menu-box">

        <a href="{{ route('customer.profile.edit') }}" class="menu-item">
            <div class="menu-left">
                <div class="menu-icon">
                    <i class="bi bi-person"></i>
                </div>
                Thông tin cá nhân
            </div>
            <div class="menu-arrow">
                <i class="bi bi-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('customer.profile.address') }}" class="menu-item">
            <div class="menu-left">
                <div class="menu-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>
                Địa chỉ đã lưu
            </div>
            <div class="menu-arrow">
                <i class="bi bi-chevron-right"></i>
            </div>
        </a>
        <a href="{{ route('customer.payments.index') }}" class="menu-item">
            <div class="menu-left">
                <div class="menu-icon">
                    <i class="bi bi-credit-card"></i>
                </div>
                Lịch sử thanh toán
            </div>
            <div class="menu-arrow">
                <i class="bi bi-chevron-right"></i>
            </div>
        </a>

    </div>

</div>

@endsection