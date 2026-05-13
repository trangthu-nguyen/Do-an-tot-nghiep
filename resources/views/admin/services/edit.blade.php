@extends('admin.layout')

@section('title', 'Sửa dịch vụ')

@section('content')
<h2 class="fw-bold mb-4" style="color:#7b5554;">✏️ Sửa dịch vụ</h2>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card shadow-sm border-0" style="border-radius:18px;">
    <div class="card-body p-4">

        <form action="{{ route('admin.services.update', $service->service_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Danh mục</label>
                <select name="category_id" class="form-select" style="border-radius:14px;">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->category_id }}"
                            {{ $service->category_id == $cat->category_id ? 'selected' : '' }}>
                            {{ $cat->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tên dịch vụ</label>
                <input type="text" name="service_name"
                       class="form-control"
                       style="border-radius:14px;"
                       value="{{ $service->service_name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Giá</label>
                <input type="number" name="price"
                       class="form-control"
                       style="border-radius:14px;"
                       value="{{ $service->price }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Thời gian (phút)</label>
                <input type="number" name="duration"
                       class="form-control"
                       style="border-radius:14px;"
                       value="{{ $service->duration }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Mô tả</label>
                <textarea name="description" class="form-control"
                          style="border-radius:14px;" rows="4">{{ $service->description }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Ảnh hiện tại</label><br>

                @if($service->image)
                    <img src="{{ asset('uploads/services/' . $service->image) }}"
                         width="140"
                         style="border-radius:14px; border:1px solid #d4c2c2;"
                         class="mb-2">
                @else
                    <p class="text-muted">Chưa có ảnh</p>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Chọn ảnh mới (nếu muốn thay)</label>
                <input type="file" name="image" class="form-control" style="border-radius:14px;">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Trạng thái</label>
                <select name="status" class="form-select" style="border-radius:14px;">
                    <option value="1" {{ $service->status == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ $service->status == 0 ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button class="btn px-4"
                        style="background:#7b5554; color:white; border-radius:14px;">
                    Cập nhật
                </button>

                <a href="{{ route('admin.services.index') }}"
                   class="btn px-4"
                   style="background:#efeded; color:#504443; border-radius:14px;">
                    Quay lại
                </a>
            </div>

        </form>

    </div>
</div>
@endsection