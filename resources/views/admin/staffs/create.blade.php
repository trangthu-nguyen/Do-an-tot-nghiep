@extends('admin.layout')

@section('title', 'Thêm nhân viên mới')

@section('content')

<style>
    .staff-create{max-width:1050px;margin:auto}
    .back-link{color:#7b5554;text-decoration:none;font-weight:900;font-size:14px}
    .cardx{background:white;border:1px solid #f0e4e4;border-radius:24px;box-shadow:0 12px 32px rgba(123,85,84,.06);padding:30px}
    .title{font-size:26px;font-weight:900;color:#7b5554;margin-bottom:24px;font-family:'Noto Serif',serif}
    .form-label{font-size:13px;font-weight:900;color:#6d6060}
    .form-control{border:0;border-bottom:1px solid #eadede;border-radius:0;padding:12px 0;background:white}
    .form-control:focus{box-shadow:none;border-color:#7b5554}
    .grid{display:grid;grid-template-columns:1fr 260px;gap:24px;margin-top:24px}
    .skill-tags{display:flex;gap:12px;flex-wrap:wrap}
    .skill-tags label{border:1px solid #eadede;border-radius:999px;padding:9px 14px;font-weight:800;color:#7b5554;cursor:pointer}
    .skill-tags input{margin-right:6px}
    .status-box label{display:flex;align-items:center;justify-content:space-between;border:1px solid #eadede;border-radius:16px;padding:14px;margin-bottom:12px;font-weight:900;color:#5f5656}
    .btn-save{background:#7b5554;color:white;border:0;border-radius:14px;padding:12px 26px;font-weight:900}
    .btn-back{background:#efeded;color:#504443;border-radius:14px;padding:12px 24px;font-weight:900;text-decoration:none}
    @media(max-width:991px){.grid{grid-template-columns:1fr}}
</style>

<div class="staff-create">

    <a href="{{ route('admin.staffs.index') }}" class="back-link">
        ← Thêm nhân viên mới
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

    <form action="{{ route('admin.staffs.store') }}" method="POST" id="staffForm">
        @csrf

        <div class="cardx mt-4">
            <div class="title">Thông tin cơ bản</div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Họ tên</label>
                    <input type="text" name="full_name" class="form-control"
                           value="{{ old('full_name') }}" placeholder="Nguyễn Văn A" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}" placeholder="example@beautyhome.vn" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control"
                           value="{{ old('phone') }}" placeholder="0901 234 567" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Mật khẩu ban đầu</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="••••••••" required>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="address" class="form-control"
                           value="{{ old('address') }}" placeholder="123 Đường Spa, Quận 1, TP.HCM">
                </div>
            </div>
        </div>

        <div class="grid">
            <div class="cardx">
                <div class="title">Kỹ năng chuyên môn</div>

                <input type="hidden" name="skill" id="skillInput" value="{{ old('skill') }}">

                <div class="skill-tags">
                    @php
                        $oldSkills = old('skill') ? explode(', ', old('skill')) : [];
                        $skills = ['Chăm sóc da mặt','Massage Body','Làm móng Nails','Nối mi','Gội đầu dưỡng sinh','Trang điểm'];
                    @endphp

                    @foreach($skills as $skill)
                        <label>
                            <input type="checkbox"
                                   class="skill-check"
                                   value="{{ $skill }}"
                                   {{ in_array($skill, $oldSkills) ? 'checked' : '' }}>
                            {{ $skill }}
                        </label>
                    @endforeach
                </div>

                <div class="mt-4">
                    <label class="form-label">Giới thiệu ngắn</label>
                    <textarea name="bio" class="form-control" rows="4"
                              placeholder="Nhập mô tả ngắn về nhân viên...">{{ old('bio') }}</textarea>
                </div>
            </div>

            <div class="cardx">
                <div class="title">Trạng thái</div>

                <div class="status-box">
                    <label>
                        Đang làm việc
                        <input type="radio" name="status" value="1" {{ old('status', 1) == 1 ? 'checked' : '' }}>
                    </label>

                    <label>
                        Tạm nghỉ
                        <input type="radio" name="status" value="0" {{ old('status') == 0 ? 'checked' : '' }}>
                    </label>
                </div>

                <p class="text-muted small mb-0">
                    Nhân viên mới sẽ được hiển thị trong hệ thống nếu đang làm việc.
                </p>
            </div>
        </div>

        <div class="cardx mt-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="fw-bold" style="color:#7b5554;">Tạo hồ sơ nhân viên</div>
                <div class="text-muted small">Kiểm tra thông tin trước khi lưu vào hệ thống.</div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.staffs.index') }}" class="btn-back">Hủy</a>
                <button type="submit" class="btn-save">Lưu nhân viên</button>
            </div>
        </div>

    </form>

</div>

<script>
    function updateSkillInput() {
        let skills = [];

        document.querySelectorAll('.skill-check:checked').forEach(function (item) {
            skills.push(item.value);
        });

        document.getElementById('skillInput').value = skills.join(', ');
    }

    document.querySelectorAll('.skill-check').forEach(function (item) {
        item.addEventListener('change', updateSkillInput);
    });

    document.getElementById('staffForm').addEventListener('submit', updateSkillInput);
    updateSkillInput();
</script>

@endsection