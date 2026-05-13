@extends('customer.layout')

@section('title','Chỉnh sửa hồ sơ')

@section('content')

<style>
    :root{
        --primary:#7b5554;
        --primary-dark:#684847;
        --outline:#eadede;
        --text:#2f2323;
        --muted:#7d7272;
    }

    .profile-edit-wrapper{
        max-width: 520px;
        margin: 0 auto;
        background: white;
        border-radius: 28px;
        border: 1px solid var(--outline);
        box-shadow: 0 15px 45px rgba(123,85,84,0.12);
        padding: 30px;
    }

    .top-title{
        text-align:center;
        font-family:'Noto Serif', serif;
        font-weight: 800;
        font-size: 26px;
        margin-bottom: 18px;
        color: var(--text);
    }

    .back-btn{
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: white;
        border: 1px solid var(--outline);
        display:flex;
        align-items:center;
        justify-content:center;
        text-decoration:none;
        color: var(--primary);
        box-shadow: 0 10px 25px rgba(123,85,84,0.12);
        margin-bottom: 16px;
    }

    label{
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 6px;
    }

    .form-control, .form-select{
        border-radius: 16px;
        border: 1px solid var(--outline);
        padding: 12px 14px;
        font-weight: 700;
    }

    .form-control:focus, .form-select:focus{
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(123,85,84,0.15);
    }

    .btn-save{
        background: var(--primary);
        border: none;
        color: white;
        border-radius: 18px;
        padding: 14px;
        font-weight: 900;
        width: 100%;
        margin-top: 20px;
        transition:0.2s;
    }

    .btn-save:hover{
        background: var(--primary-dark);
    }
</style>

<div class="profile-edit-wrapper">

    <a href="{{ route('customer.profile.index') }}" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </a>

    <div class="top-title">Chỉnh sửa hồ sơ</div>

    <form action="{{ route('customer.profile.update') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Họ và tên</label>
            <input type="text" name="full_name" class="form-control"
                   value="{{ old('full_name', $customer->full_name) }}">
        </div>

        <div class="mb-3">
            <label>Số điện thoại</label>
            <input type="text" name="phone" class="form-control"
                   value="{{ old('phone', $customer->phone) }}">
        </div>

        <div class="mb-3">
            <label>Email liên hệ</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email', $customer->email) }}">
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label>Ngày sinh</label>
                <input type="date" name="birth_date" class="form-control"
                       value="{{ old('birth_date', $customer->birth_date) }}">
            </div>

            <div class="col-md-6">
                <label>Giới tính</label>
                <select name="gender" class="form-select">
                    <option value="">-- Chọn --</option>
                    <option value="Nam" {{ $customer->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ $customer->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                    <option value="Khác" {{ $customer->gender == 'Khác' ? 'selected' : '' }}>Khác</option>
                </select>
            </div>
        </div>

        <button class="btn-save">
            Lưu thay đổi <i class="bi bi-check2-circle"></i>
        </button>

    </form>

</div>

@endsection