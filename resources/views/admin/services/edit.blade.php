@extends('admin.layout')

@section('title', 'Cập nhật dịch vụ')

@section('content')

<style>
    .service-form{max-width:980px;margin:auto}
    .form-hero,.form-card{background:white;border:1px solid #f0e4e4;border-radius:24px;box-shadow:0 12px 32px rgba(123,85,84,.06)}
    .form-hero{padding:24px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;gap:20px}
    .mini{font-size:12px;font-weight:900;color:#9b8f8f}
    .title{font-size:30px;font-weight:900;color:#2f2323;margin:6px 0}
    .sub{color:#7d7272;font-size:14px;margin:0}
    .form-card{padding:26px}
    .form-label{font-weight:800;color:#5f5656}
    .form-control,.form-select{border-radius:15px;border:1px solid #eadede;padding:12px 14px}
    .form-control:focus,.form-select:focus{border-color:#7b5554;box-shadow:0 0 0 .15rem rgba(123,85,84,.12)}
    .preview-img{width:150px;height:110px;object-fit:cover;border-radius:18px;border:1px solid #eadede}
    .btn-save{background:#7b5554;color:white;border:0;border-radius:999px;padding:12px 24px;font-weight:900}
    .btn-back{background:#efeded;color:#504443;border-radius:999px;padding:12px 24px;font-weight:900;text-decoration:none}
</style>

<div class="service-form">

    <div class="form-hero">
        <div>
            
            <p class="sub">Chỉnh sửa thông tin, hình ảnh, giá và trạng thái của dịch vụ.</p>
        </div>

        <a href="{{ route('admin.services.index') }}" class="btn-back">
            ← Quay lại
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <form action="{{ route('admin.services.update', $service->service_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Tên dịch vụ</label>
                    <input type="text" name="service_name" class="form-control"
                           value="{{ old('service_name', $service->service_name) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Danh mục</label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->category_id }}"
                                {{ old('category_id', $service->category_id) == $cat->category_id ? 'selected' : '' }}>
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Giá dịch vụ</label>
                    <input type="number" name="price" class="form-control"
                           value="{{ old('price', $service->price) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Thời gian</label>
                    <input type="number" name="duration" class="form-control"
                           value="{{ old('duration', $service->duration) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="1" {{ old('status', $service->status) == 1 ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ old('status', $service->status) == 0 ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Ảnh hiện tại</label><br>
                    @if($service->image)
                        <img src="{{ asset('uploads/services/' . $service->image) }}" class="preview-img">
                    @else
                        <div class="text-muted">Chưa có ảnh</div>
                    @endif
                </div>

                <div class="col-md-8">
                    <label class="form-label">Chọn ảnh mới</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">Bỏ trống nếu không muốn đổi ảnh.</small>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control" rows="5">{{ old('description', $service->description) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.services.index') }}" class="btn-back">Hủy</a>
                <button class="btn-save">Cập nhật dịch vụ</button>
            </div>
        </form>
    </div>

</div>

@endsection