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
        grid-template-columns:1fr 1fr 1fr 1fr auto;
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
        border:1px solid #f2e6e6;
        box-shadow:0 10px 25px rgba(123,85,84,.06);
    }

    .filter-control:focus{
        outline:none;
        border-color:var(--accent);
        box-shadow:0 0 0 4px rgba(235,186,185,.28);
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
        grid-template-columns:repeat(3,minmax(0,1fr));
        gap:24px;
    }

    @media(max-width:1200px){
        .booking-grid{
            grid-template-columns:repeat(2,minmax(0,1fr));
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
        background:#fff;
        border:1px solid #f1e7e7;
        border-radius:22px;
        overflow:hidden;
        display:flex;
        flex-direction:column;
        height:100%;
        transition:.3s;
        box-shadow:0 14px 35px rgba(123,85,84,.08);
    }

    .job-card:not(.disabled-card):hover{
        transform:translateY(-5px);
        box-shadow:0 22px 55px rgba(123,85,84,.13);
    }

    .disabled-card{
        opacity:.55;
        filter:grayscale(.35);
        cursor:not-allowed;
    }

    .image-wrap{
        height:190px;
        overflow:hidden;
        position:relative;
        background:#f8eeee;
    }

    .job-image{
        width:100%;
        height:100%;
        object-fit:cover;
        display:block;
    }

    .booking-id-badge{
        position:absolute;
        left:14px;
        top:14px;
        background:rgba(255,255,255,.95);
        color:var(--primary);
        padding:7px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
        box-shadow:0 8px 20px rgba(123,85,84,.12);
    }

    .price-badge{
        position:absolute;
        right:14px;
        top:14px;
        background:rgba(255,255,255,.95);
        color:var(--primary);
        padding:8px 13px;
        border-radius:999px;
        font-size:13px;
        font-weight:900;
        box-shadow:0 8px 20px rgba(123,85,84,.12);
    }

    .status-badge{
        position:absolute;
        left:14px;
        bottom:14px;
        background:rgba(123,85,84,.9);
        color:#fff;
        padding:7px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
    }

    .status-disabled{
        background:#9c9c9c;
    }

    .job-body{
        padding:20px;
        display:flex;
        flex-direction:column;
        flex:1;
    }

    .service-name{
        font-size:22px;
        font-weight:900;
        font-family:'Noto Serif',serif;
        color:var(--text);
        line-height:1.3;
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
        border-top:1px dashed rgba(123,85,84,.15);
    }

    .method-pill{
        padding:7px 10px;
        border-radius:999px;
        background:rgba(235,186,185,.22);
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
        transition:.25s;
        white-space:nowrap;
    }

    .btn-accept:hover{
        background:var(--primary-dark);
        transform:translateY(-2px);
    }

    .btn-disabled{
        background:#bfbfbf !important;
        color:#fff !important;
        cursor:not-allowed !important;
        pointer-events:none;
        box-shadow:none;
        transform:none !important;
    }

    .empty-box{
        background:#fff;
        border:1px dashed rgba(235,186,185,.9);
        border-radius:26px;
        padding:44px;
        text-align:center;
        color:var(--muted);
        font-weight:800;
        box-shadow:0 14px 38px rgba(123,85,84,.06);
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
            @php
                $serviceOptions = [];
            @endphp

            @foreach($bookings as $booking)
                @foreach($booking->bookingDetails as $detail)
                    @if($detail->service)
                        @php
                            $serviceName = $detail->service->service_name;
                        @endphp

                        @if(!in_array($serviceName, $serviceOptions))
                            @php
                                $serviceOptions[] = $serviceName;
                            @endphp
                            <option value="{{ strtolower($serviceName) }}">
                                {{ $serviceName }}
                            </option>
                        @endif
                    @endif
                @endforeach
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label>Quận/Huyện</label>
        <select class="filter-control" id="districtFilter">
            <option value="">Tất cả quận/huyện</option>
            <option value="ba đình">Ba Đình</option>
            <option value="hoàn kiếm">Hoàn Kiếm</option>
            <option value="đống đa">Đống Đa</option>
            <option value="hai bà trưng">Hai Bà Trưng</option>
            <option value="cầu giấy">Cầu Giấy</option>
            <option value="thanh xuân">Thanh Xuân</option>
            <option value="hoàng mai">Hoàng Mai</option>
            <option value="long biên">Long Biên</option>
            <option value="hà đông">Hà Đông</option>
            <option value="nam từ liêm">Nam Từ Liêm</option>
            <option value="bắc từ liêm">Bắc Từ Liêm</option>
            <option value="tây hồ">Tây Hồ</option>
            <option value="sóc sơn">Sóc Sơn</option>
            <option value="đông anh">Đông Anh</option>
            <option value="gia lâm">Gia Lâm</option>
            <option value="thanh trì">Thanh Trì</option>
            <option value="hoài đức">Hoài Đức</option>
            <option value="thạch thất">Thạch Thất</option>
            <option value="quốc oai">Quốc Oai</option>
            <option value="chương mỹ">Chương Mỹ</option>
        </select>
    </div>

    <div class="filter-group">
        <label>Phường/Xã</label>
        <select class="filter-control" id="wardFilter">
            <option value="">Tất cả phường/xã</option>
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

            @php
                $isBusyDay = in_array($bookingDate, $busyDates);
            @endphp
            <div class="job-card {{ $isBusyDay ? 'disabled-card' : '' }}"
                 data-service="{{ strtolower($serviceNames) }}"
                 data-address="{{ strtolower($booking->address ?? '') }}"
                 data-date="{{ $bookingDate }}">

                <div class="image-wrap">
                    <img src="{{ $serviceImage }}"
                         class="job-image"
                         alt="{{ $serviceNames }}">

                    <div class="booking-id-badge">
                        Booking #{{ $booking->booking_id }}
                    </div>

                    <div class="price-badge">
                        {{ number_format($booking->total_amount) }}đ
                    </div>

                    <div class="status-badge">
                        Chờ nhận
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
                            <i class="bi bi-hash"></i>
                            <span>
                                Mã booking: #{{ $booking->booking_id }}
                            </span>
                        </div>

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

                        @php
                            $isBusyDate = in_array($booking->booking_date, $busyDates);
                        @endphp

                        @if($isBusyDate)

                            <button class="btn-accept btn-disabled" disabled>
                                Đang nghỉ
                            </button>

                        @else

                            <form action="{{ route('staff.bookings.accept', $booking->booking_id) }}" method="POST">
                                @csrf

                                <button type="submit"
                                        class="btn-accept"
                                        onclick="return confirm('Bạn chắc chắn muốn nhận lịch này?')">
                                    Nhận lịch ngay
                                </button>
                            </form>

                        @endif

                    </div>

                </div>

            </div>

        @endforeach

    </div>

@endif

<script>
    const serviceFilter = document.getElementById('serviceFilter');
    const districtFilter = document.getElementById('districtFilter');
    const wardFilter = document.getElementById('wardFilter');
    const dateFilter = document.getElementById('dateFilter');

    const hanoiWards = {
        "ba đình": ["kim mã", "liễu giai", "ngọc hà", "đội cấn", "trúc bạch", "phúc xá", "cống vị"],
        "hoàn kiếm": ["hàng bài", "tràng tiền", "lý thái tổ", "hàng bạc", "hàng bồ", "cửa nam", "phan chu trinh"],
        "đống đa": ["láng hạ", "ô chợ dừa", "quang trung", "trung liệt", "cát linh", "văn chương", "khương thượng"],
        "hai bà trưng": ["bạch mai", "minh khai", "ngô thì nhậm", "bách khoa", "thanh nhàn", "quỳnh mai"],
        "cầu giấy": ["dịch vọng", "dịch vọng hậu", "trung hòa", "yên hòa", "nghĩa đô", "nghĩa tân", "mai dịch"],
        "thanh xuân": ["thanh xuân trung", "hạ đình", "khương đình", "nhân chính", "khương mai", "kim giang"],
        "hoàng mai": ["giáp bát", "mai động", "tân mai", "hoàng liệt", "định công", "lĩnh nam"],
        "long biên": ["ngọc lâm", "gia thụy", "bồ đề", "việt hưng", "long biên", "sài đồng"],
        "hà đông": ["văn quán", "mộ lao", "quang trung", "hà cầu", "phúc la", "yết kiêu"],
        "nam từ liêm": ["mỹ đình 1", "mỹ đình 2", "trung văn", "cầu diễn", "phú đô", "mễ trì"],
        "bắc từ liêm": ["cổ nhuế", "cổ nhuế 1", "cổ nhuế 2", "đông ngạc", "xuân đỉnh", "phú diễn"],
        "tây hồ": ["bưởi", "thụy khuê", "yên phụ", "quảng an", "nhật tân", "tứ liên"],
        "sóc sơn": ["phù linh", "tiên dược", "mai đình", "quang tiến", "tân minh"],
        "đông anh": ["đông anh", "uy nỗ", "cổ loa", "vĩnh ngọc", "nam hồng"],
        "gia lâm": ["trâu quỳ", "dương xá", "đa tốn", "kiêu kỵ", "ninh hiệp"],
        "thanh trì": ["văn điển", "tân triều", "ngọc hồi", "tả thanh oai", "hữu hòa"],
        "hoài đức": ["trạm trôi", "an khánh", "vân canh", "la phù", "đức giang"],
        "thạch thất": ["liên quan", "thạch hòa", "bình phú", "hữu bằng", "canh nậu"],
        "quốc oai": ["quốc oai", "sài sơn", "phượng cách", "đông yên", "ngọc mỹ"],
        "chương mỹ": ["chúc sơn", "xuân mai", "phú nghĩa", "đông phương yên", "ngọc hòa"]
    };

    function loadWardFilter() {
        const districtValue = districtFilter.value;
        wardFilter.innerHTML = '<option value="">Tất cả phường/xã</option>';

        if (!districtValue || !hanoiWards[districtValue]) {
            applyJobFilters();
            return;
        }

        hanoiWards[districtValue].forEach(ward => {
            const option = document.createElement('option');
            option.value = ward;
            option.textContent = ward.charAt(0).toUpperCase() + ward.slice(1);
            wardFilter.appendChild(option);
        });

        applyJobFilters();
    }

    function applyJobFilters() {
        const serviceValue = serviceFilter.value.toLowerCase();
        const districtValue = districtFilter.value.toLowerCase();
        const wardValue = wardFilter.value.toLowerCase();
        const dateValue = dateFilter.value;

        document.querySelectorAll('.job-card').forEach(card => {
            const service = card.dataset.service || '';
            const address = card.dataset.address || '';
            const date = card.dataset.date || '';

            const matchService = !serviceValue || service.includes(serviceValue);
            const matchDistrict = !districtValue || address.includes(districtValue);
            const matchWard = !wardValue || address.includes(wardValue);
            const matchDate = !dateValue || date === dateValue;

            card.style.display = (matchService && matchDistrict && matchWard && matchDate) ? 'flex' : 'none';
        });
    }

    serviceFilter.addEventListener('change', applyJobFilters);
    districtFilter.addEventListener('change', loadWardFilter);
    wardFilter.addEventListener('change', applyJobFilters);
    dateFilter.addEventListener('change', applyJobFilters);
</script>

@endsection