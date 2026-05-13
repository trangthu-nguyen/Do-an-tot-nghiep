@extends('customer.layout')

@section('title', 'Dịch vụ - BeautyHome')

@section('content')

<style>
    :root { --primary: #7b5554; }

    .services-hero {
        background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)), 
                    url('{{ asset("uploads/services/hero.jpg") }}') center/cover no-repeat; 
        padding: 140px 0 90px;
        color: white;
        text-align: center;
    }

    .search-bar {
        max-width: 720px;
        margin: 0 auto;
        background: white;
        border-radius: 9999px;
        padding: 8px 12px 8px 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        display: flex;
        align-items: center;
    }

    .search-bar input {
        border: none;
        outline: none;
        flex: 1;
        font-size: 17px;
        padding: 12px 20px;
    }

    .search-bar button {
        background: var(--primary);
        color: white;
        border: none;
        padding: 14px 32px;
        border-radius: 9999px;
        font-weight: 700;
    }

    .service-card {
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid #f2e8e8;
        transition: all 0.4s;
    }
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(123,85,84,0.15);
    }

    .service-img {
        height: 240px;
        object-fit: cover;
    }
</style>

<!-- Hero Banner -->
<div class="services-hero">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4">Tìm dịch vụ làm đẹp hoàn hảo tại nhà</h1>
        
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Bạn đang tìm dịch vụ nào?" autocomplete="off">
            <button onclick="applyFilters()">Tìm kiếm</button>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">

        <!-- Bộ lọc -->
        <div class="col-lg-3">
            <div class="filter-sidebar" style="background:white; border-radius:20px; padding:28px; border:1px solid #f2e8e8; position: sticky; top: 100px;">
                <h5 class="fw-bold mb-4">Danh mục</h5>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="all" checked onchange="applyFilters()">
                    <label class="form-check-label" for="all">Tất cả dịch vụ</label>
                </div>

                @foreach($categories as $cat)
                <div class="form-check mb-2">
                    <input class="form-check-input category-filter" type="checkbox" 
                           value="{{ $cat->category_id }}" onchange="applyFilters()">
                    <label class="form-check-label">{{ $cat->category_name }}</label>
                </div>
                @endforeach

                <hr class="my-4">
                <h5 class="fw-bold mb-3">Khoảng giá</h5>
                <input type="range" class="form-range" min="0" max="3000000" value="3000000" id="priceRange" oninput="applyFilters()">
                <div class="d-flex justify-content-between small">
                    <span>0đ</span>
                    <span id="priceValue">3.000.000đ+</span>
                </div>

                <button onclick="resetFilters()" class="btn btn-outline-secondary w-100 mt-4">Xóa bộ lọc</button>
            </div>
        </div>

        <!-- Danh sách dịch vụ -->
        <div class="col-lg-9">
            <div class="row g-4" id="servicesContainer">
                @foreach($services as $service)
                <div class="col-md-6 col-lg-4 service-item" 
                     data-price="{{ $service->price }}" 
                     data-category="{{ $service->category_id }}">

                    <div class="service-card">

                        <!-- IMAGE -->
                        <img src="{{ $service->image ? asset('uploads/services/' . $service->image) : asset('uploads/services/default.jpg') }}" 
                             class="service-img w-100" alt="{{ $service->service_name }}">

                        <div class="p-4">
                            <h5 class="fw-bold mb-2">{{ $service->service_name }}</h5>

                            <!-- CHỈ SỬA CHỖ NÀY: THÊM ICON ĐỒNG HỒ -->
                            <p class="text-muted small mb-3">
                                {{ $service->category->category_name ?? '' }}
                                <i class="bi bi-clock"></i> {{ $service->duration }} phút
                            </p>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="fw-bold fs-5 text-primary">{{ number_format($service->price) }}đ</div>

                                <a href="{{ route('customer.services.show', $service->service_id) }}" 
                                   class="btn btn-sm btn-dark">
                                    Đặt lịch
                                </a>
                            </div>
                        </div>

                    </div>

                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<script>
    function applyFilters() {
        const searchText = document.getElementById('searchInput').value.toLowerCase().trim();
        const maxPrice = parseInt(document.getElementById('priceRange').value);
        const selectedCategories = Array.from(document.querySelectorAll('.category-filter:checked')).map(cb => cb.value);

        document.querySelectorAll('.service-item').forEach(item => {
            const price = parseInt(item.dataset.price);
            const category = item.dataset.category;

            let matchCategory = selectedCategories.length === 0 || selectedCategories.includes(category);
            let matchPrice = price <= maxPrice;
            let matchSearch = searchText === '' || item.querySelector('h5').textContent.toLowerCase().includes(searchText);

            if (matchCategory && matchPrice && matchSearch) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('priceRange').value = 3000000;
        document.getElementById('priceValue').textContent = '3.000.000đ+';
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        document.getElementById('all').checked = true;
        applyFilters();
    }

    document.getElementById('priceRange').addEventListener('input', function() {
        document.getElementById('priceValue').textContent = Number(this.value).toLocaleString('vi-VN') + 'đ+';
    });

    document.getElementById('searchInput').addEventListener('keyup', applyFilters);
</script>

@endsection