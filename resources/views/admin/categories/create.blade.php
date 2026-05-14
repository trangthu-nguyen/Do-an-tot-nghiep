@extends('admin.layout')

@section('title', 'Add New Category')

@section('content')

<style>
    .form-card {
        background: white;
        border: 1px solid #f1e7e7;
        border-radius: 26px;
        padding: 30px;
        box-shadow: 0 14px 35px rgba(123,85,84,.07);
        max-width: 760px;
    }

    .form-title {
        font-size: 34px;
        font-weight: 900;
        color: #2f2323;
        margin-bottom: 8px;
    }

    .form-subtitle {
        color: #7d7272;
        margin-bottom: 28px;
    }

    .form-control {
        border-radius: 16px;
        padding: 13px 16px;
        border: 1px solid #eadede;
    }

    .btn-save {
        background: #7b5554;
        color: white;
        border: none;
        border-radius: 999px;
        padding: 12px 24px;
        font-weight: 800;
    }

    .btn-back {
        background: #efeded;
        color: #504443;
        border: none;
        border-radius: 999px;
        padding: 12px 24px;
        font-weight: 800;
        text-decoration: none;
    }
</style>

<div class="form-card">
    <h1 class="form-title">Add New Category</h1>
    <div class="form-subtitle">
        Tạo nhóm dịch vụ mới cho hệ thống BeautyHome.
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

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold">Tên danh mục</label>
            <input type="text"
                   name="category_name"
                   class="form-control"
                   value="{{ old('category_name') }}"
                   required>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Mô tả</label>
            <textarea name="description"
                      class="form-control"
                      rows="4">{{ old('description') }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button class="btn-save">
                Lưu danh mục
            </button>

            <a href="{{ route('admin.categories.index') }}"
               class="btn-back">
                Quay lại
            </a>
        </div>
    </form>
</div>

@endsection