@extends('admin.layout')

@section('title', 'Dịch vụ')

@section('content')
<h2 class="fw-bold mb-4" style="color:#7b5554;">💆‍♀️ Danh sách dịch vụ</h2>

<a href="{{ route('admin.services.create') }}"
   class="btn mb-3 px-4"
   style="background:#7b5554; color:white; border-radius:14px;">
    + Thêm dịch vụ
</a>

<div class="card shadow-sm border-0" style="border-radius:18px;">
    <div class="card-body p-3">

        <table class="table table-hover align-middle mb-0">
            <thead style="background:#7b5554; color:white;">
                <tr>
                    <th style="border-top-left-radius:14px;">ID</th>
                    <th>Tên dịch vụ</th>
                    <th>Giá</th>
                    <th>Thời gian</th>
                    <th>Ảnh</th>
                    <th>Trạng thái</th>
                    <th width="200" style="border-top-right-radius:14px;">Hành động</th>
                </tr>
            </thead>

            <tbody>
                @forelse($services as $sv)
                <tr>
                    <td class="fw-semibold">{{ $sv->service_id }}</td>

                    <td style="color:#603d3d;" class="fw-semibold">
                        {{ $sv->service_name }}
                    </td>

                    <td class="fw-semibold text-danger">
                        {{ number_format($sv->price) }} VNĐ
                    </td>

                    <td class="text-muted">
                        {{ $sv->duration }} phút
                    </td>

                    <td>
                        @if($sv->image)
                            <img src="{{ asset('uploads/services/' . $sv->image) }}"
                                 width="90"
                                 style="border-radius:14px; border:1px solid #d4c2c2;">
                        @else
                            <span class="text-muted">Không có ảnh</span>
                        @endif
                    </td>

                    <td>
                        @if($sv->status == 1)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('admin.services.edit', $sv->service_id) }}"
                           class="btn btn-sm px-3"
                           style="background:#ebbab9; color:#603d3d; border-radius:12px;">
                            Sửa
                        </a>

                        <form action="{{ route('admin.services.destroy', $sv->service_id) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm px-3"
                                    style="background:#ba1a1a; color:white; border-radius:12px;"
                                    onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        Chưa có dịch vụ nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>
@endsection