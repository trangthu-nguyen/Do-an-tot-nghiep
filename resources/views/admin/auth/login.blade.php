@extends('admin.auth.layout')

@section('title', 'Admin Login')

@section('content')
<div class="login-card">

    <div class="login-title">Đăng nhập Admin</div>
    <div class="login-subtitle">
        Vui lòng đăng nhập để quản trị hệ thống HomeBeauty
    </div>

    {{-- Thông báo lỗi --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validation error --}}
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ url('/admin/login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   placeholder="Nhập email" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control"
                   placeholder="Nhập mật khẩu" required>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">
                    Ghi nhớ
                </label>
            </div>

            <a href="#" class="login-link small">
                Quên mật khẩu?
            </a>
        </div>

        <button type="submit" class="btn w-100 btn-login">
            Đăng nhập
        </button>
    </form>

    <div class="login-footer">
        HomeBeauty Admin Panel
    </div>

</div>
@endsection