<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
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
            background:linear-gradient(135deg, rgba(235,186,185,0.45), rgba(123,85,84,0.08));
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:30px;
        }

        .login-wrapper{
            width:100%;
            max-width:1000px;
            background:white;
            border-radius:28px;
            overflow:hidden;
            box-shadow:0 25px 80px rgba(123,85,84,0.18);
            border:1px solid var(--border);
        }

        .left-side{
            background:linear-gradient(140deg, rgba(123,85,84,0.9), rgba(235,186,185,0.75)),
                       url("https://images.pexels.com/photos/3997993/pexels-photo-3997993.jpeg?auto=compress&cs=tinysrgb&w=1200");
            background-size:cover;
            background-position:center;
            color:white;
            padding:60px 50px;
            position:relative;
        }

        .left-side::before{
            content:"";
            position:absolute;
            inset:0;
            background:rgba(123,85,84,0.55);
        }

        .left-content{
            position:relative;
            z-index:2;
        }

        .brand{
            font-size:34px;
            font-weight:900;
            font-family:'Noto Serif', serif;
            margin-bottom:20px;
        }

        .left-title{
            font-size:28px;
            font-weight:900;
            margin-bottom:15px;
            line-height:1.3;
        }

        .left-desc{
            font-size:15px;
            line-height:1.9;
            opacity:0.95;
        }

        .right-side{
            padding:55px 50px;
        }

        .form-title{
            font-size:30px;
            font-weight:900;
            font-family:'Noto Serif', serif;
            color:var(--primary);
            margin-bottom:8px;
        }

        .form-subtitle{
            color:var(--muted);
            margin-bottom:35px;
            font-size:15px;
        }

        .form-control{
            border-radius:18px;
            border:1px solid var(--border);
            padding:14px 16px;
            font-weight:600;
        }

        .form-control:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 4px rgba(123,85,84,0.15);
        }

        .btn-login{
            background:var(--primary);
            border:none;
            color:white;
            border-radius:999px;
            padding:14px 18px;
            font-weight:800;
            width:100%;
            transition:0.25s;
        }

        .btn-login:hover{
            background:var(--primary-dark);
            transform:scale(1.02);
        }

        .input-group-text{
            border-radius:18px;
            border:1px solid var(--border);
            background:#fff;
            color:var(--primary);
            font-size:18px;
        }

        .alert{
            border-radius:18px;
            font-weight:600;
        }

        @media(max-width:991px){
            .left-side{
                padding:40px 30px;
            }
            .right-side{
                padding:40px 30px;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper row g-0">

    <!-- LEFT -->
    <div class="col-lg-6 d-none d-lg-flex">
        <div class="left-side w-100">
            <div class="left-content">
                <div class="brand">BeautyHome</div>

                <div class="left-title">
                    Staff Panel Login
                </div>

                <div class="left-desc">
                    Đăng nhập để quản lý lịch làm việc, nhận lịch từ admin
                    và theo dõi các thông báo quan trọng.
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="col-lg-6">
        <div class="right-side">

            <div class="form-title">Đăng nhập nhân viên</div>
            <div class="form-subtitle">
                Vui lòng nhập thông tin tài khoản của bạn để tiếp tục.
            </div>

            @if(session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('staff.login.submit') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="fw-bold mb-2" style="color:var(--text);">Email</label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email"
                               name="email"
                               class="form-control"
                               placeholder="Nhập email"
                               required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="fw-bold mb-2" style="color:var(--text);">Mật khẩu</label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="Nhập mật khẩu"
                               required>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                </button>

            </form>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>