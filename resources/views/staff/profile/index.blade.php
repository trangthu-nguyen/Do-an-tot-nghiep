@extends('staff.layout')

@section('title','Hồ sơ cá nhân')

@section('page-title','')

@section('content')

<style>
    :root{
        --primary:#7b5554;
        --primary-dark:#684847;
        --accent:#ebbab9;
        --soft:#fff7f7;
        --text:#2f2323;
        --muted:#7d7272;
        --border:#eadede;
    }

    .profile-title{
        font-size:38px;
        font-weight:900;
        font-family:'Noto Serif', serif;
        color:var(--text);
        margin-bottom:8px;
    }

    .profile-subtitle{
        color:var(--muted);
        font-weight:600;
        margin-bottom:30px;
    }

    .profile-layout{
        display:grid;
        grid-template-columns:280px 1fr;
        gap:26px;
        align-items:start;
    }

    @media(max-width:992px){
        .profile-layout{
            grid-template-columns:1fr;
        }
    }

    .profile-card,
    .info-card,
    .skill-card{
        background:white;
        border:1px solid var(--border);
        border-radius:26px;
        box-shadow:0 14px 38px rgba(123,85,84,0.07);
    }

    .profile-card{
        padding:26px 20px;
        text-align:center;
    }

    .avatar-wrap{
        position:relative;
        width:116px;
        height:116px;
        margin:0 auto 16px;
    }

    .avatar{
        width:116px;
        height:116px;
        border-radius:50%;
        object-fit:cover;
        border:6px solid rgba(235,186,185,0.45);
    }

    .avatar-edit{
        position:absolute;
        right:4px;
        bottom:8px;
        width:34px;
        height:34px;
        border-radius:50%;
        border:3px solid white;
        background:var(--accent);
        color:var(--primary);
        display:flex;
        align-items:center;
        justify-content:center;
        box-shadow:0 8px 20px rgba(123,85,84,0.14);
    }

    .staff-name{
        font-size:22px;
        font-weight:900;
        font-family:'Noto Serif', serif;
        color:var(--text);
        margin-bottom:4px;
    }

    .staff-role{
        color:var(--muted);
        font-size:13px;
        font-weight:700;
        margin-bottom:12px;
    }

    .tag-wrap{
        display:flex;
        justify-content:center;
        gap:8px;
        flex-wrap:wrap;
    }

    .tag{
        padding:7px 12px;
        border-radius:999px;
        background:rgba(235,186,185,0.28);
        color:var(--primary);
        font-weight:900;
        font-size:12px;
    }

    .special-card{
        background:white;
        border:1px solid var(--border);
        border-radius:22px;
        padding:18px;
        margin-top:18px;
        box-shadow:0 12px 28px rgba(123,85,84,0.05);
    }

    .special-title{
        color:#5f5656;
        font-weight:900;
        font-size:14px;
        margin-bottom:12px;
        text-align:left;
    }

    .special-tags{
        display:flex;
        gap:8px;
        flex-wrap:wrap;
    }

    .special-tag{
        padding:8px 12px;
        border-radius:999px;
        background:rgba(235,186,185,0.25);
        color:var(--primary);
        font-weight:800;
        font-size:12px;
    }

    .info-card{
        padding:28px;
    }

    .card-title{
        font-size:22px;
        font-weight:900;
        font-family:'Noto Serif', serif;
        color:var(--text);
        margin-bottom:22px;
    }

    .form-label{
        color:#5f5656;
        font-weight:900;
        font-size:13px;
        margin-bottom:7px;
    }

    .form-control{
        border:none;
        border-bottom:1px solid var(--border);
        border-radius:0;
        padding:10px 0;
        font-weight:700;
        color:var(--text);
        background:white;
    }

    .form-control:focus{
        box-shadow:none;
        border-color:var(--primary);
    }

    textarea.form-control{
        border:1px solid var(--border);
        border-radius:18px;
        padding:16px;
        min-height:115px;
        background:#fbf8f8;
        resize:vertical;
    }

    .btn-save{
        background:var(--accent);
        color:var(--primary);
        border:none;
        border-radius:999px;
        padding:12px 24px;
        font-weight:900;
        transition:0.25s;
    }

    .btn-save:hover{
        background:#e7aead;
        color:var(--primary-dark);
    }

    .btn-cancel{
        border:none;
        background:white;
        color:var(--muted);
        font-weight:900;
        padding:12px 18px;
    }

    .cert-section{
        margin-top:34px;
    }

    .cert-head{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:15px;
        margin-bottom:18px;
    }

    .cert-title{
        font-size:22px;
        font-weight:900;
        font-family:'Noto Serif', serif;
        color:var(--text);
        margin:0;
    }

    .add-cert{
        color:#d78e8d;
        text-decoration:none;
        font-size:13px;
        font-weight:900;
    }

    .cert-grid{
        display:grid;
        grid-template-columns:repeat(3, 1fr);
        gap:18px;
    }

    @media(max-width:992px){
        .cert-grid{
            grid-template-columns:1fr;
        }
    }

    .cert-card{
        background:white;
        border:1px solid var(--border);
        border-radius:22px;
        padding:20px;
        box-shadow:0 12px 28px rgba(123,85,84,0.06);
    }

    .cert-icon{
        width:42px;
        height:42px;
        border-radius:14px;
        background:rgba(235,186,185,0.30);
        color:var(--primary);
        display:flex;
        align-items:center;
        justify-content:center;
        margin-bottom:14px;
    }

    .cert-name{
        font-weight:900;
        color:var(--text);
        margin-bottom:5px;
    }

    .cert-time{
        color:var(--muted);
        font-weight:700;
        font-size:12px;
    }
</style>

@php
    $staffName = $staff->full_name ?? session('staff_name') ?? 'Nhân viên';
    $staffEmail = $staff->email ?? '';
    $staffPhone = $staff->phone ?? '';
    $staffAddress = $staff->address ?? '';
    $staffSkill = $staff->skill ?? 'Chuyên viên làm đẹp';
    $staffBio = $staff->bio ?? 'Chuyên viên BeautyHome với kinh nghiệm chăm sóc sắc đẹp tận tâm. Luôn hướng tới trải nghiệm thư giãn, an toàn và phù hợp với từng khách hàng.';
@endphp

<div class="profile-title">Hồ sơ cá nhân</div>
<div class="profile-subtitle">
    Cập nhật thông tin chuyên môn và thông tin liên hệ để khách hàng có thể tìm thấy bạn dễ dàng hơn.
</div>

<div class="profile-layout">

    {{-- LEFT --}}
    <div>

        <div class="profile-card">
            <div class="avatar-wrap">
                <img class="avatar"
                     src="https://i.pravatar.cc/200?img=47"
                     alt="avatar">

                <div class="avatar-edit">
                    <i class="bi bi-camera"></i>
                </div>
            </div>

            <div class="staff-name">
                {{ $staffName }}
            </div>

            <div class="staff-role">
                {{ $staffSkill }}
            </div>

            <div class="tag-wrap">
                <span class="tag">Organic</span>
                <span class="tag">Vegan</span>
            </div>
        </div>

        <div class="special-card">
            <div class="special-title">Chuyên môn</div>

            <div class="special-tags">
                <span class="special-tag">Chăm sóc da mặt</span>
                <span class="special-tag">Trị liệu Aroma</span>
                <span class="special-tag">Massage thư giãn</span>
                <span class="special-tag">Tư vấn da</span>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div>

        <form action="{{ route('staff.profile.update') }}" method="POST">
            @csrf

            <div class="info-card">

                <div class="card-title">Thông tin cơ bản</div>

                <div class="row g-4">

                    <div class="col-md-6">
                        <label class="form-label">Họ và tên</label>
                        <input type="text"
                               name="full_name"
                               class="form-control"
                               value="{{ old('full_name', $staffName) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email liên hệ</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $staffEmail) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone', $staffPhone) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Chức danh</label>
                        <input type="text"
                               name="skill"
                               class="form-control"
                               value="{{ old('skill', $staffSkill) }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Địa chỉ làm việc</label>
                        <input type="text"
                               name="address"
                               class="form-control"
                               value="{{ old('address', $staffAddress) }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Giới thiệu bản thân</label>
                        <textarea name="bio"
                                  class="form-control"
                                  placeholder="Nhập phần giới thiệu ngắn về kinh nghiệm và phong cách làm việc của bạn...">{{ old('bio', $staffBio) }}</textarea>
                    </div>

                </div>

                <div class="d-flex justify-content-end align-items-center gap-3 mt-4">
                    <button type="reset" class="btn-cancel">
                        Hủy thay đổi
                    </button>

                    <button type="submit" class="btn-save">
                        Lưu hồ sơ
                    </button>
                </div>

            </div>

        </form>

        <div class="cert-section">
            <div class="cert-head">
                <h4 class="cert-title">Kỹ năng & Chứng chỉ</h4>
                <a href="#" class="add-cert">+ Thêm chứng chỉ mới</a>
            </div>

            <div class="cert-grid">

                <div class="cert-card">
                    <div class="cert-icon">
                        <i class="bi bi-award"></i>
                    </div>
                    <div class="cert-name">Chứng chỉ Da liễu Quốc tế</div>
                    <div class="cert-time">Cấp bởi ITC Beauty · Năm 2019</div>
                </div>

                <div class="cert-card">
                    <div class="cert-icon">
                        <i class="bi bi-patch-check"></i>
                    </div>
                    <div class="cert-name">Liệu pháp Tinh dầu Nâng cao</div>
                    <div class="cert-time">Cấp bởi Academy of Aromatherapy · Năm 2021</div>
                </div>

                <div class="cert-card">
                    <div class="cert-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="cert-name">Vệ sinh Dịch tễ & An toàn</div>
                    <div class="cert-time">Chứng nhận Bộ Y tế · Năm 2023</div>
                </div>

            </div>
        </div>

    </div>

</div>

@endsection