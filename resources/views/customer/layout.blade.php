<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="stylesheet" href="{{ asset('css/luminous-beauty.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Noto+Serif:wght@500;600;700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BeautyHome')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Noto+Serif:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        :root{
            --primary:#7b5554;
            --primary-light:#ebbab9;
            --bg:#faf9f9;
            --text:#2f2323;
        }

        body{
            font-family:'Manrope',sans-serif;
            background:var(--bg);
            color:var(--text);
        }

        h1,h2,h3,h4,h5{
            font-family:'Noto Serif',serif;
        }

        /* ================= NAVBAR ================= */

        .navbar-custom{
            background:rgba(255,255,255,0.9);
            backdrop-filter:blur(18px);
            border-bottom:1px solid #f2e9e9;
            padding:18px 0;
        }

        .brand-logo{
            font-size:32px;
            font-weight:700;
            color:var(--primary)!important;
            text-decoration:none;
        }

        .navbar-nav{
            gap:10px;
        }

        .nav-link{
            color:#5f5656!important;
            font-weight:700;
            padding:10px 18px!important;
            border-radius:999px;
            transition:0.3s;
        }

        .nav-link:hover{
            background:#f7eeee;
            color:var(--primary)!important;
        }

        .nav-link.active{
            background:#7b5554;
            color:white!important;
        }

        .icon-btn{
            width:46px;
            height:46px;
            border-radius:50%;
            border:none;
            background:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:18px;
            color:#6b5c5c;
            box-shadow:
                0 10px 25px rgba(123,85,84,0.08);
            position: relative;
        }

        /* ================= NOTIFICATION BADGE ================= */
        .notification-badge{
            position:absolute;
            top:-6px;
            right:-6px;
            min-width:20px;
            height:20px;
            padding:0 6px;
            border-radius:999px;
            background:#ff3b30;
            color:white;
            font-size:11px;
            font-weight:800;
            display:flex;
            align-items:center;
            justify-content:center;
            border:2px solid white;
            box-shadow:0 6px 16px rgba(0,0,0,0.15);
        }

        .logout-btn{
            border:none;
            background:#7b5554;
            color:white;
            padding:12px 20px;
            border-radius:999px;
            font-weight:700;
        }

        .main-wrapper{
            padding-top:40px;
            padding-bottom:80px;
        }

    </style>
</head>
<body>

@php
    $unreadCount = \App\Models\Notification::where('user_type', 'customer')
        ->where('user_id', session('customer_id'))
        ->where('is_read', 0)
        ->count();
@endphp

<!-- ================= NAVBAR ================= -->

<nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">

    <div class="container">

        <!-- LOGO -->
        <a class="brand-logo"
           href="{{ route('customer.home') }}">
            BeautyHome
        </a>

        <!-- MOBILE -->
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse"
             id="navbarMain">

            <ul class="navbar-nav mx-auto">

                <!-- HOME -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.home') ? 'active' : '' }}"
                       href="{{ route('customer.home') }}">
                        Trang chủ
                    </a>
                </li>

                <!-- SERVICES -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.services.*') ? 'active' : '' }}"
                       href="{{ route('customer.services.index') }}">
                        Dịch vụ
                    </a>
                </li>

                <!-- MY BOOKINGS -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.bookings.*') ? 'active' : '' }}"
                       href="{{ route('customer.bookings.index') }}">
                        Lịch đặt của tôi
                    </a>
                </li>

                <!-- EXPERT -->
                <li class="nav-item">
                    <a class="nav-link"
                       href="#">
                        Chuyên gia
                    </a>
                </li>

            </ul>

            <!-- RIGHT -->
            <div class="d-flex align-items-center gap-3">

                <!-- NOTIFICATIONS -->
                <a href="{{ route('customer.notifications.index') }}"
                   class="icon-btn">

                    <i class="bi bi-bell"></i>

                    @if($unreadCount > 0)
                        <span class="notification-badge">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif

                </a>

                <!-- PROFILE -->
                <a href="{{ route('customer.profile.index') }}"
                   class="icon-btn">
                    <i class="bi bi-person"></i>
                </a>

                <!-- LOGOUT -->
                <form action="{{ route('customer.logout') }}"
                      method="POST">
                    @csrf

                    <button type="submit"
                            class="logout-btn">
                        Đăng xuất
                    </button>
                </form>

            </div>

        </div>

    </div>

</nav>

<!-- ================= CONTENT ================= -->

<div class="container main-wrapper">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>