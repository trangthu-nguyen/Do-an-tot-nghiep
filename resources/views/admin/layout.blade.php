<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - BeautyHome')</title>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Noto+Serif:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/luminous-beauty.css') }}">

    <style>
        :root{
            --primary:#7b5554;
            --bg:#faf9f9;
            --text:#1b1c1c;
            --muted:#504443;
            --outline:#d4c2c2;
            --radius:16px;
        }

        body{font-family:'Manrope',system-ui,sans-serif;background:var(--bg);color:var(--text)}
        h1,h2,h3,h4,h5{font-family:'Noto Serif',serif}

        .sidebar{width:260px;background:rgba(255,255,255,.92);backdrop-filter:blur(12px);border-right:1px solid #e9e8e8;min-height:100vh;padding:24px 16px}
        .sidebar-logo{font-family:'Noto Serif',serif;font-size:24px;font-weight:700;color:var(--primary);text-align:center;margin-bottom:40px}
        .sidebar-menu a{display:flex;align-items:center;gap:12px;padding:14px 16px;border-radius:14px;color:var(--muted);text-decoration:none;font-weight:500;margin-bottom:6px;transition:.25s}
        .sidebar-menu a:hover{background:rgba(235,186,185,.18);color:var(--primary)}
        .sidebar-menu a.active{background:var(--primary);color:white}

        .content-area{flex:1;padding:30px}
        .admin-header{background:rgba(255,255,255,.9);backdrop-filter:blur(10px);border-radius:var(--radius);padding:18px 24px;box-shadow:0 6px 18px rgba(123,85,84,.10);margin-bottom:30px;border:1px solid rgba(212,194,194,.6)}
        .btn-primary-ui{background:var(--primary);color:white;border-radius:14px;padding:10px 16px;font-weight:600;border:none;text-decoration:none;transition:.2s}
        .btn-primary-ui:hover{background:#6d4848;color:white}
        .card-ui{background:rgba(255,255,255,.9);backdrop-filter:blur(12px);border-radius:var(--radius);padding:20px;border:1px solid rgba(212,194,194,.6);box-shadow:0 10px 30px rgba(123,85,84,.08)}
        table thead{background:rgba(235,186,185,.25)}
        table thead th{color:var(--muted);font-weight:700;font-size:14px}
        table tbody td{font-size:14px}

        .admin-bell{
            width:44px;
            height:44px;
            border-radius:50%;
            background:white;
            color:var(--primary);
            display:flex;
            align-items:center;
            justify-content:center;
            text-decoration:none;
            position:relative;
            box-shadow:0 8px 22px rgba(123,85,84,.10);
            border:1px solid rgba(212,194,194,.6);
            overflow:visible;
        }

        .admin-bell i{font-size:19px}

        .admin-bell-badge{
            position:absolute;
            top:-7px;
            right:-7px;
            min-width:22px;
            height:22px;
            padding:0 6px;
            border-radius:999px;
            background:#ff3b30;
            color:white;
            font-size:11px;
            font-weight:900;
            display:flex;
            align-items:center;
            justify-content:center;
            border:2px solid white;
            line-height:1;
            z-index:99;
        }
    </style>

    @yield('css')
</head>

<body>

@php
    $adminUnreadCount = \App\Models\Notification::where('user_type', 'admin')
        ->where('is_read', 0)
        ->count();
@endphp

<div class="d-flex">

    <aside class="sidebar">
        <div class="sidebar-logo">BeautyHome</div>

        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i> Dashboard
            </a>

            

            <a href="{{ route('admin.services.index') }}"
   class="{{ request()->routeIs('admin.services.*') || request()->routeIs('admin.categories.*') ? 'active' : '' }}">
    <i class="bi bi-box-seam"></i> Dịch vụ
</a>

            <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Lịch đặt
            </a>

            <a href="{{ route('admin.staffs.index') }}" class="{{ request()->routeIs('admin.staffs.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Nhân viên
            </a>

            <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill"></i> Khách hàng
            </a>

            <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card"></i> Thanh toán
            </a>
            
            <a href="{{ route('admin.feedbacks.index') }}" class="{{ request()->routeIs('admin.feedbacks.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i> Đánh giá
            </a>

            

            <hr>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn w-100 text-danger fw-semibold text-start"
                        style="border-radius:14px;padding:14px 16px;">
                    <i class="bi bi-box-arrow-right"></i> Đăng xuất
                </button>
            </form>
        </div>
    </aside>

    <main class="content-area flex-grow-1">
        <div class="admin-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold" style="color:var(--primary);">
                @yield('title', 'Dashboard')
            </h4>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.notifications.index') }}" class="admin-bell" title="Thông báo">
                    <i class="bi bi-bell"></i>

                    @if($adminUnreadCount > 0)
                        <span class="admin-bell-badge">
                            {{ $adminUnreadCount > 9 ? '9+' : $adminUnreadCount }}
                        </span>
                    @endif
                </a>

                <div class="text-muted">
                    Xin chào,
                    <strong style="color:var(--primary);">
                        {{ session('admin_name') ?? 'Admin' }}
                    </strong>
                </div>
            </div>
        </div>

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('js')

</body>
</html>