<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Login')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Fonts theo Design System --}}
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Noto+Serif:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Theme chung --}}
    <link rel="stylesheet" href="{{ asset('css/luminous-beauty.css') }}">

    <style>
        /* Chỉ giữ style riêng cho login (không trùng theme) */

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: linear-gradient(135deg, #faf9f9 0%, #efeded 100%);
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 36px 34px;
            border: 1px solid rgba(212, 194, 194, 0.8);
            box-shadow: 0 18px 45px rgba(123, 85, 84, 0.10);
        }

        .login-title {
            font-family: 'Noto Serif', serif;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
            text-align: center;
            color: var(--primary);
        }

        .login-subtitle {
            text-align: center;
            font-size: 14px;
            color: var(--text-soft);
            margin-bottom: 28px;
            line-height: 1.6;
        }

        .btn-login {
            border-radius: 14px;
            padding: 12px;
            font-weight: 600;
            font-size: 15px;
            background: var(--primary);
            border: none;
            transition: 0.2s;
            color: white;
        }

        .btn-login:hover {
            background: var(--primary-dark);
        }

        .login-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 13px;
            color: var(--text-soft);
        }

        a.login-link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        a.login-link:hover {
            text-decoration: underline;
        }
    </style>

    @yield('css')
</head>
<body>

<div class="login-wrapper">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@yield('js')
</body>
</html>