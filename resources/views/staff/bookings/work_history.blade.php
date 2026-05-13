@extends('staff.layout')

@section('title','Lịch sử công việc')

@section('page-title','')

@section('content')

<style>
    :root{
        --primary:#7b5554;
        --primary-dark:#684847;
        --accent:#ebbab9;
        --text:#2f2323;
        --muted:#7d7272;
        --border:#eadede;
    }

    .page-title{
        font-size:26px;
        font-weight:900;
        color:var(--text);
        font-family:'Noto Serif', serif;
        margin-bottom:6px;
    }

    .page-subtitle{
        color:var(--muted);
        font-weight:600;
        margin-bottom:28px;
    }

    .stat-grid{
        display:grid;
        grid-template-columns:repeat(3, 1fr);
        gap:20px;
        margin-bottom:28px;
    }

    .stat-card{
        background:white;
        border:1px solid #f1e7e7;
        border-radius:22px;
        padding:22px;
        box-shadow:0 12px 35px rgba(123,85,84,0.07);
    }

    .stat-icon{
        width:42px;
        height:42px;
        border-radius:14px;
        background:rgba(235,186,185,0.32);
        color:var(--primary);
        display:flex;
        align-items:center;
        justify-content:center;
        margin-bottom:16px;
        font-size:18px;
    }

    .stat-label{
        color:#5f5656;
        font-weight:800;
        font-size:14px;
        margin-bottom:8px;
    }

    .stat-value{
        color:var(--text);
        font-size:18px;
        font-weight:900;
    }

    .tool-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:18px;
        flex-wrap:wrap;
        margin-bottom:20px;
    }

    .search-box{
        width:360px;
        max-width:100%;
        background:white;
        border:1px solid #eadede;
        border-radius:14px;
        padding:12px 16px;
        display:flex;
        align-items:center;
        gap:10px;
        box-shadow:0 10px 25px rgba(123,85,84,0.05);
    }

    .search-box input{
        border:none;
        outline:none;
        width:100%;
        font-weight:700;
        color:#5f5656;
    }

    .filter-group{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
    }

    .filter-btn{
        border:none;
        background:white;
        color:#7d7272;
        padding:11px 18px;
        border-radius:999px;
        font-weight:900;
        font-size:13px;
        box-shadow:0 8px 20px rgba(123,85,84,0.05);
        border:1px solid #f1e7e7;
        text-decoration:none;
    }

    .filter-btn.active{
        background:var(--primary);
        color:white;
    }

    .history-card{
        background:white;
        border:1px solid #f1e7e7;
        border-radius:24px;
        box-shadow:0 14px 38px rgba(123,85,84,0.07);
        overflow:hidden;
    }

    .table{
        margin-bottom:0;
    }

    .table thead th{
        background:#fffafa;
        color:#5f5656;
        font-size:13px;
        font-weight:900;
        border-bottom:1px solid #f1e7e7;
        padding:16px;
        white-space:nowrap;
    }

    .table tbody td{
        padding:16px;
        vertical-align:middle;
        border-bottom:1px solid #f7eeee;
        color:#5f5656;
        font-weight:700;
        font-size:14px;
    }

    .date-cell{
        color:#2f2323;
        font-weight:900;
        line-height:1.5;
        white-space:nowrap;
    }

    .service-box{
        display:flex;
        align-items:center;
        gap:12px;
        min-width:210px;
    }

    .service-img{
        width:48px;
        height:48px;
        border-radius:12px;
        object-fit:cover;
        border:1px solid #f1e7e7;
    }

    .service-name{
        color:#2f2323;
        font-weight:900;
        margin-bottom:3px;
    }

    .service-sub{
        color:#8b8080;
        font-size:12px;
        font-weight:700;
    }

    .money{
        font-weight:900;
        color:#7b5554;
        white-space:nowrap;
    }

    .status{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:7px 12px;
        border-radius:999px;
        font-size:11px;
        font-weight:900;
        white-space:nowrap;
    }

    .status-unfinished{
        background:rgba(255,193,7,0.14);
        color:#9a6500;
    }

    .status-doing{
        background:rgba(13,110,253,0.10);
        color:#0d6efd;
    }

    .status-completed{
        background:rgba(25,135,84,0.10);
        color:#198754;
    }

    .status-cancelled{
        background:rgba(220,53,69,0.10);
        color:#dc3545;
    }

    .view-btn{
        color:#9b8f8f;
        text-decoration:none;
        font-size:18px;
    }

    .view-btn:hover{
        color:var(--primary);
    }

    .empty-box{
        padding:36px;
        text-align:center;
        color:var(--muted);
        font-weight:800;
    }

    @media(max-width:992px){
        .stat-grid{
            grid-template-columns:1fr;
        }
    }
</style>

@php
    $totalJobs = $bookings->count();

    $totalMinutes = $bookings->sum(function($booking){
        $detail = $booking->bookingDetails->first();
        return ($detail && $detail->service) ? $detail->service->duration : 60;
    });

    $totalHourText = floor($totalMinutes / 60) . 'h';

    if($totalMinutes % 60 > 0){
        $totalHourText .= ' ' . ($totalMinutes % 60) . 'p';
    }

    $topService = $bookings
        ->flatMap(fn($booking) => $booking->bookingDetails)
        ->groupBy('service_id')
        ->sortByDesc(fn($items) => $items->count())
        ->first();

    $topServiceName = $topService && $topService->first()->service
        ? $topService->first()->service->service_name
        : 'Chưa có dữ liệu';
@endphp

<div class="page-title">Lịch sử công việc</div>
<div class="page-subtitle">
    Theo dõi chi tiết tất cả lịch được phân công, bao gồm lịch chưa hoàn thành và lịch đã hoàn thành.
</div>

<div class="stat-grid">

    <div class="stat-card">
        <div class="stat-icon">
            <i class="bi bi-star"></i>
        </div>
        <div class="stat-label">Đánh giá trung bình</div>
        <div class="stat-value">4.9/5</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="bi bi-bag-heart"></i>
        </div>
        <div class="stat-label">Dịch vụ phổ biến nhất</div>
        <div class="stat-value">{{ $topServiceName }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="bi bi-clock"></i>
        </div>
        <div class="stat-label">Tổng giờ làm</div>
        <div class="stat-value">{{ $totalHourText }}</div>
    </div>

</div>

<div class="tool-row">

    <div class="search-box">
        <i class="bi bi-search" style="color:#b99191;"></i>
        <input type="text" id="searchInput" placeholder="Tìm tên khách hàng hoặc dịch vụ...">
    </div>

    <div class="filter-group">

        <a href="{{ route('staff.workHistory', ['filter' => 'all']) }}"
           class="filter-btn {{ request('filter','all') == 'all' ? 'active' : '' }}">
            Tất cả
        </a>

        <a href="{{ route('staff.workHistory', ['filter' => 'week']) }}"
           class="filter-btn {{ request('filter') == 'week' ? 'active' : '' }}">
            Tuần này
        </a>

        <a href="{{ route('staff.workHistory', ['filter' => 'month']) }}"
           class="filter-btn {{ request('filter') == 'month' ? 'active' : '' }}">
            Tháng này
        </a>

    </div>

</div>

<div class="history-card">

    @if($bookings->count() == 0)

        <div class="empty-box">
            Bạn chưa có công việc nào được phân công.
        </div>

    @else

        <div class="table-responsive">
            <table class="table align-middle" id="historyTable">
                <thead>
                    <tr>
                        <th>Ngày/Giờ</th>
                        <th>Dịch vụ</th>
                        <th>Khách hàng</th>
                        <th>Thời gian</th>
                        <th>Phí dịch vụ</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($bookings as $booking)

                        @php
                            $detail = $booking->bookingDetails->first();
                            $service = $detail->service ?? null;

                            $serviceImage = $service && $service->image
                                ? asset('uploads/services/' . $service->image)
                                : asset('uploads/services/default.jpg');

                            $statusText = 'Chưa hoàn thành';
                            $statusClass = 'status-unfinished';

                            if($booking->status == 2){
                                $statusText = 'Đang thực hiện';
                                $statusClass = 'status-doing';
                            }
                            elseif($booking->status == 3){
                                $statusText = 'Hoàn thành';
                                $statusClass = 'status-completed';
                            }
                            elseif($booking->status == 4){
                                $statusText = 'Đã hủy';
                                $statusClass = 'status-cancelled';
                            }
                        @endphp

                        <tr>
                            <td class="date-cell">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                                <br>
                                <span style="color:#8b8080;font-size:12px;">
                                    {{ $booking->booking_time }}
                                </span>
                            </td>

                            <td>
                                <div class="service-box">
                                    <img src="{{ $serviceImage }}" class="service-img" alt="service">

                                    <div>
                                        <div class="service-name">
                                            {{ $service->service_name ?? 'Không rõ dịch vụ' }}
                                        </div>
                                        <div class="service-sub">
                                            {{ $service->duration ?? 60 }} phút
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                {{ $booking->customer->full_name ?? 'Khách hàng' }}
                            </td>

                            <td>
                                {{ $service->duration ?? 60 }} phút
                            </td>

                            <td class="money">
                                {{ number_format($booking->total_amount) }}đ
                            </td>

                            <td>
                                <span class="status {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('staff.bookings.show', $booking->booking_id) }}"
                                   class="view-btn">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>

    @endif

</div>

<script>
    const searchInput = document.getElementById('searchInput');

    if(searchInput){
        searchInput.addEventListener('keyup', function(){
            const keyword = this.value.toLowerCase();
            const rows = document.querySelectorAll('#historyTable tbody tr');

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    }
</script>

@endsection