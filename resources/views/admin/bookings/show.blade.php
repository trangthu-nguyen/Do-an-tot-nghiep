@extends('admin.layout')

@section('title', 'Chi tiết Lịch đặt')

@section('content')
<h2 class="fw-bold mb-3">Chi tiết Lịch đặt #{{ $booking->booking_id }}</h2>

<div class="card shadow-sm p-3 mb-4">
    <p><b>Khách hàng:</b> {{ $booking->customer->full_name ?? 'N/A' }} (ID: {{ $booking->customer_id }})</p>

    <p><b>Số điện thoại:</b> {{ $booking->customer->phone ?? 'N/A' }}</p>

    <p><b>Địa chỉ:</b> {{ $booking->address ?? 'N/A' }}</p>

    <p><b>Dịch vụ:</b>
        <ul>
            @foreach($booking->bookingDetails as $detail)
                <li>{{ $detail->service->service_name ?? 'N/A' }} ({{ number_format($detail->price) }} VNĐ)</li>
            @endforeach
        </ul>
    </p>

    <p><b>Tổng tiền:</b> <span class="text-danger fw-bold">{{ number_format($booking->total_amount) }} VNĐ</span></p>

    <hr>

    <h5 class="fw-bold">Thông tin thanh toán</h5>

    @if($booking->payment)
        <p><b>Phương thức:</b>
            @if($booking->payment->payment_method == 'online')
                Thanh toán trực tuyến
            @elseif($booking->payment->payment_method == 'cod')
                Thanh toán khi hoàn thành
            @else
                {{ $booking->payment->payment_method }}
            @endif
        </p>

        <p><b>Số tiền thanh toán:</b> {{ number_format($booking->payment->amount) }} VNĐ</p>

        <p><b>Trạng thái thanh toán:</b>
            @if($booking->payment->payment_status == 'paid')
                <span class="badge bg-success">Đã thanh toán</span>
            @else
                <span class="badge bg-warning text-dark">Chờ thanh toán</span>
            @endif
        </p>
    @else
        <p class="text-muted">(Chưa có dữ liệu thanh toán)</p>
    @endif

    <hr>

    <p><b>Ngày đặt:</b> {{ $booking->booking_date }}</p>
    <p><b>Giờ đặt:</b> {{ $booking->booking_time }}</p>
    <p><b>Staff ID:</b> {{ $booking->staff_id ?? 'Chưa phân công' }}</p>

    <p><b>Trạng thái hiện tại:</b>
        @if($booking->status == 0)
            <span class="badge bg-warning">Chờ xác nhận</span>
        @elseif($booking->status == 1)
            <span class="badge" style="background:#ebbab9; color:#603d3d;">Đã xác nhận</span>
        @elseif($booking->status == 2)
            <span class="badge bg-primary">Đang thực hiện</span>
        @elseif($booking->status == 3)
            <span class="badge bg-success">Hoàn thành</span>
        @elseif($booking->status == 4)
            <span class="badge bg-danger">Đã hủy</span>
        @else
            <span class="badge bg-secondary">Không rõ</span>
        @endif
    </p>
</div>

{{-- Form phân staff --}}
<div class="card shadow-sm p-3 mb-4">
    <h5 class="fw-bold">Phân công nhân viên</h5>

    <form action="{{ route('admin.bookings.assignStaff', $booking->booking_id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Chọn Staff</label>
            <select name="staff_id" class="form-select">
                <option value="">-- Chọn nhân viên --</option>

                @foreach($staffs as $staff)
                    <option value="{{ $staff->staff_id }}"
                        {{ $booking->staff_id == $staff->staff_id ? 'selected' : '' }}>
                        {{ $staff->full_name }} (ID: {{ $staff->staff_id }})
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Phân công</button>
    </form>
</div>

{{-- Form cập nhật status --}}
<div class="card shadow-sm p-3 mb-4">
    <h5 class="fw-bold">Cập nhật trạng thái</h5>

    <form action="{{ route('admin.bookings.updateStatus', $booking->booking_id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="0" {{ $booking->status == 0 ? 'selected' : '' }}>Chờ xác nhận</option>
                <option value="1" {{ $booking->status == 1 ? 'selected' : '' }}>Đã xác nhận</option>
                <option value="2" {{ $booking->status == 2 ? 'selected' : '' }}>Đang thực hiện</option>
                <option value="3" {{ $booking->status == 3 ? 'selected' : '' }}>Hoàn thành</option>
                <option value="4" {{ $booking->status == 4 ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>

        <button class="btn btn-success">Cập nhật</button>
    </form>
</div>

<a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Quay lại</a>
@endsection