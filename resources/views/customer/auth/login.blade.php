<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập khách hàng</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Noto+Serif:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #7b5554;
            --primary-dark: #6d4848;

            --background: #faf9f9;
            --surface: #ffffff;
            --surface-container: #efeded;

            --text: #1b1c1c;
            --text-muted: #504443;

            --outline: #d4c2c2;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: linear-gradient(135deg, #faf9f9 0%, #efeded 100%);
            color: var(--text);
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 24px;
        }

        .auth-card {
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 36px 34px;
            border: 1px solid rgba(212, 194, 194, 0.8);
            box-shadow: 0 18px 45px rgba(123, 85, 84, 0.10);
        }

        .auth-title {
            font-family: 'Noto Serif', serif;
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .auth-subtitle {
            text-align: center;
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 26px;
            line-height: 1.6;
        }

        .form-label {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            color: var(--text);
        }

        .form-control {
            border-radius: 14px;
            padding: 12px 14px;
            border: 1px solid var(--outline);
            background: rgba(250, 249, 249, 0.8);
            transition: 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(123, 85, 84, 0.15);
            background: #fff;
        }

        .btn-auth {
            border-radius: 14px;
            padding: 12px;
            font-weight: 600;
            font-size: 15px;
            background: var(--primary);
            border: none;
            transition: 0.2s;
            color: white;
        }

        .btn-auth:hover {
            background: var(--primary-dark);
        }

        .auth-footer {
            margin-top: 18px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-title">Đăng nhập</div>
        <div class="auth-subtitle">
            Vui lòng đăng nhập để tiếp tục đặt lịch tại HomeBeauty
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="border-radius:14px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" style="border-radius:14px;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('customer.login.submit') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       placeholder="Nhập email" value="{{ old('email') }}" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control"
                       placeholder="Nhập mật khẩu" required>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button class="btn btn-auth w-100">Đăng nhập</button>
        </form>

        <div class="auth-footer">
            Chưa có tài khoản? <a href="{{ route('customer.register') }}">Đăng ký</a>
        </div>

    </div>
</div>

</body>
</html>