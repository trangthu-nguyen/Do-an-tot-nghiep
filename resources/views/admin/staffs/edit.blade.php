@extends('admin.layout')

@section('title', 'Sửa nhân viên')

@section('content')

<h2 class="fw-bold mb-4" style="color:#7b5554;">✏️ Sửa nhân viên</h2>

<div class="card shadow-sm border-0" style="border-radius:18px;">
    <div class="card-body p-4">

        <form action="{{ route('admin.staffs.update', $staff->staff_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Họ tên</label>
                <input type="text" name="full_name"
                       value="{{ old('full_name', $staff->full_name) }}"
                       class="form-control" style="border-radius:14px;">
                @error('full_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email"
                       value="{{ old('email', $staff->email) }}"
                       class="form-control" style="border-radius:14px;">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Số điện thoại</label>
                <input type="text" name="phone"
                       value="{{ old('phone', $staff->phone) }}"
                       class="form-control" style="border-radius:14px;">
                @error('phone')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Mật khẩu (để trống nếu không đổi)</label>
                <input type="password" name="password"
                       class="form-control" style="border-radius:14px;">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Địa chỉ</label>
                <input type="text" name="address"
                       value="{{ old('address', $staff->address) }}"
                       class="form-control" style="border-radius:14px;">
                @error('address')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Kỹ năng</label>
                <input type="text" name="skill"
                       value="{{ old('skill', $staff->skill) }}"
                       class="form-control" style="border-radius:14px;">
                @error('skill')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Trạng thái</label>
                <select name="status" class="form-select" style="border-radius:14px;">
                    <option value="1" {{ old('status', $staff->status) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('status', $staff->status) == 0 ? 'selected' : '' }}>Ngưng</option>
                </select>
                @error('status')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button class="btn px-4"
                        style="background:#7b5554; color:white; border-radius:14px;">
                    Cập nhật
                </button>

                <a href="{{ route('admin.staffs.index') }}"
                   class="btn px-4"
                   style="background:#efeded; color:#504443; border-radius:14px;">
                    Quay lại
                </a>
            </div>

        </form>

    </div>
</div>

@endsection