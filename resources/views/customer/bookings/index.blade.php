@extends('customer.layout')

@section('title', 'Lịch đã đặt')

@section('content')

<style>
    :root {
        --primary: #7b5554;
        --primary-dark: #6d4848;
        --primary-light: #ebbab9;

        --outline: #d4c2c2;
        --text-muted: #504443;
    }

    .page-title {
        font-family: 'Noto Serif', serif;
        font-weight: 800;
        font-size: 32px;
        color: #2f2323;
    }

    /* CARD BOOKING */
    .booking-card {
        background: white;
        border-radius: 22px;
        border: 1px solid rgba(212, 194, 194, 0.7);
        box-shadow: 0 18px 45px rgba(123, 85, 84, 0.08);
        padding: 20px;
        margin-bottom: 18px;
        transition: 0.3s;
        display: flex;
        gap: 18px;
        align-items: stretch;
    }

    .booking-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 22px 55px rgba(123, 85, 84, 0.14);
    }

    /* IMAGE */
    .booking-img {
        width: 400px;
        height: 270px;
        border-radius: 18px;
        object-fit: cover;
        border: 1px solid rgba(212, 194, 194, 0.6);
        flex-shrink: 0;
    }

    /* CONTENT */
    .booking-content {
        flex: 1;
        display: flex;
        justify-content: space-between;
        gap: 20px;
    }

    .booking-id {
        font-weight: 900;
        font-size: 15px;
        color: var(--primary);
        margin-bottom: 6px;
    }

    /* TITLE SMALL */
    .booking-service-title {
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 8px;
        color: #6b5c5c;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* LIST SERVICE */
    .service-list {
        list-style: none;
        padding: 0;
        margin: 0 0 14px 0;
    }

    .service-list li {
    font-family: 'Noto Serif', serif;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 6px;
    font-size: 24px;
    font-weight: 800;
    color: #2f2323;
    }

    .service-list li i {
        color: var(--primary);
        font-size: 15px;
    }

    /* STAFF */
    .staff-text {
        font-weight: 700;
        font-size: 14px;
        color: #4b3f3f;
        margin-bottom: 12px;
    }

    .staff-text span {
        font-weight: 900;
        color: var(--primary);
    }

    /* INFO */
    .booking-info {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 14px;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(235, 186, 185, 0.22);
        padding: 10px 14px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 13px;
        color: #5a3e3e;
    }

    .info-item i {
        color: var(--primary);
        font-size: 14px;
    }

    /* TOTAL */
    .booking-total {
        font-weight: 900;
        font-size: 16px;
        color: #ba1a1a;
    }

    /* BADGE STATUS */
    .badge-ui {
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        display: inline-block;
        white-space: nowrap;
    }

    .badge-wait { background: rgba(235, 186, 185, 0.45); color: #6d4848; }
    .badge-confirm { background: rgba(123, 85, 84, 0.15); color: #7b5554; }
    .badge-doing { background: rgba(96, 94, 93, 0.15); color: #605e5d; }
    .badge-done { background: rgba(40, 167, 69, 0.15); color: #198754; }
    .badge-cancel { background: rgba(186, 26, 26, 0.15); color: #ba1a1a; }

    /* ACTIONS RIGHT */
    .booking-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 12px;
    }

    .booking-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        min-width: 160px;
        align-items: stretch;
    }

    .btn-ui {
        border: none;
        border-radius: 16px;
        padding: 12px 16px;
        font-weight: 800;
        font-size: 14px;
        transition: 0.25s;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        width: 100%;
    }

    .btn-detail {
        background: var(--primary);
        color: white;
    }

    .btn-detail:hover {
        background: var(--primary-dark);
        color: white;
    }

    .btn-cancel {
        background: white;
        color: var(--primary);
        border: 1px solid rgba(123, 85, 84, 0.35);
    }

    .btn-cancel:hover {
        background: rgba(123, 85, 84, 0.08);
    }

    .btn-disabled {
        background: #ececec;
        color: #777;
        cursor: not-allowed;
        border: 1px solid #ddd;
    }

    /* RESPONSIVE */
    @media(max-width: 768px) {
        .booking-card {
            flex-direction: column;
        }

        .booking-img {
            width: 100%;
            height: 200px;
        }

        .booking-content {
            flex-direction: column;
        }

        .booking-right {
            align-items: stretch;
        }

        .booking-actions {
            width: 100%;
            min-width: unset;
        }
    }
</style>


{{-- ALERT --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

{{-- LIST BOOKINGS --}}
@forelse($bookings as $booking)

    @php
        $firstDetail = $booking->bookingDetails->first();
        $serviceImage = $firstDetail && $firstDetail->service && $firstDetail->service->image
            ? asset('uploads/services/' . $firstDetail->service->image)
            : asset('uploads/services/default.jpg');
    @endphp

    <div class="booking-card">

        {{-- IMAGE --}}
        <img src="{{ $serviceImage }}" class="booking-img" alt="service">

        {{-- CONTENT --}}
        <div class="booking-content">

            {{-- LEFT --}}
            <div style="flex:1;">

                <div class="booking-id">
                    Booking #{{ $booking->booking_id }}
                </div>

                <div class="booking-service-title">
                    Dịch vụ đã đặt
                </div>

                <ul class="service-list">
                    @foreach($booking->bookingDetails as $detail)
                        <li>
                            <i class="bi bi-scissors"></i>
                            {{ $detail->service->service_name ?? 'Không rõ' }}
                        </li>
                    @endforeach
                </ul>

                {{-- STAFF --}}
                <div class="staff-text">
                    <i class="bi bi-person-badge"></i>
                    Nhân viên:  
                    @if($booking->staff)
                        <span>{{ $booking->staff->full_name }}</span>
                    @else
                        <span style="color:#ba1a1a;">Chờ phân công nhân viên</span>
                    @endif
                </div>

                <div class="booking-info">
                    <div class="info-item">
                        <i class="bi bi-calendar-event"></i>
                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                    </div>

                    <div class="info-item">
                        <i class="bi bi-clock"></i>
                        {{ $booking->booking_time }}
                    </div>

                    <div class="info-item">
                        <i class="bi bi-geo-alt"></i>
                        {{ $booking->address }}
                    </div>
                </div>

                <div class="booking-total">
                    Tổng tiền: {{ number_format($booking->total_amount) }} VNĐ
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="booking-right">

                {{-- STATUS --}}
                <div>
                    @if($booking->status == 0)
                        <span class="badge-ui badge-wait">Chờ xác nhận</span>
                    @elseif($booking->status == 1)
                        <span class="badge-ui badge-confirm">Đã xác nhận</span>
                    @elseif($booking->status == 2)
                        <span class="badge-ui badge-doing">Đang thực hiện</span>
                    @elseif($booking->status == 3)
                        <span class="badge-ui badge-done">Hoàn thành</span>
                    @elseif($booking->status == 4)
                        <span class="badge-ui badge-cancel">Đã hủy</span>
                    @else
                        <span class="badge-ui" style="background:#eee;color:#333;">Không rõ</span>
                    @endif
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="booking-actions">

                    <a href="{{ route('customer.bookings.show', $booking->booking_id) }}"
                       class="btn-ui btn-detail">
                        Chi tiết
                    </a>

                    @if($booking->status == 0)
                        <form action="{{ route('customer.bookings.cancel', $booking->booking_id) }}"
                              method="POST">
                            @csrf
                            <button type="submit"
                                    class="btn-ui btn-cancel"
                                    onclick="return confirm('Bạn chắc chắn muốn hủy lịch?')">
                                Hủy lịch
                            </button>
                        </form>
                    @else
                        <button class="btn-ui btn-disabled" disabled>
                            Không thể hủy
                        </button>
                    @endif

                </div>

            </div>

        </div>

    </div>

@empty
    <div class="alert alert-info text-center">
        Bạn chưa đặt lịch nào.
    </div>
@endforelse

@endsection