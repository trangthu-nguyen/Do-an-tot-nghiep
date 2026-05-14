@extends('customer.layout')

@section('title', $service->service_name)

@section('content')

<style>
    .service-title {
        font-size: 48px;
        font-weight: 700;
        line-height: 1.15;
        color: #2f2323;
    }

    .service-price {
        font-size: 36px;
        font-weight: 800;
        color: var(--primary);
    }

    .back-button {
        position: absolute;
        top: 30px;
        left: 30px;
        background: white;
        color: var(--primary);
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 6px 20px rgba(123,85,84,0.15);
        border: 1px solid #f2e8e8;
        transition: all 0.3s ease;
        z-index: 20;
    }

    .back-button:hover {
        background: var(--primary);
        color: white;
        transform: scale(1.08);
    }

    .service-info-box {
        background: white;
        border: 1px solid #e6cfcf;
        border-radius: 22px;
        padding: 26px 28px;
        margin-bottom: 24px;
        box-shadow: 0 10px 30px rgba(123,85,84,0.06);
    }

    .service-info-box h5 {
        font-weight: 800;
        font-size: 20px;
        margin-bottom: 16px;
        color: #2f2323;
    }

    .service-info-box p {
        font-size: 15px;
        line-height: 1.9;
        margin-bottom: 0;
        color: #6f6464;
    }

    .service-meta-row {
        font-size: 15px;
        line-height: 1.9;
        color: #2f2323;
        margin-bottom: 10px;
    }

    .service-meta-row:last-child {
        margin-bottom: 0;
    }

    .service-meta-row b {
        font-weight: 800;
        color: #2f2323;
    }

    .date-picker {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        padding: 12px 0;
    }

    .date-item {
        min-width: 82px;
        text-align: center;
        padding: 14px 10px;
        cursor: pointer;
        font-weight: 700;
    }

    .date-item .dow {
        font-size: 12px;
        opacity: 0.7;
    }

    .date-item .date {
        font-size: 18px;
        font-weight: 800;
    }

    .time-slot-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 10px;
    }

    .time-slot {
        width: 92px;
        height: 50px;
        text-align: center;
        cursor: pointer;
        font-size: 14px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .payment-card {
        border: 2px solid #eadede;
        border-radius: 18px;
        padding: 18px;
        cursor: pointer;
        transition: 0.3s;
        background: white;
        height: 100%;
    }

    .payment-card:hover {
        background: rgba(123,85,84,0.05);
    }

    .payment-card.active {
        border-color: var(--primary);
        box-shadow: 0 6px 20px rgba(123,85,84,0.15);
        background: rgba(123,85,84,0.03);
    }
</style>

<div class="container py-5 position-relative">
    <a href="{{ route('customer.services.index') }}" class="back-button" title="Quay lại">←</a>

    <div class="row g-5 align-items-center">
        <div class="col-lg-6">
            <div class="service-hero">
                <img src="{{ $service->image ? asset('uploads/services/' . $service->image) : 'https://images.pexels.com/photos/3997993/pexels-photo-3997993.jpeg' }}"
                     alt="{{ $service->service_name }}"
                     style="height:520px; object-fit:cover; width:100%;">
            </div>
        </div>

        <div class="col-lg-6">
            <h1 class="service-title">{{ $service->service_name }}</h1>
            <div class="service-price mb-4">{{ number_format($service->price) }} VNĐ</div>

            <div class="service-info-box">
                <h5>Mô tả dịch vụ</h5>
                <p>{{ $service->description }}</p>
            </div>

            <div class="service-info-box">
                <h5>Thông tin dịch vụ</h5>
                <div class="service-meta-row">
                    ⏳ <b>Thời gian:</b> {{ $service->duration }} phút
                </div>

                <div class="service-meta-row">
                    ⭐ <b>Đánh giá:</b>
                    @if($avgRating)
                        {{ number_format($avgRating,1) }}/5
                    @else
                        Chưa có đánh giá
                    @endif
                </div>
            </div>

            @if(session('customer_id'))

                <button onclick="startBooking()" class="btn-booking w-100 py-3">
                    Đặt lịch ngay
                </button>

            @else

                <a href="{{ route('customer.login') }}"
                   class="btn-booking w-100 py-3 text-center text-decoration-none d-block">
                    Đăng nhập để đặt lịch
                </a>

            @endif
        </div>
    </div>
</div>

@if(session('customer_id'))

<!-- Popup chính -->
<div class="modal fade" id="bookingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Đặt lịch dịch vụ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>
</div>

<!-- Popup Thanh toán trực tuyến -->
<div class="modal fade" id="onlinePaymentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 26px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Chọn hình thức thanh toán trực tuyến</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="payment-card mb-3 active" onclick="selectOnlineMethod(this)" data-method="momo">
                    <strong>📱 Ví MoMo</strong>
                </div>

                <div class="payment-card mb-3" onclick="selectOnlineMethod(this)" data-method="vnpay">
                    <strong>🏦 VNPAY</strong>
                </div>

                <div class="payment-card" onclick="selectOnlineMethod(this)" data-method="bank">
                    <strong>🏦 Chuyển khoản ngân hàng</strong>
                </div>
            </div>

            <div class="modal-footer border-0">
                <button onclick="confirmOnlinePayment()" class="btn btn-booking w-100 py-3">
                    Xác nhận & Thanh toán
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    let selectedDate = '';
    let selectedTime = '';
    let custName = "{{ session('customer_name') ?? '' }}";
    let custPhone = "{{ session('customer_phone') ?? '' }}";
    let custAddress = "";
    let paymentMethod = "cod";

    function startBooking() {
        currentStep = 1;
        showStep();
        new bootstrap.Modal(document.getElementById('bookingModal')).show();
    }

    function showStep() {
        const title = document.getElementById('modalTitle');
        const body = document.getElementById('modalBody');

        if (currentStep === 1) {
            title.textContent = "Bước 1/3: Chọn ngày & giờ";

            body.innerHTML = `
                <input type="hidden" id="hidden_date" value="${selectedDate}">
                <input type="hidden" id="hidden_time" value="${selectedTime}">

                <h6 class="mb-3">Chọn ngày</h6>
                <div class="date-picker" id="datePicker"></div>

                <h6 class="mb-4 mt-4">Chọn khung giờ</h6>
                <div class="time-slot-grid" id="timeSlots"></div>

                <button onclick="nextStep()" class="btn btn-dark w-100 py-3 mt-4" id="btnNext" disabled>
                    Tiếp tục →
                </button>
            `;

            generateDatePicker();
            generateTimeSlots();
            checkReady();
        }

        if (currentStep === 2) {
            title.textContent = "Bước 2/3: Thông tin liên hệ";

            body.innerHTML = `
                <h6 class="mb-3">Thông tin người đặt</h6>

                <input type="text" id="cust_name" class="form-control mb-3" value="${custName}" placeholder="Họ và tên">

                <input type="tel" id="cust_phone" class="form-control mb-3" value="${custPhone}" placeholder="Số điện thoại">

                <textarea id="cust_address" class="form-control mb-4" rows="3" placeholder="Địa chỉ nhận dịch vụ">${custAddress}</textarea>

                <div class="d-flex gap-3">
                    <button onclick="prevStep()" class="btn btn-secondary flex-fill">
                        Quay lại
                    </button>

                    <button onclick="nextStep()" class="btn btn-dark flex-fill">
                        Tiếp tục →
                    </button>
                </div>
            `;
        }

        if (currentStep === 3) {
            title.textContent = "Bước 3/3: Xác nhận đặt lịch";

            body.innerHTML = `
                <h6 class="fw-bold mb-4">Xác nhận thông tin</h6>

                <div style="background:#f8f1f1; padding:24px; border-radius:20px;">
                    <p><strong>Dịch vụ:</strong> {{ $service->service_name }}</p>
                    <p><strong>Ngày:</strong> ${selectedDate ? new Date(selectedDate).toLocaleDateString('vi-VN') : ''}</p>
                    <p><strong>Giờ:</strong> ${selectedTime}</p>
                    <p><strong>Khách hàng:</strong> ${custName || 'Khách hàng'}</p>
                    <p><strong>SĐT:</strong> ${custPhone || '(Chưa nhập)'}</p>
                    <p><strong>Địa chỉ:</strong> ${custAddress || '(Chưa nhập)'}</p>
                    <p class="mb-0">
                        <strong>Tổng tiền:</strong>
                        <span class="fw-bold text-primary">{{ number_format($service->price) }} VNĐ</span>
                    </p>
                </div>

                <div class="mt-4">
                    <h6 class="fw-bold mb-3">Phương thức thanh toán</h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="payment-card ${paymentMethod === 'cod' ? 'active' : ''}" onclick="selectPayment('cod')">
                                <div class="fw-bold" style="font-size:16px;">
                                    Thanh toán khi hoàn thành
                                </div>
                                <div class="text-muted" style="font-size:14px;">
                                    Thanh toán tiền mặt cho nhân viên sau khi dịch vụ xong
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="payment-card ${paymentMethod === 'online' ? 'active' : ''}" onclick="selectPayment('online')">
                                <div class="fw-bold" style="font-size:16px;">
                                    Thanh toán trực tuyến
                                </div>
                                <div class="text-muted" style="font-size:14px;">
                                    Chuyển khoản / Ví điện tử
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button onclick="prevStep()" class="btn btn-secondary flex-fill">
                        Quay lại
                    </button>

                    <button onclick="submitBooking()" class="btn-booking flex-fill">
                        Xác nhận đặt lịch
                    </button>
                </div>
            `;
        }
    }

    function selectPayment(method) {
    paymentMethod = method;

    if (method === 'cod') {
        showStep(); // chỉ chọn phương thức, không submit ngay
        return;
    }

    if (method === 'online') {
        bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
        new bootstrap.Modal(document.getElementById('onlinePaymentModal')).show();
    }
}

    function selectOnlineMethod(el) {
        document.querySelectorAll('#onlinePaymentModal .payment-card').forEach(e => e.classList.remove('active'));
        el.classList.add('active');
    }

    function confirmOnlinePayment() {
        const selected = document.querySelector('#onlinePaymentModal .payment-card.active');

        if (selected) {
            paymentMethod = selected.getAttribute('data-method');
        }

        bootstrap.Modal.getInstance(document.getElementById('onlinePaymentModal')).hide();
        submitBooking();
    }

    function nextStep() {
        if (currentStep === 1) {
            selectedDate = document.getElementById('hidden_date').value;
            selectedTime = document.getElementById('hidden_time').value;

            if (!selectedDate || !selectedTime) {
                return alert("Vui lòng chọn ngày và giờ");
            }
        }

        if (currentStep === 2) {
            custName = document.getElementById('cust_name').value;
            custPhone = document.getElementById('cust_phone').value;
            custAddress = document.getElementById('cust_address').value;

            if (!custName || !custPhone || !custAddress) {
                return alert("Vui lòng nhập đầy đủ họ tên, số điện thoại và địa chỉ!");
            }
        }

        currentStep++;
        showStep();
    }

    function prevStep() {
        if (currentStep === 2) {
            custName = document.getElementById('cust_name').value;
            custPhone = document.getElementById('cust_phone').value;
            custAddress = document.getElementById('cust_address').value;
        }

        currentStep--;
        showStep();
    }

    function submitBooking() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('customer.bookings.store') }}";

        form.innerHTML = `
            @csrf
            <input type="hidden" name="service_id" value="{{ $service->service_id }}">
            <input type="hidden" name="booking_date" value="${selectedDate}">
            <input type="hidden" name="booking_time" value="${selectedTime}">
            <input type="hidden" name="address" value="${custAddress}">
            <input type="hidden" name="customer_name" value="${custName}">
            <input type="hidden" name="customer_phone" value="${custPhone}">
            <input type="hidden" name="payment_method" value="${paymentMethod}">
        `;

        document.body.appendChild(form);
        form.submit();
    }

    function generateDatePicker() {
        const container = document.getElementById('datePicker');
        container.innerHTML = '';

        const today = new Date();
        const dayNames = ["CN", "T2", "T3", "T4", "T5", "T6", "T7"];

        for (let i = 0; i < 7; i++) {
            let d = new Date(today);
            d.setDate(today.getDate() + i);

            let dateStr = d.getFullYear() + '-' +
                String(d.getMonth() + 1).padStart(2, '0') + '-' +
                String(d.getDate()).padStart(2, '0');

            let div = document.createElement('div');
            div.className = 'date-item';
            div.innerHTML = `<div class="dow">${dayNames[d.getDay()]}</div><div class="date">${d.getDate()}</div>`;

            div.onclick = () => {
                document.querySelectorAll('.date-item').forEach(el => el.classList.remove('active'));

                div.classList.add('active');

                document.getElementById('hidden_date').value = dateStr;
                selectedDate = dateStr;

                checkReady();
            };

            container.appendChild(div);
        }
    }

    function generateTimeSlots() {
        const container = document.getElementById('timeSlots');
        container.innerHTML = '';

        const slots = ["08:00", "09:00", "10:00", "11:00", "13:00", "14:00", "15:00", "16:00", "18:00", "19:00"];

        slots.forEach(time => {
            let div = document.createElement('div');
            div.className = 'time-slot';
            div.textContent = time;

            div.onclick = () => {
                document.querySelectorAll('.time-slot').forEach(el => el.classList.remove('active'));

                div.classList.add('active');

                document.getElementById('hidden_time').value = time;
                selectedTime = time;

                checkReady();
            };

            container.appendChild(div);
        });
    }

    function checkReady() {
        const date = document.getElementById('hidden_date').value;
        const time = document.getElementById('hidden_time').value;
        const btn = document.getElementById('btnNext');

        if (btn) {
            btn.disabled = !(date && time);
        }
    }
</script>

@endif

@endsection