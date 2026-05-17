@extends('admin.layout')

@section('title', 'Quản lý Dịch vụ')

@section('content')

<style>
    :root{--primary:#7b5554;--dark:#2f2323;--muted:#8a7e7e;--border:#eadede;--soft:#faf7f7}
    .service-page{display:grid;grid-template-columns:280px 1fr;gap:24px}
    .left-card,.service-card,.promo-card{background:white;border:1px solid var(--border);border-radius:24px;box-shadow:0 12px 32px rgba(123,85,84,.06)}
    .page-title{font-family:'Noto Serif',serif;font-size:30px;font-weight:900;color:var(--primary);margin-bottom:24px}
    .left-card{padding:22px}
    .left-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
    .left-title{font-family:'Noto Serif',serif;font-weight:900;color:var(--primary);font-size:20px}
    .add-mini{border:0;background:#f7eeee;color:var(--primary);border-radius:999px;padding:7px 11px;font-size:12px;font-weight:900;text-decoration:none}
    .cat-link{display:flex;align-items:center;gap:12px;padding:14px;border-radius:16px;text-decoration:none;color:#655858;font-weight:800;margin-bottom:8px;transition:.2s}
    .cat-link:hover,.cat-link.active{background:#fff3f3;color:var(--primary)}
    .cat-icon{width:34px;height:34px;border-radius:12px;background:#f7eeee;color:var(--primary);display:flex;align-items:center;justify-content:center}
    .cat-count{margin-left:auto;background:#f4eeee;color:#7b5554;border-radius:999px;padding:4px 8px;font-size:11px;font-weight:900}
    .promo-card{margin-top:18px;height:180px;overflow:hidden;position:relative;background:linear-gradient(135deg,#7b5554,#c99796);color:white;padding:22px;display:flex;align-items:end}
    .promo-card:before{content:'';position:absolute;inset:0;background:rgba(0,0,0,.15)}
    .promo-text{position:relative;font-weight:800;line-height:1.7}
    .service-card{padding:0;overflow:hidden}
    .service-head{display:flex;justify-content:space-between;align-items:flex-start;gap:18px;padding:24px;border-bottom:1px solid #f3eeee}
    .service-title{font-family:'Noto Serif',serif;font-size:26px;font-weight:900;color:var(--primary)}
    .service-sub{color:#8a7e7e;font-weight:600;font-size:14px;margin-top:4px}
    .btn-main{border:0;background:var(--primary);color:white;border-radius:14px;padding:12px 18px;font-weight:900;text-decoration:none}
    .btn-main:hover{background:#684847;color:white}
    .table th{font-size:11px;text-transform:uppercase;color:#9b8f8f;padding:16px;border-bottom:1px solid #f2eeee}
    .table td{padding:18px 16px;vertical-align:middle;border-bottom:1px solid #f7eeee}
    .svc-name{font-weight:900;color:var(--dark)}
    .svc-desc{font-size:12px;color:#8a7e7e;margin-top:3px;max-width:240px}
    .price{font-weight:900;color:#7b5554}
    .duration{font-weight:800;color:#5f5656}
    .badge-status{border-radius:999px;padding:7px 11px;font-size:11px;font-weight:900}
    .active{background:#dcfce7;color:#15803d}
    .inactive{background:#eeeeee;color:#777}
    .action-wrap{display:flex;justify-content:flex-end;gap:8px}
    .action-btn{width:34px;height:34px;border-radius:12px;border:1px solid var(--border);display:inline-flex;align-items:center;justify-content:center;background:white;color:#7b5554;text-decoration:none}
    .delete-btn{color:#ba1a1a}
    .footer-note{padding:16px 24px;color:#8a7e7e;font-size:13px;font-weight:700}
    @media(max-width:1100px){.service-page{grid-template-columns:1fr}}
</style>

@php
    $selectedCategory = $categories->firstWhere('category_id', request('category_id'));
@endphp

<div class="page-title">Quản lý Dịch vụ</div>

@if(session('success'))
    <div class="alert alert-success rounded-4">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger rounded-4">{{ session('error') }}</div>
@endif

<div class="service-page">

    <aside>
        <div class="left-card">
            <div class="left-head">
                <div class="left-title">Danh mục</div>
                <a href="{{ route('admin.categories.create') }}" class="add-mini">
                    <i class="bi bi-plus-circle"></i> Thêm mới
                </a>
            </div>

            <a href="{{ route('admin.services.index') }}"
               class="cat-link {{ request('category_id') ? '' : 'active' }}">
                <span class="cat-icon"><i class="bi bi-grid"></i></span>
                Tất cả dịch vụ
                <span class="cat-count">{{ $totalServices }}</span>
            </a>

            @foreach($categories as $cat)
                <a href="{{ route('admin.services.index', ['category_id' => $cat->category_id]) }}"
                   class="cat-link {{ request('category_id') == $cat->category_id ? 'active' : '' }}">
                    <span class="cat-icon"><i class="bi bi-stars"></i></span>
                    <span>{{ $cat->category_name }}</span>
                    <span class="cat-count">{{ $cat->services_count ?? $cat->services()->count() }}</span>
                </a>
            @endforeach
        </div>

        <div class="promo-card">
            <div class="promo-text">
                Mẹo quản trị: Cập nhật hình ảnh dịch vụ thường xuyên để thu hút khách hàng.
            </div>
        </div>
    </aside>

    <section class="service-card">
        <div class="service-head">
            <div>
                <div class="service-title">
                    Dịch vụ: {{ $selectedCategory->category_name ?? 'Tất cả danh mục' }}
                </div>
                <div class="service-sub">
                    Hiển thị {{ $services->count() }} dịch vụ trong danh mục này
                </div>
            </div>

            <a href="{{ route('admin.services.create') }}" class="btn-main">
                <i class="bi bi-plus-lg"></i> Thêm dịch vụ mới
            </a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Dịch vụ</th>
                        <th>Giá tiền</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($services as $sv)
                        <tr>
                            <td>
                                <div class="svc-name">{{ $sv->service_name }}</div>
                                <div class="svc-desc">
                                    {{ \Illuminate\Support\Str::limit($sv->description, 55) }}
                                </div>
                            </td>

                            <td class="price">{{ number_format($sv->price) }}đ</td>

                            <td class="duration">{{ $sv->duration }} phút</td>

                            <td>
                                @if($sv->status == 1)
                                    <span class="badge-status active">Đang kinh doanh</span>
                                @else
                                    <span class="badge-status inactive">Ngừng kinh doanh</span>
                                @endif
                            </td>

                            <td>
                                <div class="action-wrap">
                                    <a href="{{ route('admin.services.edit', $sv->service_id) }}"
                                       class="action-btn"
                                       title="Sửa dịch vụ">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('admin.services.destroy', $sv->service_id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Bạn chắc chắn muốn xóa dịch vụ này?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="action-btn delete-btn" title="Xóa dịch vụ">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                Chưa có dịch vụ nào trong danh mục này.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="footer-note">
            Tổng dịch vụ: {{ $totalServices }} | Đang kinh doanh: {{ $activeServices }}
        </div>
    </section>

</div>

@endsection