@extends('admin.layout')

@section('title', 'Quản lý Booking')

@section('content')

<h3 class="fw-bold mb-3">📌 Danh sách lịch đặt</h3>

<div class="card shadow-sm">
    <div class="card-body">

        <table class="table table-bordered table-hover align-middle">
            <thead style="background:#efeded; color:#504443;">
                <tr>
                    <th>ID</th>
                    <th>Khách hàng</th>
                    <th>Dịch vụ</th>
                    <th>Nhân viên</th>
                    <th>Ngày</th>
                    <th>Giờ</th>
                    <th width="220">Trạng thái</th>
                    <th width="120">Chi tiết</th>
                </tr>
            </thead>

            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->booking_id }}</td>

                        <td>{{ $booking->customer->full_name ?? 'Chưa có' }}</td>

                        {{-- FIX: bỏ dấu "-" trước dịch vụ --}}
                        <td>
                            @foreach($booking->bookingDetails as $detail)
                                <div>{{ $detail->service->service_name ?? 'Chưa có' }}</div>
                            @endforeach
                        </td>

                        <td>
                            @if($booking->staff)
                                {{ $booking->staff->full_name }}
                            @else
                                <span class="text-danger">Chưa phân công</span>
                            @endif
                        </td>

                        <td>{{ $booking->booking_date }}</td>
                        <td>{{ $booking->booking_time }}</td>

                        {{-- Trạng thái có thể chỉnh trực tiếp --}}
                        <td>
                            <form action="{{ route('admin.bookings.updateStatus', $booking->booking_id) }}" method="POST" class="d-flex gap-2">
                                @csrf

                                <select name="status" class="form-select form-select-sm">
                                    <option value="0" {{ $booking->status == 0 ? 'selected' : '' }}>Chờ xác nhận</option>
                                    <option value="1" {{ $booking->status == 1 ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="2" {{ $booking->status == 2 ? 'selected' : '' }}>Đang thực hiện</option>
                                    <option value="3" {{ $booking->status == 3 ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="4" {{ $booking->status == 4 ? 'selected' : '' }}>Đã hủy</option>
                                </select>

                                <button class="btn btn-sm btn-success">
                                    Lưu
                                </button>
                            </form>
                        </td>

                        <td>
                            <a href="{{ route('admin.bookings.show', $booking->booking_id) }}" class="btn btn-sm btn-primary">
                                Xem
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Chưa có booking nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@endsection