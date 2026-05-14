@extends('admin.layout')

@section('title', 'Quản lý dịch vụ')

@section('content')

<style>
    .svc-top,.svc-card,.insight{background:white;border:1px solid #f0e4e4;border-radius:22px;box-shadow:0 12px 32px rgba(123,85,84,.06)}
    .svc-top{padding:24px;margin-bottom:20px;display:flex;justify-content:space-between;gap:18px;align-items:center}
    .svc-label{font-size:12px;font-weight:900;color:#9b8f8f}
    .svc-heading{font-size:30px;font-weight:900;color:#2f2323;margin:6px 0}
    .svc-sub{color:#7d7272;font-size:14px;margin:0}
    .btn-svc{background:#7b5554;color:white;border-radius:14px;padding:11px 16px;text-decoration:none;font-weight:800;border:0}
    .btn-svc:hover{background:#684847;color:white}
    .filter-box{padding:16px 20px;margin-bottom:20px}
    .filter-box select{border-radius:14px;border:1px solid #eadede;padding:9px 14px}
    .svc-layout{display:grid;grid-template-columns:1fr 280px;gap:20px}
    .table-title{padding:20px 24px;border-bottom:1px solid #f1e7e7;font-size:20px;font-weight:900;color:#2f2323}
    .table th{font-size:12px;text-transform:uppercase;color:#8b8080;padding:15px 18px}
    .table td{padding:16px 18px;vertical-align:middle;border-bottom:1px solid #f7eeee}
    .svc-img{width:58px;height:46px;object-fit:cover;border-radius:12px;border:1px solid #eadede}
    .svc-name{font-weight:900;color:#2f2323}
    .svc-desc{font-size:12px;color:#8d8181}
    .cat-pill{background:#f4eeee;color:#7b5554;padding:6px 10px;border-radius:999px;font-size:12px;font-weight:800}
    .status{padding:6px 12px;border-radius:999px;font-size:12px;font-weight:900}
    .active{background:#dcfce7;color:#15803d}
    .inactive{background:#eee;color:#777}
    .action-btn{border:0;background:transparent;color:#7b5554;font-size:16px}
    .insight{padding:22px;height:max-content}
    .insight-title{font-size:20px;font-weight:900;color:#7b5554;margin-bottom:18px}
    .insight-box{background:#fff1f1;border-radius:18px;padding:16px;margin-bottom:14px}
    .insight-num{font-size:26px;font-weight:900;color:#7b5554}
    @media(max-width:991px){.svc-top{flex-direction:column;align-items:flex-start}.svc-layout{grid-template-columns:1fr}}
</style>

<div class="svc-top">
    <div>
      
        <h1 class="svc-heading">Danh mục dịch vụ</h1>
        <p class="svc-sub">Quản lý các dịch vụ làm đẹp, giá tiền, thời gian và trạng thái hiển thị.</p>
    </div>

    <a href="{{ route('admin.services.create') }}" class="btn-svc">
        <i class="bi bi-plus-circle"></i> Thêm dịch vụ
    </a>
</div>

<form method="GET" action="{{ route('admin.services.index') }}" class="svc-card filter-box">
    <div class="d-flex flex-wrap gap-3 align-items-center">
        <strong><i class="bi bi-funnel"></i> Lọc theo</strong>

        <select name="category_id" onchange="this.form.submit()">
            <option value="">Tất cả danh mục</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->category_id }}" {{ request('category_id') == $cat->category_id ? 'selected' : '' }}>
                    {{ $cat->category_name }}
                </option>
            @endforeach
        </select>

        <select name="status" onchange="this.form.submit()">
            <option value="">Tất cả trạng thái</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
        </select>

        <span class="text-muted ms-auto">
            {{ $services->count() }} dịch vụ được tìm thấy
        </span>
    </div>
</form>

<div class="svc-layout">

    <div class="svc-card overflow-hidden">
        <div class="table-title">Chi tiết dịch vụ</div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Dịch vụ</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($services as $sv)
                        @php
                            $img = $sv->image
                                ? asset('uploads/services/'.$sv->image)
                                : asset('uploads/services/default.jpg');
                        @endphp

                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $img }}" class="svc-img" alt="{{ $sv->service_name }}">
                                    <div>
                                        <div class="svc-name">{{ $sv->service_name }}</div>
                                        <div class="svc-desc">{{ \Illuminate\Support\Str::limit($sv->description, 45) }}</div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="cat-pill">
                                    {{ $sv->category->category_name ?? 'Chưa có' }}
                                </span>
                            </td>

                            <td class="fw-bold text-danger">
                                {{ number_format($sv->price) }}đ
                            </td>

                            <td>
                                {{ $sv->duration }} phút
                            </td>

                            <td>
                                @if($sv->status == 1)
                                    <span class="status active">Active</span>
                                @else
                                    <span class="status inactive">Hidden</span>
                                @endif
                            </td>

                            <td class="text-end">
                                <a href="{{ route('admin.services.edit', $sv->service_id) }}"
                                   class="action-btn"
                                   title="Sửa">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>

                                <form action="{{ route('admin.services.destroy', $sv->service_id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="action-btn"
                                            title="Xóa"
                                            onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Chưa có dịch vụ nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 text-muted" style="font-size:13px;">
            Hiển thị {{ $services->count() }} / {{ $totalServices }} dịch vụ
        </div>
    </div>

    <div class="insight">
        <div class="insight-title">Service Insight</div>

        <div class="insight-box">
            <div class="svc-label">Tổng dịch vụ</div>
            <div class="insight-num">{{ $totalServices }}</div>
        </div>

        <div class="insight-box">
            <div class="svc-label">Đang hoạt động</div>
            <div class="insight-num">{{ $activeServices }}</div>
        </div>

        <div class="insight-box">
            <div class="svc-label">Dịch vụ nổi bật</div>
            <div class="fw-bold" style="color:#7b5554;">
                {{ $mostBookedService->service_name ?? 'Chưa có' }}
            </div>
            <div class="text-muted" style="font-size:13px;">
                {{ $mostBookedService->booking_details_count ?? 0 }} lượt đặt
            </div>
        </div>

    </div>

</div>

@endsection