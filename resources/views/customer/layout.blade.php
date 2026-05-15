<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BeautyHome')</title>

    <link rel="stylesheet" href="{{ asset('css/luminous-beauty.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Noto+Serif:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root{--primary:#7b5554;--bg:#faf9f9;--text:#2f2323}
        body{font-family:'Manrope',sans-serif;background:var(--bg);color:var(--text)}
        h1,h2,h3,h4,h5{font-family:'Noto Serif',serif}

        .navbar-custom{background:rgba(255,255,255,.9);backdrop-filter:blur(18px);border-bottom:1px solid #f2e9e9;padding:18px 0}
        .brand-logo{font-size:32px;font-weight:700;color:var(--primary)!important;text-decoration:none}
        .navbar-nav{gap:10px}
        .nav-link{color:#5f5656!important;font-weight:700;padding:10px 18px!important;border-radius:999px;transition:.3s}
        .nav-link:hover{background:#f7eeee;color:var(--primary)!important}
        .nav-link.active{background:var(--primary);color:white!important}

        .icon-btn{width:46px;height:46px;border-radius:50%;border:none;background:white;display:flex;align-items:center;justify-content:center;font-size:18px;color:#6b5c5c;box-shadow:0 10px 25px rgba(123,85,84,.08);position:relative;text-decoration:none;overflow:visible}
        .header-avatar{width:46px;height:46px;border-radius:50%;object-fit:cover;border:2px solid rgba(123,85,84,.15)}

        .notification-badge{position:absolute;top:-6px;right:-6px;min-width:22px;height:22px;padding:0 6px;border-radius:999px;background:#ff3b30;color:white;font-size:11px;font-weight:900;display:flex;align-items:center;justify-content:center;border:2px solid white;z-index:999;line-height:1}

        .logout-btn{border:none;background:var(--primary);color:white;padding:12px 20px;border-radius:999px;font-weight:700;text-decoration:none}
        .main-wrapper{padding-top:40px;padding-bottom:80px}
    </style>
</head>

<body>

@php
    $layoutCustomer = session('customer_id')
        ? \App\Models\Customer::find(session('customer_id'))
        : null;

    $unreadCount = session('customer_id')
        ? \App\Models\Notification::where('user_type', 'customer')
            ->where('user_id', session('customer_id'))
            ->where('is_read', 0)
            ->count()
        : 0;
@endphp

<nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">
    <div class="container">

        <a class="brand-logo" href="{{ route('customer.home') }}">
            BeautyHome
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.home') ? 'active' : '' }}"
                       href="{{ route('customer.home') }}">
                        Trang chủ
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.services.*') ? 'active' : '' }}"
                       href="{{ route('customer.services.index') }}">
                        Dịch vụ
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.bookings.*') ? 'active' : '' }}"
                       href="{{ route('customer.bookings.index') }}">
                        Lịch đặt của tôi
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('customer.home') }}#experts-section" class="nav-link">
                        Chuyên gia
                    </a>
                </li>

            </ul>

            <div class="d-flex align-items-center gap-3">

                <a href="{{ route('customer.notifications.index') }}" class="icon-btn" title="Thông báo">
                    <i class="bi bi-bell"></i>

                    @if($unreadCount > 0)
                        <span class="notification-badge">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('customer.profile.index') }}" class="icon-btn" title="Hồ sơ cá nhân">
                    @if($layoutCustomer)
                        <img src="{{ $layoutCustomer->avatar_url }}"
                             class="header-avatar"
                             alt="avatar khách hàng">
                    @else
                        <i class="bi bi-person"></i>
                    @endif
                </a>

                @if(session('customer_id'))
                    <form action="{{ route('customer.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">
                            Đăng xuất
                        </button>
                    </form>
                @else
                    <a href="{{ route('customer.login') }}" class="logout-btn">
                        Đăng nhập
                    </a>
                @endif

            </div>
        </div>

    </div>
</nav>

<div class="container main-wrapper">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>