@extends('staff.layout')

@section('title','Danh sách lịch đặt')

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

    .page-title{
        font-size:24px;
        font-weight:900;
        font-family:'Noto Serif', serif;
        color:var(--primary);
        margin-bottom:22px;
    }

    .filter-bar{
        display:grid;
        grid-template-columns:1fr 1fr 1fr auto;
        gap:16px;
        align-items:end;
        margin-bottom:28px;
    }

    .filter-group label{
        font-size:13px;
        font-weight:900;
        color:#5f5656;
        margin-bottom:7px;
    }

    .filter-control{
        width:100%;
        border:none;
        background:white;
        border-radius:14px;
        padding:13px 15px;
        font-size:14px;
        font-weight:700;
        color:#5f5656;
        box-shadow:0 10px 25px rgba(123,85,84,0.06);
        border:1px solid #f2e6e6;
    }

    .filter-control:focus{
        outline:none;
        border-color:var(--accent);
        box-shadow:0 0 0 4px rgba(235,186,185,0.28);
    }

    .request-count{
        font-size:13px;
        color:#7d7272;
        font-weight:800;
        padding-bottom:13px;
        white-space:nowrap;
    }

    .booking-grid{
        display:grid;
        grid-template-columns:repeat(3, minmax(0, 1fr));
        gap:24px;
    }

    @media(max-width:1200px){
        .booking-grid{
            grid-template-columns:repeat(2, minmax(0, 1fr));
        }

        .filter-bar{
            grid-template-columns:1fr 1fr;
        }
    }

    @media(max-width:768px){
        .booking-grid{
            grid-template-columns:1fr;
        }

        .filter-bar{
            grid-template-columns:1fr;
        }

        .request-count{
            padding-bottom:0;
        }
    }

    .job-card{
        background:white;
        border:1px solid #f1e7e7;
        border-radius:22px;
        overflow:hidden;
        box-shadow:0 14px 35px rgba(123,85,84,0.08);
        transition:0.28s;
        height:100%;
        display:flex;
        flex-direction:column;
    }

    .job-card:hover{
        transform:translateY(-5px);
        box-shadow:0 22px 55px rgba(123,85,84,0.13);
    }

    .image-wrap{
        height:190px;
        position:relative;
        overflow:hidden;
        background:#f8eeee;
    }

    .job-image{
        width:100%;
        height:100%;
        object-fit:cover;
        display:block;
    }

    .price-badge{
        position:absolute;
        right:14px;
        top:14px;
        background:rgba(255,255,255,0.92);
        color:var(--primary);
        padding:8px 13px;
        border-radius:999px;
        font-size:13px;
        font-weight:900;
        box-shadow:0 8px 20px rgba(123,85,84,0.12);
    }

    .status-badge{
        position:absolute;
        left:14px;
        bottom:14px;
        background:rgba(123,85,84,0.88);
        color:white;
        padding:7px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
    }

    .job-body{
        padding:20px;
        flex:1;
        display:flex;
        flex-direction:column;
    }

    .service-name{
        font-size:22px;
        font-weight:900;
        font-family:'Noto Serif', serif;
        color:var(--text);
        line-height:1.25;
        margin-bottom:8px;
    }

    .duration{
        display:flex;
        align-items:center;
        gap:7px;
        color:#8a7d7d;
        font-size:13px;
        font-weight:800;
        margin-bottom:14px;
    }

    .info-list{
        display:flex;
        flex-direction:column;
        gap:9px;
        margin-bottom:18px;
    }

    .info-item{
        display:flex;
        align-items:flex-start;
        gap:9px;
        color:#6b5c5c;
        font-size:13px;
        font-weight:700;
        line-height:1.5;
    }

    .info-item i{
        color:var(--accent);
        font-size:16px;
        margin-top:1px;
    }

    .job-footer{
        margin-top:auto;
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:12px;
        padding-top:16px;
        border-top:1px dashed rgba(123,85,84,0.15);
    }

    .method-pill{
        padding:7px 10px;
        border-radius:999px;
        background:rgba(235,186,185,0.22);
        color:var(--primary);
        font-size:11px;
        font-weight:900;
        text-transform:uppercase;
    }

    .btn-accept{
        border:none;
        background:var(--primary);
        color:white;
        padding:10px 16px;
        border-radius:12px;
        font-size:13px;
        font-weight:900;
        transition:0.25s;
        white-space:nowrap;
    }

    .btn-accept:hover{
        background:var(--primary-dark);
        transform:translateY(-2px);
    }

    .empty-box{
        background:white;
        border:1px dashed rgba(235,186,185,0.9);
        border-radius:26px;
        padding:44px;
        text-align:center;
        color:var(--muted);
        font-weight:800;
        box-shadow:0 14px 38px rgba(123,85,84,0.06);
    }

    .empty-box i{
        font-size:46px;
        color:var(--accent);
        display:block;
        margin-bottom:14px;
    }
</style>

<div class="page-title">
    Danh sách lịch đặt
</div>

<div class="filter-bar">

    <div class="filter-group">
        <label>Dịch vụ</label>
        <select class="filter-control" id="serviceFilter">
            <option value="">Tất cả dịch vụ</option>
            @foreach($bookings as $booking)
                @foreach($booking->bookingDetails as $detail)
                    @if($detail->service)
                        <option value="{{ strtolower($detail->service->service_name) }}">
                            {{ $detail->service->service_name }}
                        </option>
                    @endif
                @endforeach
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label>Khu vực</label>
        <select class="filter-control" id="areaFilter">
            <option value="">Tất cả quận/huyện</option>
            <option value="quận 1">Quận 1</option>
            <option value="quận 2">Quận 2</option>
            <option value="quận 3">Quận 3</option>
            <option value="quận 7">Quận 7</option>
            <option value="bình thạnh">Bình Thạnh</option>
            <option value="gò vấp">Gò Vấp</option>
            <option value="thủ đức">Thủ Đức</option>
        </select>
    </div>

    <div class="filter-group">
        <label>Ngày thực hiện</label>
        <input type="date" class="filter-control" id="dateFilter">
    </div>

    <div class="request-count">
        Đang có {{ $bookings->count() }} yêu cầu mới
    </div>

</div>

@if($bookings->count() == 0)

    <div class="empty-box">
        <i class="bi bi-calendar-x"></i>
        Hiện chưa có lịch đặt nào đang chờ nhận.
    </div>

@else

    <div class="booking-grid" id="bookingGrid">

        @foreach($bookings as $booking)

            @php
                $firstDetail = $booking->bookingDetails->first();
                $firstService = $firstDetail->service ?? null;

                $serviceNames = $booking->bookingDetails->map(function($detail){
                    return $detail->service->service_name ?? 'Không rõ dịch vụ';
                })->implode(', ');

                $duration = $firstService->duration ?? 60;

                $serviceImage = $firstService && $firstService->image
                    ? asset('uploads/services/' . $firstService->image)
                    : asset('uploads/services/default.jpg');

                $bookingDate = \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d');
            @endphp

            <div class="job-card"
                 data-service="{{ strtolower($serviceNames) }}"
                 data-address="{{ strtolower($booking->address) }}"
                 data-date="{{ $bookingDate }}">

                <div class="image-wrap">
                    <img src="{{ $serviceImage }}"
                         class="job-image"
                         alt="{{ $serviceNames }}">

                    <div class="price-badge">
                        {{ number_format($booking->total_amount) }}đ
                    </div>

                    <div class="status-badge">
                        Mới nhất
                    </div>
                </div>

                <div class="job-body">

                    <div class="service-name">
                        {{ $firstService->service_name ?? 'Không rõ dịch vụ' }}
                    </div>

                    <div class="duration">
                        <i class="bi bi-clock"></i>
                        {{ $duration }} phút
                    </div>

                    <div class="info-list">

                        <div class="info-item">
                            <i class="bi bi-calendar-event"></i>
                            <span>
                                {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d/m/Y') }}
                            </span>
                        </div>

                        <div class="info-item">
                            <i class="bi bi-clock-history"></i>
                            <span>
                                {{ $booking->booking_time }}
                            </span>
                        </div>

                        <div class="info-item">
                            <i class="bi bi-geo-alt"></i>
                            <span>
                                {{ $booking->address }}
                            </span>
                        </div>

                        <div class="info-item">
                            <i class="bi bi-person"></i>
                            <span>
                                {{ $booking->customer->full_name ?? 'Khách hàng' }}
                            </span>
                        </div>

                    </div>

                    <div class="job-footer">

                        <span class="method-pill">
                            {{ $booking->payment->payment_method ?? 'COD' }}
                        </span>

                        <form action="{{ route('staff.bookings.accept', $booking->booking_id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="btn-accept"
                                    onclick="return confirm('Bạn chắc chắn muốn nhận lịch này?')">
                                Nhận lịch ngay
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

@endif

<script>
    const serviceFilter = document.getElementById('serviceFilter');
    const areaFilter = document.getElementById('areaFilter');
    const dateFilter = document.getElementById('dateFilter');

    function applyJobFilters() {
        const serviceValue = serviceFilter.value.toLowerCase();
        const areaValue = areaFilter.value.toLowerCase();
        const dateValue = dateFilter.value;

        document.querySelectorAll('.job-card').forEach(card => {
            const service = card.dataset.service || '';
            const address = card.dataset.address || '';
            const date = card.dataset.date || '';

            const matchService = !serviceValue || service.includes(serviceValue);
            const matchArea = !areaValue || address.includes(areaValue);
            const matchDate = !dateValue || date === dateValue;

            card.style.display = (matchService && matchArea && matchDate) ? 'flex' : 'none';
        });
    }

    serviceFilter.addEventListener('change', applyJobFilters);
    areaFilter.addEventListener('change', applyJobFilters);
    dateFilter.addEventListener('change', applyJobFilters);
</script>

@endsection