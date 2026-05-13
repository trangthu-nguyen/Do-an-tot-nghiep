<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - BeautyHome')</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Noto+Serif:wght@500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Main CSS --}}
    <link rel="stylesheet" href="{{ asset('css/luminous-beauty.css') }}">

    <style>
        :root {
            --primary: #7b5554;
            --primary-light: #ebbab9;

            --background: #faf9f9;
            --surface: #ffffff;
            --surface-container: #efeded;

            --text: #1b1c1c;
            --text-muted: #504443;

            --outline: #d4c2c2;

            --radius: 16px;
        }

        body {
            font-family: 'Manrope', system-ui, sans-serif;
            background-color: var(--background);
            color: var(--text);
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Noto Serif', serif;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border-right: 1px solid #e9e8e8;
            min-height: 100vh;
            padding: 24px 16px;
        }

        .sidebar-logo {
            font-family: 'Noto Serif', serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            text-align: center;
            margin-bottom: 40px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 14px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 6px;
            transition: all 0.25s ease;
        }

        .sidebar-menu a:hover {
            background: rgba(235, 186, 185, 0.18);
            color: var(--primary);
        }

        .sidebar-menu a.active {
            background: var(--primary);
            color: white;
        }

        .content-area {
            flex: 1;
            padding: 30px;
        }

        .admin-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: var(--radius);
            padding: 18px 24px;
            box-shadow: 0 6px 18px rgba(123, 85, 84, 0.10);
            margin-bottom: 30px;
            border: 1px solid rgba(212, 194, 194, 0.6);
        }

        /* Buttons */
        .btn-primary-ui {
            background: var(--primary);
            color: white;
            border-radius: 14px;
            padding: 10px 16px;
            font-weight: 600;
            border: none;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-primary-ui:hover {
            background: #6d4848;
            color: white;
        }

        /* Cards */
        .card-ui {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-radius: var(--radius);
            padding: 20px;
            border: 1px solid rgba(212, 194, 194, 0.6);
            box-shadow: 0 10px 30px rgba(123, 85, 84, 0.08);
        }

        /* Table */
        table thead {
            background: rgba(235, 186, 185, 0.25);
        }

        table thead th {
            color: var(--text-muted);
            font-weight: 700;
            font-size: 14px;
        }

        table tbody td {
            font-size: 14px;
        }

    </style>

    @yield('css')
</head>

<body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-logo">BeautyHome</div>

        <div class="sidebar-menu">

            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i> Dashboard
            </a>

            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Danh mục
            </a>

            <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Dịch vụ
            </a>

            <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Quản lý đặt lịch
            </a>

            <a href="{{ route('admin.staffs.index') }}" class="{{ request()->routeIs('admin.staffs.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Quản lý nhân viên
            </a>

            <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill"></i> Quản lý khách hàng
            </a>

            <a href="{{ route('admin.feedbacks.index') }}" class="{{ request()->routeIs('admin.feedbacks.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i> Đánh giá
            </a>

            <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card"></i> Thanh toán
            </a>

            <a href="{{ route('admin.notifications.index') }}" class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Thông báo
            </a>

            <hr>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn w-100 text-danger fw-semibold text-start"
                        style="border-radius:14px; padding:14px 16px;">
                    <i class="bi bi-box-arrow-right"></i> Đăng xuất
                </button>
            </form>

        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content-area flex-grow-1">
        <div class="admin-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold" style="color: var(--primary);">
                @yield('title', 'Dashboard')
            </h4>

            <div class="text-muted">
                Xin chào, <strong style="color: var(--primary);">
                    {{ session('admin_name') ?? 'Admin' }}
                </strong>
            </div>
        </div>

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('js')

</body>
</html>