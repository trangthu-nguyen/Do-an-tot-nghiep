@extends('admin.layout')

@section('title', 'Chỉnh sửa nhân viên')

@section('content')

@php
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

    $avatarImage = $femalePortraits[
        $staff->staff_id % count($femalePortraits)
    ];
@endphp

<style>
    .edit-wrap{max-width:1050px;margin:auto}
    .back-link{color:#7b5554;text-decoration:none;font-weight:900;font-size:13px}
    .edit-card,.stat-card{background:white;border:1px solid #f0e4e4;border-radius:24px;box-shadow:0 12px 32px rgba(123,85,84,.06)}
    .edit-card{padding:34px;margin-top:20px}
    .edit-grid{display:grid;grid-template-columns:260px 1fr;gap:36px;align-items:start}
    .profile-side{text-align:center}
    .staff-avatar{width:170px;height:170px;border-radius:50%;object-fit:cover;border:6px solid #f1dddd}
    .staff-name{font-size:24px;font-weight:900;color:#7b5554;margin-top:18px;font-family:'Noto Serif',serif}
    .staff-role{color:#7d7272;font-weight:700}
    .status-line{display:flex;align-items:center;justify-content:center;gap:10px;margin-top:28px;font-weight:800;color:#5f5656}
    .form-label{font-size:13px;font-weight:900;color:#6d6060}
    .form-control,.form-select{border:1px solid #eadede;border-radius:15px;padding:12px 14px;background:#fbf8f8}
    .form-control:focus,.form-select:focus{border-color:#7b5554;box-shadow:0 0 0 .15rem rgba(123,85,84,.12)}
    textarea.form-control{min-height:90px}
    .btn-save{background:#7b5554;color:white;border:0;border-radius:14px;padding:12px 24px;font-weight:900}
    .btn-save:hover{background:#684847;color:white}
    .btn-cancel{background:white;color:#7b5554;border:1px solid #eadede;border-radius:14px;padding:12px 24px;font-weight:900;text-decoration:none}
    .stats{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:26px}
    .stat-card{padding:22px}
    .stat-icon{width:42px;height:42px;border-radius:14px;background:#ffe1e0;color:#7b5554;display:flex;align-items:center;justify-content:center;margin-bottom:12px}
    .stat-label{font-size:12px;font-weight:900;color:#9b8f8f}
    .stat-num{font-size:24px;font-weight:900;color:#7b5554}
    @media(max-width:991px){.edit-grid,.stats{grid-template-columns:1fr}.profile-side{text-align:left}.status-line{justify-content:flex-start}}
</style>

<div class="edit-wrap">

    <a href="{{ route('admin.staffs.index') }}" class="back-link">
        ← Quay lại
    </a>

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="edit-card">
        <form action="{{ route('admin.staffs.update', $staff->staff_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="edit-grid">

                <div class="profile-side">
                    <img src="{{ $avatarImage }}"
                         class="staff-avatar"
                         alt="{{ $staff->full_name }}">

                    <div class="staff-name">
                        {{ $staff->full_name }}
                    </div>

                    <div class="staff-role">
                        {{ $staff->skill ?? 'Chuyên viên làm đẹp' }}
                    </div>

                    <div class="status-line">
                        <span>Trạng thái</span>

                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="status" value="0">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="status"
                                   value="1"
                                   {{ old('status', $staff->status) == 1 ? 'checked' : '' }}>
                        </div>

                        <span>Hoạt động</span>
                    </div>
                </div>

                <div>
                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="form-label">Họ tên</label>
                            <input type="text"
                                   name="full_name"
                                   class="form-control"
                                   value="{{ old('full_name', $staff->full_name) }}"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text"
                                   name="phone"
                                   class="form-control"
                                   value="{{ old('phone', $staff->phone) }}"
                                   required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Email</label>
                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ old('email', $staff->email) }}"
                                   required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Để trống nếu không đổi mật khẩu">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="address"
                                      class="form-control">{{ old('address', $staff->address) }}</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Kỹ năng</label>
                            <input type="text"
                                   name="skill"
                                   class="form-control"
                                   value="{{ old('skill', $staff->skill) }}"
                                   placeholder="Ví dụ: Skincare, Nails, Massage">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Giới thiệu</label>
                            <textarea name="bio"
                                      class="form-control"
                                      placeholder="Nhập mô tả ngắn về nhân viên...">{{ old('bio', $staff->bio) }}</textarea>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('admin.staffs.index') }}" class="btn-cancel">
                            Hủy
                        </a>

                        <button type="submit" class="btn-save">
                            Lưu thay đổi
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="stat-label">Tổng số lịch hẹn</div>
            <div class="stat-num">{{ $staff->bookings()->count() }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-star-fill"></i>
            </div>
            <div class="stat-label">Đánh giá</div>
            <div class="stat-num">4.9/5.0</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-label">Trạng thái</div>
            <div class="stat-num">
                {{ $staff->status == 1 ? 'Hoạt động' : 'Bận' }}
            </div>
        </div>
    </div>

</div>

@endsection