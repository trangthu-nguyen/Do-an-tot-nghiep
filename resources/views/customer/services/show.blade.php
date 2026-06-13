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
        overflow-y: hidden;
        padding: 12px 4px 16px;
        scroll-behavior: smooth;
        white-space: nowrap;
    }

    .date-picker::-webkit-scrollbar {
        height: 8px;
    }

    .date-picker::-webkit-scrollbar-track {
        background: #f6eeee;
        border-radius: 999px;
    }

    .date-picker::-webkit-scrollbar-thumb {
        background: #d9b5b5;
        border-radius: 999px;
    }

    .date-item {
        min-width: 86px;
        text-align: center;
        padding: 14px 10px;
        cursor: pointer;
        font-weight: 700;
        border: 1px solid #eadede;
        border-radius: 18px;
        background: #fff;
        color: #7b5554;
        flex: 0 0 auto;
        transition: 0.25s;
    }

    .date-item:hover {
        background: #fff7f7;
        transform: translateY(-2px);
    }

    .date-item.active {
        background: #7b5554;
        color: #fff;
        border-color: #7b5554;
        box-shadow: 0 8px 20px rgba(123,85,84,0.18);
    }

    .date-item .dow {
        font-size: 12px;
        opacity: 0.8;
    }

    .date-item .date {
        font-size: 18px;
        font-weight: 800;
    }

    .date-item .month {
        font-size: 11px;
        opacity: 0.75;
        margin-top: 2px;
    }

    .time-slot-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 10px;
    }

    .time-slot {
        width: 96px;
        min-height: 58px;
        text-align: center;
        cursor: pointer;
        font-size: 14px;
        font-weight: 700;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 1px solid #eadede;
        border-radius: 16px;
        background: white;
        color: #7b5554;
        transition: 0.25s;
        position: relative;
    }

    .time-slot:hover {
        background: #fff7f7;
    }

    .time-slot.active {
        background: #7b5554;
        color: white;
        border-color: #7b5554;
    }

    .time-slot.disabled-slot {
        opacity: 0.4;
        cursor: not-allowed;
        background: #f1f1f1;
        color: #999;
        border-color: #e5e5e5;
    }

    .time-slot.disabled-slot:hover {
        background: #f1f1f1;
        transform: none;
    }

    .peak-badge {
        margin-top: 4px;
        background: #fee2e2;
        color: #b91c1c;
        font-size: 10px;
        font-weight: 900;
        padding: 2px 7px;
        border-radius: 999px;
        line-height: 1.4;
    }

    .time-slot.active .peak-badge {
        background: white;
        color: #b91c1c;
    }

    .booking-warning {
        background: #fff4d6;
        color: #8a5a00;
        border: 1px solid #f3d58a;
        padding: 12px 14px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 14px;
    }

    .fee-note {
        background: #fff7f7;
        color: #7b5554;
        border: 1px solid #eadede;
        padding: 12px 14px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 14px;
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

    .price-line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        color: #2f2323;
    }

    .price-line.extra {
        color: #b91c1c;
        font-weight: 700;
    }

    .price-total {
        display: flex;
        justify-content: space-between;
        border-top: 1px dashed #d9b5b5;
        padding-top: 12px;
        margin-top: 12px;
        font-size: 18px;
        font-weight: 900;
        color: #7b5554;
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

    let selectedDistrictCode = "";
    let selectedDistrictName = "";
    let selectedWardName = "";
    let addressDetail = "";

    let paymentMethod = "cod";

    const MIN_BOOKING_HOURS = 2;
   const SERVICE_PRICE = Number("{{ $service->price }}");
    const PEAK_HOUR_FEE = 50000;
    const DISTRICT_FEE = 50000;

    function formatCurrency(value) {
        return Number(value).toLocaleString('vi-VN') + ' VNĐ';
    }

    function isPeakHour(time) {
        return (time >= '10:00' && time <= '13:00') ||
               (time >= '18:00' && time <= '21:00');
    }

    function isDistrictFeeArea(districtName) {
    return districtName &&
        (
            districtName.trim().startsWith('Huyện') ||
            districtName.trim().startsWith('Thị xã')
        );
}

    function getPeakHourFee() {
        return isPeakHour(selectedTime) ? PEAK_HOUR_FEE : 0;
    }

    function getDistrictFee() {
        return isDistrictFeeArea(selectedDistrictName) ? DISTRICT_FEE : 0;
    }

    function getTotalAmount() {
        return SERVICE_PRICE + getPeakHourFee() + getDistrictFee();
    }

    function startBooking() {
        currentStep = 1;
        selectedDate = '';
        selectedTime = '';
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

                <div class="booking-warning">
                    Vui lòng đặt lịch trước thời gian thực hiện ít nhất ${MIN_BOOKING_HOURS} giờ.
                </div>

                <div class="fee-note">
                    🔥 Giờ cao điểm từ 10:00 - 13:00 và 18:00 - 21:00 phụ thu thêm 50.000đ.
                </div>

                <h6 class="mb-3">Chọn ngày</h6>
                <div class="date-picker" id="datePicker"></div>
                <div class="text-muted small mt-1">
                    Có thể kéo ngang để xem thêm các ngày tiếp theo.
                </div>

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

                <input type="text"
                       id="cust_name"
                       class="form-control mb-3"
                       value="${custName}"
                       placeholder="Họ và tên">

                <input type="tel"
                       id="cust_phone"
                       class="form-control mb-3"
                       value="${custPhone}"
                       placeholder="Số điện thoại">

                <div class="mb-3">
                    <label class="form-label fw-bold">Tỉnh/Thành phố</label>
                    <input type="text" class="form-control" value="Hà Nội" readonly>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-bold">Quận/Huyện</label>
                    <select id="district" class="form-control" onchange="loadWardsByDistrict()">
                        <option value="">-- Chọn quận/huyện --</option>
                    </select>
                </div>

                <div id="districtFeeNotice" class="fee-note" style="display:none;">
                    🚗 Khu vực Huyện/Thị xã ngoại thành phụ thu thêm 50.000đ phí di chuyển.
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label fw-bold">Phường/Xã</label>
                    <select id="ward" class="form-control">
                        <option value="">-- Chọn phường/xã --</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Số nhà / Đường / Ngõ</label>
                    <input type="text"
                           id="address_detail"
                           class="form-control"
                           value="${addressDetail}"
                           placeholder="VD: Số 12, ngõ 18 Nguyễn Phúc Lai">
                </div>

                <div class="d-flex gap-3">
                    <button onclick="prevStep()" class="btn btn-secondary flex-fill">
                        Quay lại
                    </button>

                    <button onclick="nextStep()" class="btn btn-dark flex-fill">
                        Tiếp tục →
                    </button>
                </div>
            `;

            loadHanoiDistricts();
        }

        if (currentStep === 3) {
            title.textContent = "Bước 3/3: Xác nhận đặt lịch";

            const peakFee = getPeakHourFee();
            const districtFee = getDistrictFee();
            const totalAmount = getTotalAmount();

            body.innerHTML = `
                <h6 class="fw-bold mb-4">Xác nhận thông tin</h6>

                <div style="background:#f8f1f1; padding:24px; border-radius:20px;">
                    <p><strong>Dịch vụ:</strong> {{ $service->service_name }}</p>
                    <p><strong>Ngày:</strong> ${selectedDate ? new Date(selectedDate).toLocaleDateString('vi-VN') : ''}</p>
                    <p><strong>Giờ:</strong> ${selectedTime}</p>
                    <p><strong>Khách hàng:</strong> ${custName || 'Khách hàng'}</p>
                    <p><strong>SĐT:</strong> ${custPhone || '(Chưa nhập)'}</p>
                    <p><strong>Địa chỉ:</strong> ${custAddress || '(Chưa nhập)'}</p>

                    <hr>

                    <div class="price-line">
                        <span>Giá dịch vụ:</span>
                        <strong>${formatCurrency(SERVICE_PRICE)}</strong>
                    </div>

                    ${peakFee > 0 ? `
                        <div class="price-line extra">
                            <span>Phụ thu giờ cao điểm:</span>
                            <strong>+${formatCurrency(peakFee)}</strong>
                        </div>
                    ` : ''}

                    ${districtFee > 0 ? `
                        <div class="price-line extra">
                            <span>Phụ thu di chuyển:</span>
                            <strong>+${formatCurrency(districtFee)}</strong>
                        </div>
                    ` : ''}

                    <div class="price-total">
                        <span>Tổng tiền:</span>
                        <span>${formatCurrency(totalAmount)}</span>
                    </div>
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
                            <div class="payment-card ${paymentMethod === 'online' || paymentMethod === 'momo' || paymentMethod === 'vnpay' || paymentMethod === 'bank' ? 'active' : ''}" onclick="selectPayment('online')">
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
            showStep();
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

            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');
            const addressInput = document.getElementById('address_detail');

            selectedDistrictCode = districtSelect.value;
            selectedDistrictName = districtSelect.options[districtSelect.selectedIndex]?.text || '';
            selectedWardName = wardSelect.value;
            addressDetail = addressInput.value.trim();

            if (!custName || !custPhone || !selectedDistrictCode || !selectedWardName || !addressDetail) {
                return alert("Vui lòng nhập đầy đủ họ tên, số điện thoại và địa chỉ!");
            }

            custAddress = `${addressDetail}, ${selectedWardName}, ${selectedDistrictName}, Hà Nội`;
        }

        currentStep++;
        showStep();
    }

    function prevStep() {
        if (currentStep === 2) {
            custName = document.getElementById('cust_name').value;
            custPhone = document.getElementById('cust_phone').value;

            const addressInput = document.getElementById('address_detail');
            if (addressInput) {
                addressDetail = addressInput.value.trim();
            }
        }

        currentStep--;
        showStep();
    }

    function submitBooking() {
        if (
            paymentMethod === 'momo' ||
            paymentMethod === 'vnpay' ||
            paymentMethod === 'bank'
        ) {
            const form = document.createElement('form');

            form.method = 'POST';
            form.action = "{{ route('payment.init') }}";

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
            return;
        }

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
            <input type="hidden" name="payment_method" value="cod">
        `;

        document.body.appendChild(form);
        form.submit();
    }

    function generateDatePicker() {
        const container = document.getElementById('datePicker');
        container.innerHTML = '';

        const today = new Date();
        const dayNames = ["CN", "T2", "T3", "T4", "T5", "T6", "T7"];

        for (let i = 0; i < 60; i++) {
            let d = new Date(today);
            d.setDate(today.getDate() + i);

            let dateStr = d.getFullYear() + '-' +
                String(d.getMonth() + 1).padStart(2, '0') + '-' +
                String(d.getDate()).padStart(2, '0');

            let div = document.createElement('div');
            div.className = 'date-item';
            div.innerHTML = `
                <div class="dow">${dayNames[d.getDay()]}</div>
                <div class="date">${String(d.getDate()).padStart(2, '0')}</div>
                <div class="month">Tháng ${d.getMonth() + 1}</div>
            `;

            div.onclick = () => {
                document.querySelectorAll('.date-item')
                    .forEach(el => el.classList.remove('active'));

                div.classList.add('active');

                document.getElementById('hidden_date').value = dateStr;

                selectedDate = dateStr;
                selectedTime = '';
                document.getElementById('hidden_time').value = '';

                generateTimeSlots();
                checkReady();
            };

            container.appendChild(div);
        }
    }

    function generateTimeSlots() {
        const container = document.getElementById('timeSlots');
        container.innerHTML = '';

        const slots = [
            "08:00", "09:00", "10:00", "11:00",
            "13:00", "14:00", "15:00", "16:00",
            "18:00", "19:00"
        ];

        const now = new Date();
        const minBookingTime = new Date(now.getTime() + MIN_BOOKING_HOURS * 60 * 60 * 1000);

        slots.forEach(time => {
            let div = document.createElement('div');
            div.className = 'time-slot';

            div.innerHTML = `
                <span>${time}</span>
                ${isPeakHour(time) ? '<span class="peak-badge">+50k</span>' : ''}
            `;

            let isBlocked = false;

            if (selectedDate) {
                const [year, month, day] = selectedDate.split('-').map(Number);
                const [hour, minute] = time.split(':').map(Number);

                const slotTime = new Date(year, month - 1, day, hour, minute, 0);

                if (slotTime < minBookingTime) {
                    isBlocked = true;
                }
            }

            if (isBlocked) {
                div.classList.add('disabled-slot');
                div.title = `Vui lòng đặt lịch trước thời gian thực hiện ít nhất ${MIN_BOOKING_HOURS} giờ`;

                div.onclick = () => {
                    alert(`Vui lòng đặt lịch trước thời gian thực hiện ít nhất ${MIN_BOOKING_HOURS} giờ!`);
                };
            } else {
                div.onclick = () => {
                    document.querySelectorAll('.time-slot')
                        .forEach(el => el.classList.remove('active'));

                    div.classList.add('active');

                    document.getElementById('hidden_time').value = time;

                    selectedTime = time;

                    checkReady();
                };
            }

            container.appendChild(div);
        });
    }

    async function loadHanoiDistricts() {
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');

        if (!districtSelect) return;

        districtSelect.innerHTML = '<option value="">Đang tải quận/huyện...</option>';

        try {
            const response = await fetch('https://provinces.open-api.vn/api/v1/p/01?depth=2');
            const data = await response.json();

            districtSelect.innerHTML = '<option value="">-- Chọn quận/huyện --</option>';

            data.districts.forEach(district => {
                const selected = selectedDistrictCode == district.code ? 'selected' : '';

                districtSelect.innerHTML += `
                    <option value="${district.code}" ${selected}>
                        ${district.name}
                    </option>
                `;
            });

            if (selectedDistrictCode) {
                await loadWardsByDistrict();
            } else if (wardSelect) {
                wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
            }

            updateDistrictFeeNotice();
        } catch (error) {
            districtSelect.innerHTML = '<option value="">Không tải được dữ liệu</option>';
            alert('Không tải được danh sách quận/huyện. Vui lòng kiểm tra kết nối mạng.');
        }
    }

    async function loadWardsByDistrict() {
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');

        if (!districtSelect || !wardSelect) return;

        const districtCode = districtSelect.value;

        selectedDistrictCode = districtCode;
        selectedDistrictName = districtSelect.options[districtSelect.selectedIndex]?.text || '';

        updateDistrictFeeNotice();

        wardSelect.innerHTML = '<option value="">Đang tải phường/xã...</option>';

        if (!districtCode) {
            wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
            return;
        }

        try {
            const response = await fetch(`https://provinces.open-api.vn/api/v1/d/${districtCode}?depth=2`);
            const data = await response.json();

            wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';

            data.wards.forEach(ward => {
                const selected = selectedWardName == ward.name ? 'selected' : '';

                wardSelect.innerHTML += `
                    <option value="${ward.name}" ${selected}>
                        ${ward.name}
                    </option>
                `;
            });
        } catch (error) {
            wardSelect.innerHTML = '<option value="">Không tải được dữ liệu</option>';
            alert('Không tải được danh sách phường/xã.');
        }
    }

    function updateDistrictFeeNotice() {
        const notice = document.getElementById('districtFeeNotice');

        if (!notice) return;

        if (isDistrictFeeArea(selectedDistrictName)) {
            notice.style.display = 'block';
        } else {
            notice.style.display = 'none';
        }
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
{{-- Đánh giá khách hàng --}}
<div class="container pb-5">

    <div class="service-info-box">

        <div class="row align-items-center mb-5">

            <div class="col-md-6">

                <h3 style="
                    font-weight:800;
                    color:#5f3d3d;
                    line-height:1.2;
                ">
                    Đánh giá từ khách hàng
                </h3>

                <div class="text-muted mt-2">
                    Dựa trên {{ $feedbacks->count() }} lượt đặt lịch thành công
                </div>

            </div>

            <div class="col-md-6 text-md-end mt-4 mt-md-0">

                <div style="
                    display:inline-flex;
                    align-items:center;
                    gap:18px;
                    background:#fff6f6;
                    padding:18px 24px;
                    border-radius:20px;
                    border:1px solid #f1dddd;
                ">

                    <div style="
                        font-size:42px;
                        font-weight:800;
                        color:#7b5554;
                        line-height:1;
                    ">
                        {{ $avgRating ? number_format($avgRating,1) : '0.0' }}
                    </div>

                    <div>

                        <div style="
                            color:#8d6766;
                            font-size:18px;
                            letter-spacing:2px;
                        ">
                            ★★★★★
                        </div>

                        <div class="small text-muted">
                            Tuyệt vời
                        </div>

                    </div>

                </div>

            </div>

        </div>


        @forelse($feedbacks as $feedback)

            <div class="mb-4 pb-4" style="border-bottom:1px solid #f2e7e7;">

                <div class="d-flex">

                    <div style="
                        width:50px;
                        height:50px;
                        border-radius:50%;
                        background:#7b5554;
                        color:white;
                        font-weight:700;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:18px;
                        margin-right:16px;
                    ">
                        {{ strtoupper(substr($feedback->customer->full_name,0,1)) }}
                    </div>

                    <div class="flex-grow-1">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div style="
                                    font-weight:700;
                                    color:#2f2323;
                                ">
                                    {{ $feedback->customer->full_name }}
                                </div>

                                <div class="small text-muted">
                                    {{ \Carbon\Carbon::parse($feedback->created_at)->diffForHumans() }}
                                </div>

                            </div>

                            <div style="
                                color:#8d6766;
                                font-size:18px;
                            ">

                                @for($i=1;$i<=5;$i++)
                                    @if($i <= $feedback->rating)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor

                            </div>

                        </div>

                        <div style="
                            margin-top:14px;
                            color:#7d7272;
                            line-height:1.9;
                            font-style:italic;
                        ">
                            "{{ $feedback->comment }}"
                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div style="
                background:#fff8f8;
                border:1px solid #f1dddd;
                border-radius:20px;
                padding:30px;
                text-align:center;
                color:#7b5554;
                font-weight:700;
            ">
                Chưa có đánh giá nào cho dịch vụ này.
            </div>

        @endforelse

    </div>

</div>

@endif

@endsection