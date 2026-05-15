<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng ký khách hàng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Noto+Serif:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root{
            --primary:#7b5554;
            --primary-dark:#6d4848;
            --background:#faf9f9;
            --surface:#ffffff;
            --text:#1b1c1c;
            --text-muted:#504443;
            --outline:#d4c2c2;
        }

        body{
            font-family:'Manrope',sans-serif;
            background:linear-gradient(135deg,#faf9f9 0%,#efeded 100%);
            color:var(--text);
        }

        .auth-wrapper{
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:24px;
        }

        .auth-card{
            width:100%;
            max-width:520px;
            background:rgba(255,255,255,.9);
            backdrop-filter:blur(12px);
            border-radius:24px;
            padding:36px 34px;
            border:1px solid rgba(212,194,194,.8);
            box-shadow:0 18px 45px rgba(123,85,84,.10);
        }

        .auth-title{
            font-family:'Noto Serif',serif;
            font-size:30px;
            font-weight:700;
            text-align:center;
            color:var(--primary);
            margin-bottom:10px;
        }

        .auth-subtitle{
            text-align:center;
            font-size:14px;
            color:var(--text-muted);
            margin-bottom:28px;
            line-height:1.6;
        }

        .form-label{
            font-weight:700;
            font-size:14px;
            margin-bottom:8px;
        }

        .form-control{
            border-radius:14px;
            padding:12px 14px;
            border:1px solid var(--outline);
            background:#faf9f9;
        }

        .form-control:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 4px rgba(123,85,84,.15);
        }

        .password-box{
            position:relative;
        }

        .password-box .form-control{
            padding-right:48px;
        }

        .toggle-password{
            position:absolute;
            right:14px;
            top:50%;
            transform:translateY(-50%);
            border:none;
            background:none;
            color:#7b5554;
            font-size:18px;
            cursor:pointer;
        }

        .btn-auth{
            border-radius:14px;
            padding:12px;
            font-weight:700;
            font-size:15px;
            background:var(--primary);
            border:none;
            color:white;
            transition:.2s;
        }

        .btn-auth:hover{
            background:var(--primary-dark);
        }

        .auth-footer{
            margin-top:18px;
            text-align:center;
            font-size:13px;
            color:var(--text-muted);
        }

        .auth-footer a{
            color:var(--primary);
            font-weight:700;
            text-decoration:none;
        }

        .auth-footer a:hover{
            text-decoration:underline;
        }
    </style>
</head>

<body>

<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-title">Đăng ký</div>

        <div class="auth-subtitle">
            Tạo tài khoản để đặt lịch và trải nghiệm dịch vụ BeautyHome
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

        <form action="{{ route('customer.register.submit') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Họ tên</label>

                <input type="text"
                       name="full_name"
                       class="form-control"
                       placeholder="Nhập họ tên"
                       value="{{ old('full_name') }}"
                       required>

                @error('full_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       placeholder="Nhập email"
                       value="{{ old('email') }}"
                       required>

                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>

                <input type="text"
                       name="phone"
                       class="form-control"
                       placeholder="Nhập số điện thoại"
                       value="{{ old('phone') }}"
                       required>

                @error('phone')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>

                <div class="password-box">
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Nhập mật khẩu"
                           required>

                    <button type="button"
                            class="toggle-password"
                            onclick="togglePassword('password', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>

                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Xác nhận mật khẩu</label>

                <div class="password-box">
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           class="form-control"
                           placeholder="Nhập lại mật khẩu"
                           required>

                    <button type="button"
                            class="toggle-password"
                            onclick="togglePassword('password_confirmation', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <button class="btn btn-auth w-100">
                Đăng ký
            </button>
        </form>

        <div class="auth-footer">
            Đã có tài khoản?
            <a href="{{ route('customer.login') }}">
                Đăng nhập
            </a>
        </div>

    </div>
</div>

<script>
    function togglePassword(id, button)
    {
        const input = document.getElementById(id);
        const icon  = button.querySelector('i');

        if(input.type === 'password'){
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }else{
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>

</body>
</html>