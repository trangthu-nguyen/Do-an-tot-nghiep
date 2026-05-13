@extends('admin.layout')

@section('title', 'Thêm danh mục')

@section('content')
<h2 class="fw-bold mb-4" style="color:#7b5554;">➕ Thêm danh mục</h2>

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

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Tên danh mục</label>
                <input type="text" name="category_name" class="form-control"
                       style="border-radius:14px;" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Mô tả</label>
                <textarea name="description" class="form-control"
                          style="border-radius:14px;" rows="4"></textarea>
            </div>

            <div class="d-flex gap-2">
                <button class="btn px-4"
                        style="background:#7b5554; color:white; border-radius:14px;">
                    Lưu
                </button>

                <a href="{{ route('admin.categories.index') }}"
                   class="btn px-4"
                   style="background:#efeded; color:#504443; border-radius:14px;">
                    Quay lại
                </a>
            </div>
        </form>

    </div>
</div>
@endsection