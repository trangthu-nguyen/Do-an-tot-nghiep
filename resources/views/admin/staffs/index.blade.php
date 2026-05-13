@extends('admin.layout')

@section('title', 'Nhân viên')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold m-0" style="color:#7b5554;">👩‍💼 Danh sách nhân viên</h2>

    <a href="{{ route('admin.staffs.create') }}"
       class="btn px-4"
       style="background:#7b5554; color:white; border-radius:14px;">
        + Thêm nhân viên
    </a>
</div>

<div class="card shadow-sm border-0" style="border-radius:18px;">
    <div class="card-body p-3">

        <table class="table table-hover align-middle mb-0">
            <thead style="background:#7b5554; color:white;">
                <tr>
                    <th style="border-top-left-radius:14px;">ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Kỹ năng</th>
                    <th>Trạng thái</th>
                    <th width="200" style="border-top-right-radius:14px;">Hành động</th>
                </tr>
            </thead>

            <tbody>
                @forelse($staffs as $staff)
                    <tr>
                        <td class="fw-semibold">{{ $staff->staff_id }}</td>

                        <td class="fw-semibold" style="color:#603d3d;">
                            {{ $staff->full_name }}
                        </td>

                        <td class="text-muted">{{ $staff->email }}</td>
                        <td class="text-muted">{{ $staff->phone }}</td>

                        <td>
                            <span class="badge"
                                  style="background:#efeded; color:#504443; border-radius:999px; padding:8px 12px;">
                                {{ $staff->skill }}
                            </span>
                        </td>

                        <td>
                            @if($staff->status == 1)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Ngưng</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.staffs.edit', $staff->staff_id) }}"
                               class="btn btn-sm px-3"
                               style="background:#ebbab9; color:#603d3d; border-radius:12px;">
                                Sửa
                            </a>

                            <form action="{{ route('admin.staffs.destroy', $staff->staff_id) }}"
                                  method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')

                                <button onclick="return confirm('Bạn chắc chắn muốn xóa nhân viên này?')"
                                        class="btn btn-sm px-3"
                                        style="background:#ba1a1a; color:white; border-radius:12px;">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Chưa có nhân viên nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@endsection