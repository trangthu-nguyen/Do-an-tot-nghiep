<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Staff Panel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Noto+Serif:wght@600;700&display=swap" rel="stylesheet">

    <style>
        :root{
            --primary:#7b5554;
            --primary-dark:#684847;
            --primary-light:#ebbab9;
            --bg:#faf9f9;
            --text:#2f2323;
            --muted:#7d7272;
            --border:#eadede;
        }

        body{
            font-family:'Manrope',sans-serif;
            background:var(--bg);
            color:var(--text);
        }

        h1,h2,h3,h4,h5{
            font-family:'Noto Serif',serif;
        }

        .staff-wrapper{
            display:flex;
            min-height:100vh;
        }

        .sidebar{
            width:260px;
            background:white;
            border-right:1px solid var(--border);
            padding:26px 18px;
            position:sticky;
            top:0;
            height:100vh;
        }

        .brand{
            font-size:28px;
            font-weight:900;
            color:var(--primary);
            font-family:'Noto Serif', serif;
            margin-bottom:28px;
        }

        .sidebar-menu{
            display:flex;
            flex-direction:column;
            gap:10px;
        }

        .sidebar-link{
            display:flex;
            align-items:center;
            gap:12px;
            padding:12px 16px;
            border-radius:16px;
            text-decoration:none;
            font-weight:700;
            color:#5f5656;
            transition:0.25s;
        }

        .sidebar-link i{
            font-size:18px;
            color:var(--primary);
        }

        .sidebar-link:hover{
            background:rgba(235,186,185,0.25);
            color:var(--primary);
        }

        .sidebar-link.active{
            background:rgba(123,85,84,0.12);
            color:var(--primary);
            border:1px solid rgba(123,85,84,0.15);
        }

        .main-content{
            flex:1;
            padding:35px 40px;
        }

        .topbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:28px;
        }

        .topbar-title{
            font-size:22px;
            font-weight:900;
            color:var(--primary);
        }

        .topbar-right{
            display:flex;
            align-items:center;
            gap:14px;
        }

        .icon-btn{
            width:44px;
            height:44px;
            border-radius:50%;
            border:1px solid var(--border);
            background:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:18px;
            color:var(--primary);
            box-shadow:0 10px 25px rgba(123,85,84,0.08);
            position:relative;
            text-decoration:none;
            transition:0.25s;
        }

        .icon-btn:hover{
            background:rgba(235,186,185,0.25);
            color:var(--primary-dark);
            transform:translateY(-2px);
        }

        .profile-box{
            display:flex;
            align-items:center;
            gap:10px;
            padding:8px 14px;
            border-radius:999px;
            background:white;
            border:1px solid var(--border);
            text-decoration:none;
            transition:0.25s;
        }

        .profile-box:hover{
            background:rgba(235,186,185,0.18);
            transform:translateY(-2px);
        }

        .profile-avatar{
            width:36px;
            height:36px;
            border-radius:50%;
            object-fit:cover;
            border:2px solid rgba(123,85,84,0.15);
        }

        .profile-name{
            font-weight:800;
            font-size:14px;
            color:var(--text);
        }

        .profile-role{
            font-size:12px;
            color:var(--muted);
        }

        .alert{
            border-radius:18px;
            font-weight:600;
        }

        .logout-btn{
            width:100%;
            margin-top:30px;
            background:var(--primary);
            color:white;
            border:none;
            padding:12px 16px;
            border-radius:16px;
            font-weight:800;
            transition:0.25s;
        }

        .logout-btn:hover{
            background:var(--primary-dark);
        }

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

        @media(max-width:991px){
            .sidebar{
                display:none;
            }

            .main-content{
                padding:25px 15px;
            }
        }
    </style>
</head>

@php
    $staffUnreadCount = \App\Models\Notification::where('user_type', 'staff')
        ->where('user_id', session('staff_id'))
        ->where('is_read', 0)
        ->count();

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

    $staffAvatar = $femalePortraits[
        (session('staff_id') ?? 1) % count($femalePortraits)
    ];
@endphp

<body>

<div class="staff-wrapper">

    <aside class="sidebar">
        <div class="brand">BeautyHome</div>

        <div class="sidebar-menu">
            <a href="{{ route('staff.bookings.index') }}"
               class="sidebar-link {{ request()->routeIs('staff.bookings.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i>
                Lịch làm việc
            </a>

            <a href="{{ route('staff.jobMarket') }}"
               class="sidebar-link {{ request()->routeIs('staff.jobMarket') ? 'active' : '' }}">
                <i class="bi bi-shop"></i>
                Danh sách lịch đặt
            </a>

            <a href="{{ route('staff.workHistory') }}"
               class="sidebar-link {{ request()->routeIs('staff.workHistory') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                Lịch sử công việc
            </a>

            <a href="{{ route('staff.scheduleRegistration') }}"
               class="sidebar-link {{ request()->routeIs('staff.scheduleRegistration') ? 'active' : '' }}">
                <i class="bi bi-calendar-plus"></i>
                <span>Đăng ký lịch làm</span>
            </a>
        </div>

        <form action="{{ route('staff.logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="bi bi-box-arrow-right"></i> Đăng xuất
            </button>
        </form>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <div class="topbar-title">
                @yield('page-title','Staff Dashboard')
            </div>

            <div class="topbar-right">
                <a href="{{ route('staff.notifications.index') }}"
                   class="icon-btn"
                   title="Thông báo">
                    <i class="bi bi-bell"></i>

                    @if($staffUnreadCount > 0)
                        <span class="notification-badge">
                            {{ $staffUnreadCount > 9 ? '9+' : $staffUnreadCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('staff.profile.index') }}"
                   class="profile-box"
                   title="Hồ sơ cá nhân">

                    <img class="profile-avatar"
                         src="{{ $staffAvatar }}"
                         alt="avatar">

                    <div>
                        <div class="profile-name">
                            {{ session('staff_name') ?? 'Nhân viên' }}
                        </div>
                        <div class="profile-role">
                            Staff
                        </div>
                    </div>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')

    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>