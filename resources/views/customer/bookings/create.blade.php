@extends('customer.layout')

@section('title', 'Đặt lịch dịch vụ')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>

<style>
    :root {
        --primary: #7b5554;
        --primary-dark: #6d4848;
        --primary-light: #ebbab9;

        --background: #faf9f9;
        --surface: #ffffff;
        --surface-container: #efeded;

        --text: #1b1c1c;
        --text-muted: #504443;

        --outline: #d4c2c2;
    }

    body {
        background: var(--background);
        font-family: 'Manrope', sans-serif;
        color: var(--text);
    }

    .booking-header {
        font-family: 'Noto Serif', serif;
        font-size: 40px;
        font-weight: 700;
        color: var(--primary);
    }

    .service-main-card {
        border-radius: 28px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(212, 194, 194, 0.7);
        box-shadow: 0 18px 45px rgba(123, 85, 84, 0.10);
    }

    .service-main-img {
        height: 380px;
        object-fit: cover;
    }

    .price-text {
        font-weight: 700;
        color: var(--primary);
    }

    /* Date picker */
    .date-picker {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        padding: 10px 0;
    }

    .date-item {
        min-width: 78px;
        text-align: center;
        padding: 12px 8px;
        border-radius: 18px;
        border: 1px solid var(--outline);
        cursor: pointer;
        transition: 0.25s;
        background: rgba(250, 249, 249, 0.9);
        font-weight: 600;
        color: var(--text-muted);
    }

    .date-item.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        box-shadow: 0 10px 25px rgba(123, 85, 84, 0.18);
    }

    /* Time slots */
    .time-slot {
        border: 1px solid var(--outline);
        border-radius: 16px;
        padding: 14px;
        text-align: center;
        cursor: pointer;
        background: rgba(250, 249, 249, 0.9);
        transition: 0.25s;
        font-weight: 600;
        color: var(--text-muted);
    }

    .time-slot:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .time-slot.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        box-shadow: 0 10px 25px rgba(123, 85, 84, 0.18);
    }

    /* Buttons */
    .btn-primary-ui {
        background: var(--primary);
        border: none;
        border-radius: 16px;
        padding: 14px 18px;
        font-weight: 600;
        font-size: 16px;
        transition: 0.2s;
        color: white;
    }

    .btn-primary-ui:hover {
        background: var(--primary-dark);
    }

    .btn-outline-ui {
        border: 1px solid var(--primary);
        color: var(--primary);
        border-radius: 16px;
        padding: 14px 18px;
        font-weight: 600;
        transition: 0.2s;
        background: transparent;
    }

    .btn-outline-ui:hover {
        background: rgba(235, 186, 185, 0.2);
    }

    /* Modal */
    .modal-content {
        border-radius: 24px;
        border: 1px solid rgba(212, 194, 194, 0.7);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }

    .modal-title {
        font-family: 'Noto Serif', serif;
        color: var(--primary);
        font-weight: 700;
    }

    .form-control {
        border-radius: 14px;
        padding: 12px 14px;
        border: 1px solid var(--outline);
        background: rgba(250, 249, 249, 0.9);
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(123, 85, 84, 0.15);
    }

    .confirm-box {
        background: rgba(235, 186, 185, 0.18);
        border: 1px solid rgba(212, 194, 194, 0.7);
        padding: 20px;
        border-radius: 18px;
    }
</style>

<div class="container py-5" style="max-width: 1280px;">
    <h1 class="booking-header">Đặt lịch dịch vụ</h1>
    <p class="text-muted">Bắt đầu quy trình đặt lịch</p>

    <div class="row g-5 mt-2">
        <div class="col-lg-8">
            <div class="service-main-card">
                <img src="{{ $service->image ?? 'https://images.pexels.com/photos/3997993/pexels-photo-3997993.jpeg' }}"
                     class="service-main-img w-100" alt="">

                <div class="p-4">
                    <h3 class="fw-bold">{{ $service->service_name }}</h3>
                    <p class="text-muted">{{ $service->description }}</p>
                    <h4 class="price-text">{{ number_format($service->price) }} VNĐ</h4>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <button onclick="startBooking()" class="btn-primary-ui w-100">
                Bắt đầu đặt lịch →
            </button>
        </div>
    </div>
</div>

{{-- FORM ẨN ĐỂ SUBMIT --}}
<form id="bookingForm" action="{{ route('customer.bookings.store') }}" method="POST">
    @csrf
    <input type="hidden" name="service_id" value="{{ $service->service_id }}">
    <input type="hidden" name="booking_date" id="hidden_date">
    <input type="hidden" name="booking_time" id="hidden_time">
    <input type="hidden" name="customer_name" id="hidden_name">
    <input type="hidden" name="customer_phone" id="hidden_phone">
    <input type="hidden" name="address" id="hidden_address">
    <input type="hidden" name="payment_method" value="cod">
</form>

<!-- MULTI-STEP POPUP -->
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

<script>
    let currentStep = 1;
    let selectedDate = '';
    let selectedTime = '';

    function startBooking() {
        currentStep = 1;
        showStep();
        new bootstrap.Modal(document.getElementById('bookingModal')).show();
    }

    function showStep() {
        const modalBody = document.getElementById('modalBody');
        const title = document.getElementById('modalTitle');

        if (currentStep === 1) {
            title.innerHTML = `Bước 1/3: Chọn ngày & giờ`;
            modalBody.innerHTML = `
                <h6 class="mb-3 fw-bold">Chọn ngày</h6>
                <div class="date-picker" id="datePicker"></div>

                <h6 class="mb-3 mt-4 fw-bold">Chọn khung giờ</h6>
                <div class="row g-3" id="timeSlots"></div>

                <button onclick="nextStep()" class="btn-primary-ui w-100 mt-4" id="btnNext" disabled>
                    Tiếp tục →
                </button>
            `;
            generateDatePicker();
            generateTimeSlots();
        }

        else if (currentStep === 2) {
            title.innerHTML = `Bước 2/3: Thông tin liên hệ`;
            modalBody.innerHTML = `
                <h6 class="mb-3 fw-bold">Thông tin người đặt</h6>

                <div class="mb-3">
                    <label class="fw-semibold mb-2">Họ và tên</label>
                    <input type="text" id="cust_name" class="form-control" value="{{ session('customer_name') ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold mb-2">Số điện thoại</label>
                    <input type="tel" id="cust_phone" class="form-control" placeholder="Nhập số điện thoại" required>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold mb-2">Địa chỉ nhận dịch vụ</label>
                    <textarea id="cust_address" class="form-control" rows="3" placeholder="Nhập địa chỉ chi tiết" required></textarea>
                </div>

                <div class="d-flex gap-3">
                    <button onclick="prevStep()" class="btn-outline-ui flex-fill">Quay lại</button>
                    <button onclick="nextStep()" class="btn-primary-ui flex-fill">Tiếp tục →</button>
                </div>
            `;
        }

        else if (currentStep === 3) {
            title.innerHTML = `Bước 3/3: Xác nhận đặt lịch`;

            const name = document.getElementById('cust_name').value || 'Khách hàng';
            const phone = document.getElementById('cust_phone').value || '(Chưa nhập)';
            const address = document.getElementById('cust_address').value || '(Chưa nhập)';

            modalBody.innerHTML = `
                <h6 class="fw-bold mb-4">Thông tin đặt lịch</h6>

                <div class="confirm-box">
                    <p><strong>Dịch vụ:</strong> {{ $service->service_name }}</p>
                    <p><strong>Ngày:</strong> ${selectedDate ? new Date(selectedDate).toLocaleDateString('vi-VN') : ''}</p>
                    <p><strong>Giờ:</strong> ${selectedTime}</p>
                    <p><strong>Khách hàng:</strong> ${name}</p>
                    <p><strong>SĐT:</strong> ${phone}</p>
                    <p><strong>Địa chỉ:</strong> ${address}</p>
                    <p><strong>Tổng tiền:</strong>
                        <span class="fw-bold" style="color:#7b5554;">
                            {{ number_format($service->price) }} VNĐ
                        </span>
                    </p>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button onclick="prevStep()" class="btn-outline-ui flex-fill">Quay lại</button>
                    <button onclick="submitBooking()" class="btn-primary-ui flex-fill">Xác nhận đặt lịch</button>
                </div>
            `;
        }
    }

    function nextStep() {
        if (currentStep === 1) {
            selectedDate = document.getElementById('hidden_date').value;
            selectedTime = document.getElementById('hidden_time').value;

            if (!selectedDate || !selectedTime) {
                alert("Vui lòng chọn ngày và giờ");
                return;
            }
        }

        if (currentStep === 2) {
            let name = document.getElementById('cust_name').value;
            let phone = document.getElementById('cust_phone').value;
            let address = document.getElementById('cust_address').value;

            if (!name || !phone || !address) {
                alert("Vui lòng nhập đầy đủ thông tin!");
                return;
            }

            document.getElementById('hidden_name').value = name;
            document.getElementById('hidden_phone').value = phone;
            document.getElementById('hidden_address').value = address;
        }

        currentStep++;
        showStep();
    }

    function prevStep() {
        currentStep--;
        showStep();
    }

    function submitBooking() {
        document.getElementById('bookingForm').submit();
    }

    function generateDatePicker() {
        const container = document.getElementById('datePicker');
        container.innerHTML = '';
        const today = new Date();
        const dayNames = ["CN","T2","T3","T4","T5","T6","T7"];

        for (let i = 0; i < 7; i++) {
            let d = new Date();
            d.setDate(today.getDate() + i);
            let dateStr = d.toISOString().split('T')[0];

            let div = document.createElement('div');
            div.className = 'date-item';
            div.innerHTML = `<div>${dayNames[d.getDay()]}</div><div style="font-size:18px;">${d.getDate()}</div>`;

            div.onclick = () => {
                document.querySelectorAll('.date-item').forEach(el => el.classList.remove('active'));
                div.classList.add('active');
                document.getElementById('hidden_date').value = dateStr;
                checkReady();
            };

            container.appendChild(div);
        }
    }

    function generateTimeSlots() {
        const container = document.getElementById('timeSlots');
        container.innerHTML = '';
        const slots = ["08:00","09:00","10:00","11:00","13:00","14:00","15:00","16:00","18:00","19:00"];

        slots.forEach(time => {
            let col = document.createElement('div');
            col.className = 'col-4';

            let div = document.createElement('div');
            div.className = 'time-slot';
            div.textContent = time;

            div.onclick = () => {
                document.querySelectorAll('.time-slot').forEach(el => el.classList.remove('active'));
                div.classList.add('active');
                document.getElementById('hidden_time').value = time;
                checkReady();
            };

            col.appendChild(div);
            container.appendChild(col);
        });
    }

    function checkReady() {
        const date = document.getElementById('hidden_date').value;
        const time = document.getElementById('hidden_time').value;
        const btn = document.getElementById('btnNext');
        if (btn) btn.disabled = !(date && time);
    }
</script>

@endsection