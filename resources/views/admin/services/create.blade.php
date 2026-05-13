@extends('admin.layout')

@section('title', 'Thêm dịch vụ')

@section('content')
<h2 class="fw-bold mb-4" style="color:#7b5554;">➕ Thêm dịch vụ</h2>

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

        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Danh mục</label>
                <select name="category_id" class="form-select" style="border-radius:14px;">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tên dịch vụ</label>
                <input type="text" name="service_name" class="form-control" style="border-radius:14px;" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Giá</label>
                <input type="number" name="price" class="form-control" style="border-radius:14px;" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Thời gian (phút)</label>
                <input type="number" name="duration" class="form-control" style="border-radius:14px;" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Mô tả</label>
                <textarea name="description" class="form-control" style="border-radius:14px;" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Ảnh</label>
                <input type="file" name="image" class="form-control" style="border-radius:14px;">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Trạng thái</label>
                <select name="status" class="form-select" style="border-radius:14px;">
                    <option value="1">Hoạt động</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button class="btn px-4"
                        style="background:#7b5554; color:white; border-radius:14px;">
                    Lưu
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