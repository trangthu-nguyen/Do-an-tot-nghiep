@extends('admin.layout')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="row g-4">

    {{-- Card 1 --}}
    <div class="col-md-6 col-lg-3">
        <div class="card-ui">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted fw-semibold" style="font-size: 13px;">Tổng đặt lịch</div>
                    <div class="fw-bold" style="font-size: 26px; color: #7b5554;">
                        {{ $totalBookings ?? 0 }}
                    </div>
                </div>
                <div style="font-size: 28px;">📅</div>
            </div>
        </div>
    </div>

    {{-- Card 2 --}}
    <div class="col-md-6 col-lg-3">
        <div class="card-ui">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted fw-semibold" style="font-size: 13px;">Dịch vụ</div>
                    <div class="fw-bold" style="font-size: 26px; color: #7b5554;">
                        {{ $totalServices ?? 0 }}
                    </div>
                </div>
                <div style="font-size: 28px;">💆‍♀️</div>
            </div>
        </div>
    </div>

    {{-- Card 3 --}}
    <div class="col-md-6 col-lg-3">
        <div class="card-ui">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted fw-semibold" style="font-size: 13px;">Nhân viên</div>
                    <div class="fw-bold" style="font-size: 26px; color: #7b5554;">
                        {{ $totalStaff ?? 0 }}
                    </div>
                </div>
                <div style="font-size: 28px;">👩‍🔧</div>
            </div>
        </div>
    </div>

    {{-- Card 4 --}}
    <div class="col-md-6 col-lg-3">
        <div class="card-ui">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted fw-semibold" style="font-size: 13px;">Khách hàng</div>
                    <div class="fw-bold" style="font-size: 26px; color: #7b5554;">
                        {{ $totalCustomers ?? 0 }}
                    </div>
                </div>
                <div style="font-size: 28px;">👤</div>
            </div>
        </div>
    </div>

</div>

{{-- Table --}}
<div class="mt-4 card-ui">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 fw-bold" style="font-family: 'Noto Serif', serif; color: #7b5554;">
            Đặt lịch gần đây
        </h5>

        <a href="{{ url('/admin/bookings') }}" class="btn btn-primary-ui">
            Xem tất cả
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr style="color:#504443;">
                    <th>#</th>
                    <th>Khách hàng</th>
                    <th>Dịch vụ</th>
                    <th>Ngày</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
            @if(isset($recentBookings) && count($recentBookings) > 0)
                @foreach($recentBookings as $index => $b)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $b->customer_name ?? '---' }}</td>
                        <td>{{ $b->service_name ?? '---' }}</td>
                        <td>{{ $b->booking_date ?? '---' }}</td>
                        <td>
                            <span class="badge rounded-pill"
                                  style="background:#ebbab9; color:#6d4848;">
                                {{ $b->status ?? 'Pending' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Chưa có dữ liệu đặt lịch
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>

@endsection