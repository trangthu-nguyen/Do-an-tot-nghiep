@extends('staff.layout')

@section('title','Lịch làm việc')

@section('page-title','')

@section('content')

<style>
    :root{
        --primary:#7b5554;
        --primary-dark:#684847;
        --accent:#ebbab9;
        --soft:#fff7f7;
        --text:#2f2323;
        --muted:#7d7272;
        --border:#eadede;
    }

    .schedule-layout{
        display:grid;
        grid-template-columns: 1fr 310px;
        gap:28px;
        align-items:start;
    }

    @media(max-width: 992px){
        .schedule-layout{
            grid-template-columns:1fr;
        }
    }

    .schedule-top{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:20px;
        margin-bottom:28px;
    }

    .timeline-title{
        font-size:38px;
        font-weight:900;
        font-family:'Noto Serif', serif;
        color:var(--text);
        margin-bottom:6px;
    }

    .timeline-subtitle{
        color:var(--muted);
        font-weight:700;
        font-size:14px;
    }

    .filter-wrap{
        display:inline-flex;
        align-items:center;
        background:white;
        border:1px solid var(--border);
        border-radius:14px;
        overflow:hidden;
        box-shadow:0 10px 28px rgba(123,85,84,0.06);
    }

    .filter-btn{
        min-width:78px;
        padding:10px 18px;
        background:white;
        color:var(--muted);
        font-weight:800;
        font-size:13px;
        text-align:center;
        text-decoration:none;
        border-right:1px solid #f1e7e7;
        transition:0.25s;
    }

    .filter-btn:last-child{
        border-right:none;
    }

    .filter-btn:hover,
    .filter-btn.active{
        background:rgba(235,186,185,0.30);
        color:var(--primary);
    }

    .timeline{
        position:relative;
        padding-left:34px;
    }

    .timeline::before{
        content:"";
        position:absolute;
        left:10px;
        top:8px;
        bottom:8px;
        width:2px;
        background:linear-gradient(to bottom, rgba(123,85,84,0.25), rgba(235,186,185,0.45));
        border-radius:999px;
    }

    .date-heading{
    margin:30px 0 18px 34px;
    color:var(--primary);
    font-weight:900;
    font-size:15px;
    display:inline-flex;
    align-items:center;
    padding:9px 18px;
    border-radius:999px;
    background:rgba(235,186,185,0.28);
    border:1px solid rgba(235,186,185,0.65);
}

    .timeline-item{
        position:relative;
        margin-bottom:22px;
    }

    .timeline-dot{
        position:absolute;
        left:-31px;
        top:20px;
        width:16px;
        height:16px;
        border-radius:50%;
        background:white;
        border:3px solid var(--primary);
        z-index:2;
    }

    .booking-card{
        background:white;
        border:1px solid #f1e7e7;
        border-radius:22px;
        padding:20px;
        box-shadow:0 14px 36px rgba(123,85,84,0.07);
        transition:0.25s;
        display:grid;
        grid-template-columns:90px 1fr;
        gap:20px;
    }

    .booking-card:hover{
        transform:translateY(-3px);
        box-shadow:0 18px 45px rgba(123,85,84,0.11);
    }

    .time-box{
        border-right:1px dashed rgba(123,85,84,0.16);
        padding-right:16px;
    }

    .booking-time{
        font-size:20px;
        font-weight:900;
        color:var(--primary);
        margin-bottom:5px;
    }

    .booking-duration{
        color:var(--muted);
        font-size:13px;
        font-weight:700;
        margin-bottom:10px;
    }

    .status-badge{
        padding:6px 10px;
        border-radius:999px;
        font-weight:900;
        font-size:11px;
        display:inline-block;
    }

    .status-pending{ background:rgba(255,193,7,0.14); color:#9a6500; }
    .status-confirmed{ background:rgba(235,186,185,0.35); color:var(--primary); }
    .status-doing{ background:rgba(13,110,253,0.10); color:#0d6efd; }
    .status-done{ background:rgba(25,135,84,0.12); color:#198754; }
    .status-cancel{ background:rgba(220,53,69,0.10); color:#dc3545; }

    .booking-main{
        min-width:0;
    }

    .booking-customer{
        font-family:'Noto Serif', serif;
        font-size:20px;
        font-weight:900;
        color:var(--text);
        margin-bottom:4px;
    }

    .booking-service{
        color:#5f5656;
        font-size:14px;
        font-weight:700;
        margin-bottom:8px;
    }

    .booking-address{
        color:var(--muted);
        font-size:13px;
        font-weight:700;
        display:flex;
        align-items:center;
        gap:7px;
        margin-bottom:14px;
    }

    .mini-map{
        height:82px;
        border-radius:16px;
        overflow:hidden;
        background:
            linear-gradient(135deg, rgba(235,186,185,0.16), rgba(255,255,255,0.2)),
            url('https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=900&auto=format&fit=crop');
        background-size:cover;
        background-position:center;
        border:1px solid #f2e8e8;
        margin-bottom:14px;
    }

    .card-footer-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:12px;
        flex-wrap:wrap;
    }

    .price-text{
        color:#ba1a1a;
        font-weight:900;
        font-size:15px;
    }

    .btn-detail{
        background:var(--primary);
        color:white;
        border-radius:999px;
        font-weight:900;
        padding:8px 16px;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:7px;
        transition:0.25s;
        font-size:13px;
    }

    .btn-detail:hover{
        background:var(--primary-dark);
        color:white;
        transform:translateY(-2px);
    }

    .side-panel{
        background:transparent;
        display:flex;
        flex-direction:column;
        gap:18px;
    }

    .summary-card{
        border-radius:26px;
        padding:22px;
        background:linear-gradient(135deg, rgba(235,186,185,0.88), rgba(235,186,185,0.55));
        color:var(--primary);
        box-shadow:0 16px 38px rgba(123,85,84,0.10);
        border:1px solid rgba(235,186,185,0.85);
    }

    .side-title{
        font-family:'Noto Serif', serif;
        font-size:21px;
        font-weight:900;
        margin-bottom:16px;
        color:var(--text);
    }

    .summary-grid{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:12px;
        margin-bottom:16px;
    }

    .summary-box{
        background:rgba(255,255,255,0.45);
        border-radius:18px;
        padding:14px;
    }

    .summary-number{
        font-size:28px;
        font-weight:900;
        color:var(--primary);
    }

    .summary-label{
        font-size:12px;
        font-weight:800;
        color:#775858;
    }

    .progress-text{
        display:flex;
        justify-content:space-between;
        font-size:12px;
        font-weight:800;
        margin-bottom:8px;
        color:#775858;
    }

    .progress-line{
        height:7px;
        background:rgba(255,255,255,0.55);
        border-radius:999px;
        overflow:hidden;
    }

    .progress-line span{
        display:block;
        height:100%;
        width:75%;
        background:var(--primary);
        border-radius:999px;
    }

    .white-side-card{
        background:white;
        border:1px solid #f1e7e7;
        border-radius:24px;
        padding:20px;
        box-shadow:0 14px 36px rgba(123,85,84,0.07);
    }

    .availability-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:11px 0;
        border-bottom:1px solid #f5eeee;
        gap:12px;
    }

    .availability-row:last-child{
        border-bottom:none;
    }

    .availability-label{
        font-weight:800;
        color:#5f5656;
        font-size:14px;
    }

    .custom-switch{
        width:50px;
        height:27px;
        background:#eadede;
        border:none;
        cursor:pointer;
    }

    .custom-switch:checked{
        background-color:var(--primary);
        border-color:var(--primary);
    }

    .custom-switch:focus{
        box-shadow:0 0 0 4px rgba(235,186,185,0.45);
    }

    .break-pill{
        background:rgba(235,186,185,0.20);
        color:var(--primary);
        padding:7px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
        white-space:nowrap;
    }

    .reminder-item{
        display:flex;
        gap:12px;
        align-items:flex-start;
        padding:14px;
        background:white;
        border-radius:18px;
        border:1px solid #f1e7e7;
        box-shadow:0 10px 24px rgba(123,85,84,0.05);
    }

    .reminder-icon{
        width:34px;
        height:34px;
        border-radius:12px;
        display:flex;
        align-items:center;
        justify-content:center;
        background:rgba(235,186,185,0.35);
        color:var(--primary);
        flex-shrink:0;
    }

    .reminder-title{
        font-weight:900;
        color:var(--text);
        font-size:13px;
    }

    .reminder-sub{
        color:var(--muted);
        font-weight:700;
        font-size:12px;
    }

    @media(max-width:576px){
        .booking-card{
            grid-template-columns:1fr;
        }

        .time-box{
            border-right:none;
            border-bottom:1px dashed rgba(123,85,84,0.16);
            padding-right:0;
            padding-bottom:12px;
        }

        .schedule-top{
            flex-direction:column;
        }
    }
</style>

@php
    use Carbon\Carbon;

    Carbon::setLocale('vi');

    $today = Carbon::today();

    $bookingsByDate = $bookings->groupBy(function($booking) {
        return $booking->booking_date;
    });

    $todayBookings = $bookings->filter(function($booking) use ($today) {
        return Carbon::parse($booking->booking_date)->isSameDay($today);
    });

    $completedCount = $bookings->where('status', 3)->count();
    $totalCount = max($bookings->count(), 1);
    $progressPercent = round(($completedCount / $totalCount) * 100);
@endphp

<div class="schedule-layout">

    <div>
        <div class="schedule-top">
            <div>
                <div class="timeline-title">Lịch làm việc của tôi</div>
                
            </div>

            <div class="filter-wrap">
                <a href="{{ route('staff.bookings.index', ['filter' => 'day']) }}"
                   class="filter-btn {{ request('filter','day') == 'day' ? 'active' : '' }}">
                    Ngày
                </a>

                <a href="{{ route('staff.bookings.index', ['filter' => 'week']) }}"
                   class="filter-btn {{ request('filter') == 'week' ? 'active' : '' }}">
                    Tuần
                </a>

                <a href="{{ route('staff.bookings.index', ['filter' => 'month']) }}"
                   class="filter-btn {{ request('filter') == 'month' ? 'active' : '' }}">
                    Tháng
                </a>
            </div>
        </div>

        <div class="timeline">

            @forelse($bookingsByDate as $date => $items)

                @php
                    $dateObj = Carbon::parse($date);

                    if ($dateObj->isSameDay($today)) {
                        $dateTitle = 'Hôm nay - ' . $dateObj->translatedFormat('l, d/m/Y');
                    } elseif ($dateObj->isSameDay($today->copy()->addDay())) {
                        $dateTitle = 'Ngày mai - ' . $dateObj->translatedFormat('l, d/m/Y');
                    } else {
                        $dateTitle = $dateObj->translatedFormat('l, d/m/Y');
                    }
                @endphp

                <div class="date-heading">
                    {{ ucfirst($dateTitle) }}
                </div>

                @foreach($items as $bk)

                    @php
                        $statusText = 'Chờ xác nhận';
                        $statusClass = 'status-pending';

                        if($bk->status == 1){
                            $statusText = 'Đã xác nhận';
                            $statusClass = 'status-confirmed';
                        }elseif($bk->status == 2){
                            $statusText = 'Đang thực hiện';
                            $statusClass = 'status-doing';
                        }elseif($bk->status == 3){
                            $statusText = 'Hoàn thành';
                            $statusClass = 'status-done';
                        }elseif($bk->status == 4){
                            $statusText = 'Đã hủy';
                            $statusClass = 'status-cancel';
                        }

                        $firstDetail = $bk->bookingDetails->first();
                        $service = $firstDetail->service ?? null;
                    @endphp

                    <div class="timeline-item">
                        <div class="timeline-dot"></div>

                        <div class="booking-card">
                            <div class="time-box">
                                <div class="booking-time">{{ $bk->booking_time }}</div>
                                <div class="booking-duration">
                                    {{ $service->duration ?? '60' }} phút
                                </div>

                                <span class="status-badge {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </div>

                            <div class="booking-main">
                                <div class="booking-customer">
                                    {{ $bk->customer->full_name ?? 'Không rõ khách hàng' }}
                                </div>

                                <div class="booking-service">
                                    {{ $service->service_name ?? 'Không rõ dịch vụ' }}
                                </div>

                                <div class="booking-address">
                                    <i class="bi bi-geo-alt"></i>
                                    {{ $bk->address }}
                                </div>

                                <div class="mini-map"></div>

                                <div class="card-footer-row">
                                    <div class="price-text">
                                        {{ number_format($bk->total_amount) }} VNĐ
                                    </div>

                                    <a href="{{ route('staff.bookings.show', $bk->booking_id) }}"
                                       class="btn-detail">
                                        <i class="bi bi-eye"></i>
                                        Chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

            @empty
                <div class="alert alert-info text-center">
                    Hiện chưa có lịch làm việc nào.
                </div>
            @endforelse

        </div>
    </div>

    <div class="side-panel">

        <div class="summary-card">
            <div class="side-title">Tổng quan ca làm</div>

            <div class="summary-grid">
                <div class="summary-box">
                    <div class="summary-label">Hoàn thành</div>
                    <div class="summary-number">
                        {{ $completedCount }}/{{ $bookings->count() }}
                    </div>
                </div>

                <div class="summary-box">
                    <div class="summary-label">Lịch hôm nay</div>
                    <div class="summary-number">
                        {{ $todayBookings->count() }}
                    </div>
                </div>
            </div>

            <div class="progress-text">
                <span>Tiến độ công việc</span>
                <span>{{ $progressPercent }}%</span>
            </div>

            <div class="progress-line">
                <span style="width: {{ $progressPercent }}%"></span>
            </div>
        </div>

        <div class="white-side-card">
            <div class="side-title">Trạng thái làm việc</div>

            <div class="availability-row">
                <div>
                    <div class="availability-label">Sẵn sàng nhận lịch</div>
                </div>

                <div class="form-check form-switch m-0">
                    <input class="form-check-input custom-switch" type="checkbox" checked>
                </div>
            </div>

            <div class="availability-row">
                <div class="availability-label">Nghỉ trưa</div>
                <span class="break-pill">12:00 - 13:00</span>
            </div>

            <div class="availability-row">
                <div class="availability-label">Nghỉ ngắn</div>
                <span class="break-pill">16:00 - 16:15</span>
            </div>

            <div class="availability-row">
                <div class="availability-label">Nghỉ tối</div>
                <span class="break-pill">19:00 - 19:30</span>
            </div>
        </div>

        <div>
            <div class="side-title" style="font-size:14px; color:#9b8f8f; margin-bottom:12px;">
                NHẮC NHỞ
            </div>

            <div class="d-flex flex-column gap-3">
                <div class="reminder-item">
                    <div class="reminder-icon">
                        <i class="bi bi-bag-check"></i>
                    </div>
                    <div>
                        <div class="reminder-title">Chuẩn bị dụng cụ</div>
                        <div class="reminder-sub">Kiểm tra đồ nghề trước khi đi làm</div>
                    </div>
                </div>

                <div class="reminder-item">
                    <div class="reminder-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <div class="reminder-title">Kiểm tra địa chỉ</div>
                        <div class="reminder-sub">Xác nhận địa chỉ trước lịch hẹn</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection