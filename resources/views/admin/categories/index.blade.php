@extends('admin.layout')

@section('title', 'Danh mục')

@section('content')
<h2 class="fw-bold mb-4" style="color:#7b5554;">📂 Danh sách danh mục</h2>

<a href="{{ route('admin.categories.create') }}"
   class="btn mb-3 px-4"
   style="background:#7b5554; color:white; border-radius:14px;">
   + Thêm danh mục
</a>

<div class="card shadow-sm border-0" style="border-radius:18px;">
    <div class="card-body p-3">

        <table class="table table-hover align-middle mb-0">
            <thead style="background:#7b5554; color:white;">
                <tr>
                    <th style="border-top-left-radius:14px;">ID</th>
                    <th>Tên danh mục</th>
                    <th>Mô tả</th>
                    <th width="200" style="border-top-right-radius:14px;">Hành động</th>
                </tr>
            </thead>

            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td class="fw-semibold">{{ $cat->category_id }}</td>
                    <td>{{ $cat->category_name }}</td>
                    <td class="text-muted">{{ $cat->description }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $cat->category_id) }}"
                           class="btn btn-sm px-3"
                           style="background:#ebbab9; color:#603d3d; border-radius:12px;">
                           Sửa
                        </a>

                        <form action="{{ route('admin.categories.destroy', $cat->category_id) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm px-3"
                                    style="background:#ba1a1a; color:white; border-radius:12px;"
                                    onclick="return confirm('Bạn có chắc muốn xóa?')">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        Chưa có danh mục nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>
@endsection