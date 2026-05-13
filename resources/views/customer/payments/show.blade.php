@extends('customer.layout')

@section('title','Thanh toán')

@section('content')


<h3 class="fw-bold mb-4">Thanh toán dịch vụ</h3>

<div class="row g-4">

    {{-- LEFT --}}
    <div class="col-md-8">

        {{-- Thông tin đơn hàng --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Thông tin đơn hàng</h5>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Mã booking</small>
                        <div class="fw-bold">#{{ $booking->booking_id }}</div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Dịch vụ</small>
                        <div class="fw-bold">{{ count($booking->bookingDetails) }} dịch vụ</div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Ngày giờ</small>
                        <div class="fw-bold">
                            {{ $booking->booking_time }} - {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Nhân viên</small>
                        <div class="fw-bold">
                            {{ $booking->staff->full_name ?? 'Chưa phân công' }}
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <small class="text-muted">Địa chỉ</small>
                        <div class="fw-bold">{{ $booking->address }}</div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Chọn phương thức thanh toán --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Chọn phương thức thanh toán</h5>

                @if($booking->payment && $booking->payment->payment_status == 'paid')
                    <div class="alert alert-success">
                        Booking này đã thanh toán bằng <b>{{ $booking->payment->payment_method }}</b>
                        vào lúc <b>{{ $booking->payment->payment_date }}</b>.
                    </div>

                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        Quay lại
                    </a>

                @else
                    <form action="{{ route('customer.payments.pay', $booking->booking_id) }}" method="POST">
                        @csrf

                        {{-- Tiền mặt --}}
                        <div class="border rounded p-3 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" value="Cash" id="cash"
                                    {{ old('payment_method') == 'Cash' ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="cash">
                                    Tiền mặt
                                </label>
                            </div>
                            <small class="text-muted">Thanh toán khi nhân viên hoàn thành dịch vụ.</small>
                        </div>

                        {{-- Chuyển khoản --}}
                        <div class="border rounded p-3 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" value="Bank" id="bank"
                                    {{ old('payment_method') == 'Bank' ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="bank">
                                    Chuyển khoản ngân hàng
                                </label>
                            </div>

                            <div class="mt-3 bg-light p-3 rounded">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="mb-1"><b>Ngân hàng:</b> Vietcombank</p>
                                        <p class="mb-1"><b>Số TK:</b> 0123456789</p>
                                        <p class="mb-1"><b>Chủ TK:</b> BEAUTYHOME</p>
                                        <p class="mb-1"><b>Nội dung:</b> BEAUTYHOME #{{ $booking->booking_id }}</p>
                                    </div>

                                    <div class="col-md-4 text-center">
                                        <div class="border rounded bg-white p-3">
                                            <small class="text-muted">QR Code</small>
                                            <div class="mt-2">
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=BEAUTYHOME{{ $booking->booking_id }}"
                                                    class="img-fluid" alt="QR">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <small class="text-muted">
                                (Bạn chuyển khoản trước, admin sẽ xác nhận thanh toán.)
                            </small>
                        </div>

                        {{-- Ví điện tử --}}
                        <div class="border rounded p-3 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" value="Momo" id="momo"
                                    {{ old('payment_method') == 'Momo' ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="momo">
                                    Ví điện tử (Momo)
                                </label>
                            </div>
                            <small class="text-muted">Thanh toán qua ví điện tử.</small>
                        </div>

                        @error('payment_method')
                            <div class="text-danger mb-3">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="btn btn-primary fw-bold w-100">
                            Xác nhận thanh toán
                        </button>

                        <a href="{{ route('customer.payments.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Quay lại
                        </a>

                    </form>
                @endif
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h5 class="fw-bold mb-3">Chi tiết thanh toán</h5>

                @php
                    $subtotal = $booking->total_amount;
                    $discount = 0;
                    $fee = 0;
                    $total = $subtotal - $discount + $fee;
                @endphp

                @foreach($booking->bookingDetails as $detail)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $detail->service->service_name }}</span>
                        <span>{{ number_format($detail->price) }}đ</span>
                    </div>
                @endforeach

                <hr>

                <div class="d-flex justify-content-between mb-2 text-muted">
                    <span>Tạm tính</span>
                    <span>{{ number_format($subtotal) }}đ</span>
                </div>

                <div class="d-flex justify-content-between mb-2 text-success">
                    <span>Giảm giá</span>
                    <span>-{{ number_format($discount) }}đ</span>
                </div>

                <div class="d-flex justify-content-between mb-3 text-muted">
                    <span>Phí dịch vụ</span>
                    <span>{{ number_format($fee) }}đ</span>
                </div>

                <hr>

                <div class="d-flex justify-content-between fs-5 fw-bold">
                    <span>Tổng cộng</span>
                    <span class="text-primary">{{ number_format($total) }}đ</span>
                </div>

                <small class="text-muted">Giá đã bao gồm VAT</small>

            </div>
        </div>
    </div>

</div>

@endsection