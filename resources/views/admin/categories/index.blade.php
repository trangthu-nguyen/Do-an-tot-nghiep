@extends('admin.layout')

@section('title', 'Quản lý danh mục')

@section('content')

<style>
    .cat-page{color:#2f2323}
    .cat-top,.cat-card,.cat-info{background:white;border:1px solid #f0e4e4;border-radius:22px;box-shadow:0 12px 32px rgba(123,85,84,.06)}
    .cat-top{padding:24px;margin-bottom:22px;display:flex;justify-content:space-between;gap:18px;align-items:center}
    .cat-label{font-size:12px;font-weight:900;color:#9b8f8f;letter-spacing:.5px}
    .cat-heading{font-size:30px;font-weight:900;margin:6px 0;color:#2f2323}
    .cat-sub{color:#7d7272;font-size:14px;line-height:1.7;margin:0}
    .btn-cat{background:#7b5554;color:white;border-radius:14px;padding:11px 16px;text-decoration:none;font-weight:800;border:0}
    .btn-cat:hover{background:#684847;color:white}
    .stats{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:22px}
    .stat{padding:20px}
    .stat:nth-child(3){background:#ffe1e0;position:relative;overflow:hidden}
    .stat-num{font-size:30px;font-weight:900;color:#7b5554}
    .stat-note{font-size:12px;color:#9b8f8f;font-weight:700}
    .table-wrap{overflow:hidden}
    .table-title{padding:20px 24px;border-bottom:1px solid #f1e7e7;font-size:20px;font-weight:900}
    .table th{font-size:12px;text-transform:uppercase;color:#8b8080;padding:15px 22px}
    .table td{padding:16px 22px;vertical-align:middle;border-bottom:1px solid #f7eeee}
    .cat-icon{width:38px;height:38px;border-radius:13px;background:#ffe1e0;color:#7b5554;display:flex;align-items:center;justify-content:center}
    .cat-name{font-weight:900}
    .cat-desc{font-size:12px;color:#8d8181}
    .badge-status{padding:6px 12px;border-radius:999px;font-size:12px;font-weight:900}
    .active{background:#dcfce7;color:#15803d}
    .inactive{background:#eee;color:#777}
    .action-btn{border:0;background:transparent;color:#7b5554;font-size:16px}
    .bottom{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:22px}
    .cat-info{padding:20px;background:#efeded}
    .cat-info h5{font-weight:900;color:#7b5554}
    @media(max-width:991px){.cat-top{flex-direction:column;align-items:flex-start}.stats,.bottom{grid-template-columns:1fr}}
</style>

<div class="cat-page">

    <div class="cat-top">
        <div>
            
            <h1 class="cat-heading">Nhóm dịch vụ làm đẹp</h1>
            <p class="cat-sub">
                Tổ chức danh mục để khách hàng dễ tìm kiếm và đặt lịch dịch vụ phù hợp.
            </p>
        </div>

        <a href="{{ route('admin.categories.create') }}" class="btn-cat">
            <i class="bi bi-plus-circle"></i> Thêm danh mục
        </a>
    </div>

    <div class="stats">
        <div class="cat-card stat">
            <div class="cat-label">Tổng danh mục</div>
            <div class="stat-num">{{ $totalCategories }}</div>
            <div class="stat-note">Danh mục hiện có</div>
        </div>

        <div class="cat-card stat">
            <div class="cat-label">Tổng dịch vụ</div>
            <div class="stat-num">{{ $totalServices }}</div>
            <div class="stat-note">Dịch vụ trong hệ thống</div>
        </div>

        <div class="cat-card stat">
            <div class="cat-label">Nổi bật nhất</div>
            <div class="stat-num" style="font-size:22px">
                {{ $mostPopularCategory->category_name ?? 'Chưa có' }}
            </div>
            <div class="stat-note">
                {{ $mostPopularCategory->services_count ?? 0 }} dịch vụ
            </div>
        </div>
    </div>

    <div class="cat-card table-wrap">
        <div class="table-title">
            Danh sách nhóm dịch vụ
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tên danh mục</th>
                        <th>Số dịch vụ</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="cat-icon">
                                        <i class="bi bi-stars"></i>
                                    </span>

                                    <div>
                                        <div class="cat-name">{{ $cat->category_name }}</div>
                                        <div class="cat-desc">{{ $cat->description ?? 'Chưa có mô tả' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <strong>{{ $cat->services_count }}</strong> dịch vụ
                            </td>

                            <td>
                                @if($cat->services_count > 0)
                                    <span class="badge-status active">Active</span>
                                @else
                                    <span class="badge-status inactive">Inactive</span>
                                @endif
                            </td>

                            <td class="text-end">
                                <a href="{{ route('admin.categories.edit', $cat->category_id) }}"
                                   class="action-btn"
                                   title="Sửa">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>

                                <form action="{{ route('admin.categories.destroy', $cat->category_id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="action-btn"
                                            title="Xóa"
                                            onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                Chưa có danh mục nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 text-muted" style="font-size:13px;">
            Hiển thị {{ $categories->count() }} danh mục
        </div>
    </div>

    <div class="bottom">
        <div class="cat-info">
            <h5>Sức khỏe danh mục</h5>
            <p class="mb-0">Danh mục chưa có dịch vụ sẽ hiển thị trạng thái Inactive.</p>
        </div>

        <div class="cat-info">
            <h5>Gợi ý quản lý</h5>
            <p class="mb-0">Nên nhóm dịch vụ rõ ràng để khách hàng dễ chọn và đặt lịch hơn.</p>
        </div>
    </div>

</div>

@endsection