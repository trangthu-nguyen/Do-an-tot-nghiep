@extends('admin.layout')

@section('title', 'Chào buổi sáng, Admin')
@section('page-title', 'Dashboard')

@section('content')

<style>
    .dash-sub {
        color: #8b7b7a;
        font-size: 14px;
        margin-bottom: 24px;
    }

    .stat-card,
    .dashboard-card {
        background: #fff;
        border-radius: 24px;
        border: 1px solid #f0e4e4;
        box-shadow: 0 12px 28px rgba(123,85,84,.08);
    }

    .stat-card {
        padding: 22px;
        min-height: 120px;
    }

    .dashboard-card {
        padding: 24px;
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: #f8e6e6;
        color: #7b5554;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
        font-size: 20px;
    }

    .stat-label {
        font-size: 12px;
        color: #8b7b7a;
        text-transform: uppercase;
        font-weight: 700;
    }

    .stat-value {
        font-size: 26px;
        font-weight: 800;
        color: #2f2424;
        margin-top: 4px;
    }

    .stat-up {
        font-size: 12px;
        color: #35a66f;
        font-weight: 700;
    }

    .top-staff-avatar {
        width: 92px;
        height: 92px;
        object-fit: cover;
        border-radius: 50%;
        border: 5px solid #f8dede;
    }

    .staff-mini-avatar {
        width: 28px;
        height: 28px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #f8dede;
    }

    .soft-btn {
        background: #f6eeee;
        border: none;
        border-radius: 14px;
        color: #7b5554;
        padding: 9px 16px;
        font-weight: 700;
        text-decoration: none;
        font-size: 13px;
    }

    .soft-btn:hover {
        background: #ecd6d6;
        color: #6d4848;
    }

    .filter-pill {
        padding: 6px 14px;
        border-radius: 999px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #eadede;
        color: #8b6f6f;
        background: #fff;
        transition: .25s;
    }

    .filter-pill:hover {
        background: #f9eeee;
        color: #7b5554;
    }

    .filter-pill.active {
        background: #f3dede;
        color: #7b5554;
        border-color: #e7caca;
    }

    .status-pill {
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
    }

    .status-pending { background: #fff3cd; color: #b7791f; }
    .status-confirmed { background: #d1fae5; color: #047857; }
    .status-doing { background: #ede9fe; color: #6d28d9; }
    .status-completed { background: #dbeafe; color: #1d4ed8; }
    .status-cancelled { background: #fee2e2; color: #b91c1c; }
    .status-default { background: #f1f1f1; color: #555; }

    .table td, .table th {
        vertical-align: middle;
        font-size: 13px;
    }

    .rating-stars {
        color: #f5a623;
        font-size: 15px;
        letter-spacing: 1px;
    }
</style>

@php
    $femalePortraits = [
        'https://randomuser.me/api/portraits/women/44.jpg',
        'https://randomuser.me/api/portraits/women/65.jpg',
        'https://randomuser.me/api/portraits/women/68.jpg',
        'https://randomuser.me/api/portraits/women/71.jpg',
        'https://randomuser.me/api/portraits/women/72.jpg',
        'https://randomuser.me/api/portraits/women/76.jpg',
        'https://randomuser.me/api/portraits/women/79.jpg',
        'https://randomuser.me/api/portraits/women/81.jpg'
    ];

    $topStaffAvatar = asset('images/default-avatar.png');

    if (!empty($topStaffId)) {
        $topStaffAvatar = $femalePortraits[$topStaffId % count($femalePortraits)];
    } elseif (!empty($topStaffImage)) {
        $topStaffAvatar = $topStaffImage;
    }
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="dash-sub">Đây là tình hình hoạt động của BeautyHome hôm nay.</div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.bookings.index') }}" class="soft-btn">
            <i class="bi bi-calendar-plus"></i> Lịch đặt
        </a>

        <a href="{{ route('admin.services.index') }}" class="btn btn-primary-ui">
            <i class="bi bi-plus-lg"></i> Dịch vụ mới
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-label">Doanh thu hôm nay</div>
            <div class="stat-value">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}đ</div>
            <div class="stat-up">+{{ $totalBookings ?? 0 }} lịch đặt</div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-calendar-heart"></i></div>
        <div class="stat-label">Lịch chờ xác nhận</div>
        <div class="stat-value">{{ $pendingBookingCount ?? 0 }}</div>
        <div class="stat-up">Cần xử lý</div>
    </div>
</div>

<div class="col-md-6 col-xl-3">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-credit-card"></i></div>
        <div class="stat-label">Thanh toán chờ xác nhận</div>
        <div class="stat-value">{{ $pendingPaymentCount ?? 0 }}</div>
        <div class="stat-up">Giao dịch pending</div>
    </div>
</div>

<div class="col-md-6 col-xl-3">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
        <div class="stat-label">Doanh thu tháng này</div>
        <div class="stat-value">{{ number_format($monthRevenue ?? 0, 0, ',', '.') }}đ</div>
        <div class="stat-up">Đã thanh toán</div>
    </div>
</div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1" style="color:#7b5554;">Tăng trưởng doanh thu</h5>
                    <div class="text-muted small">
                        {{ ($revenueFilter ?? 'month') == 'week' ? 'Theo dữ liệu doanh thu trong tuần' : 'Theo dữ liệu doanh thu hằng tháng' }}
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard', ['revenue_filter' => 'month']) }}"
                       class="filter-pill {{ ($revenueFilter ?? 'month') == 'month' ? 'active' : '' }}">
                        Tháng
                    </a>

                    <a href="{{ route('admin.dashboard', ['revenue_filter' => 'week']) }}"
                       class="filter-pill {{ ($revenueFilter ?? 'month') == 'week' ? 'active' : '' }}">
                        Tuần
                    </a>
                </div>
            </div>

            <canvas id="revenueChart" height="115"></canvas>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="dashboard-card text-center h-100">
            <h5 class="fw-bold mb-3" style="color:#7b5554;">Chuyên gia hàng đầu</h5>

            <img src="{{ $topStaffAvatar }}" class="top-staff-avatar mb-3" alt="Ảnh nhân viên">

            <div class="fw-bold" style="color:#2f2424;">
                {{ $topStaffName ?? 'Chưa có dữ liệu' }}
            </div>

            <div class="text-muted small mb-3">Nhân viên nổi bật</div>

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <div class="p-3 rounded-4" style="background:#faf4f4;">
                        <div class="fw-bold">{{ $topStaffBookings ?? 0 }}</div>
                        <div class="small text-muted">Lịch hẹn</div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="p-3 rounded-4" style="background:#faf4f4;">
                        <div class="rating-stars">★★★★★</div>
                        <div class="small text-muted">5.0</div>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.staffs.index') }}" class="soft-btn d-inline-block w-100">
                Xem hồ sơ đầy đủ
            </a>
        </div>
    </div>
</div>

<div class="dashboard-card mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0" style="color:#7b5554;">Lịch hẹn gần đây</h5>

        <a href="{{ route('admin.bookings.index') }}" class="text-decoration-none" style="color:#d49a9a;">
            Xem tất cả lịch →
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Khách hàng</th>
                    <th>Dịch vụ</th>
                    <th>Nhân viên</th>
                    <th>Ngày & Giờ</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>

            <tbody>
            @forelse($recentBookings ?? [] as $b)
                @php
                    $serviceName = optional($b->bookingDetails->first()?->service)->service_name ?? '---';

                    $staffImage = asset('images/default-avatar.png');

                    if ($b->staff) {
                        $staffImage = $femalePortraits[$b->staff->staff_id % count($femalePortraits)];
                    }

                    $statusText = match((string)$b->status) {
                        '0', 'pending' => 'Chờ xác nhận',
                        '1', 'confirmed' => 'Đã xác nhận',
                        '2', 'doing' => 'Đang thực hiện',
                        '3', 'completed' => 'Hoàn thành',
                        '4', 'cancelled' => 'Đã hủy',
                        default => $b->status ?? '---',
                    };

                    $statusClass = match((string)$b->status) {
                        '0', 'pending' => 'status-pending',
                        '1', 'confirmed' => 'status-confirmed',
                        '2', 'doing' => 'status-doing',
                        '3', 'completed' => 'status-completed',
                        '4', 'cancelled' => 'status-cancelled',
                        default => 'status-default',
                    };
                @endphp

                <tr>
                    <td>
                        <div class="fw-bold">{{ $b->customer->full_name ?? '---' }}</div>
                        <div class="text-muted small">{{ $b->customer->phone ?? '' }}</div>
                    </td>

                    <td>{{ $serviceName }}</td>

                    <td>

    @if($b->staff)

        <div class="d-flex align-items-center gap-2">

            <img src="{{ $staffImage }}"
                 class="staff-mini-avatar"
                 alt="Ảnh nhân viên">

            <span>{{ $b->staff->full_name }}</span>

        </div>

    @else

        <span class="text-muted">
            Chưa phân công
        </span>

    @endif

</td>

                    <td>
                        {{ $b->booking_date ?? '---' }}
                        <div class="text-muted small">{{ $b->booking_time ?? '' }}</div>
                    </td>

                    <td>
                        <span class="status-pill {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Chưa có dữ liệu đặt lịch
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="chart-data"
     data-months="{{ json_encode($months ?? []) }}"
     data-revenues="{{ json_encode($revenues ?? []) }}">
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const chartData = document.getElementById('chart-data');
const months = JSON.parse(chartData.dataset.months);
const revenues = JSON.parse(chartData.dataset.revenues);

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            {
                type: 'bar',
                label: 'Doanh thu',
                data: revenues,
                backgroundColor: '#f2d6df',
                borderRadius: 10
            },
            {
                type: 'line',
                label: 'Xu hướng',
                data: revenues,
                borderColor: '#d99a9a',
                backgroundColor: 'transparent',
                tension: 0.35,
                borderWidth: 2,
                pointRadius: 3
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f3eeee' }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});
</script>
@endsection