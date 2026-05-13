@extends('customer.layout')

@section('title','Đánh giá dịch vụ')

@section('content')

<style>
    :root{
        --primary:#7b5554;
        --primary-dark:#6d4848;
        --primary-light:#ebbab9;

        --background:#faf9f9;
        --outline:#d4c2c2;

        --text:#1b1c1c;
        --text-muted:#504443;
    }

    .page-title{
        font-family:'Noto Serif',serif;
        font-weight:700;
        color:var(--primary);
    }

    .card-ui{
        background:rgba(255,255,255,0.92);
        backdrop-filter:blur(10px);
        border-radius:22px;
        border:1px solid rgba(212,194,194,0.7);
        box-shadow:0 18px 45px rgba(123,85,84,0.08);
    }

    .service-name{
        color:var(--primary);
        font-weight:700;
    }

    .btn-primary-ui{
        background:var(--primary);
        border:none;
        border-radius:14px;
        padding:10px 16px;
        font-weight:600;
        color:white;
        transition:0.25s;
    }

    .btn-primary-ui:hover{
        background:var(--primary-dark);
    }

    .btn-outline-ui{
        border:1px solid var(--outline);
        border-radius:14px;
        padding:10px 16px;
        font-weight:600;
        color:var(--text-muted);
        background:transparent;
        transition:0.25s;
        text-decoration:none;
    }

    .btn-outline-ui:hover{
        background:rgba(235,186,185,0.18);
        color:var(--primary);
    }

    .form-control{
        border-radius:14px;
        border:1px solid var(--outline);
        padding:12px 14px;
        background:rgba(250,249,249,0.9);
    }

    .form-control:focus{
        border-color:var(--primary);
        box-shadow:0 0 0 4px rgba(123,85,84,0.15);
    }

    .badge-ui{
        background:rgba(235,186,185,0.45);
        color:var(--primary);
        border-radius:999px;
        padding:8px 14px;
        font-weight:700;
    }
</style>

<h3 class="page-title mb-3">Đánh giá dịch vụ</h3>

<div class="card card-ui p-4">

    <h5 class="mb-3 service-name">
        Dịch vụ:
        <b>
            @foreach($booking->bookingDetails as $detail)
                {{ $detail->service->service_name ?? 'Không rõ' }}@if(!$loop->last), @endif
            @endforeach
        </b>
    </h5>

    <form action="{{ route('customer.feedback.store') }}" method="POST">
        @csrf

        <input type="hidden" name="booking_id" value="{{ $booking->booking_id }}">
        <input type="hidden" name="rating" id="ratingInput" value="0">

        <div class="mb-3">
            <label class="fw-bold">Chọn số sao:</label>

            <div class="d-flex align-items-center gap-3 mt-2">
                <div id="starRating" style="font-size: 34px; cursor: pointer;">
                    <span class="star text-secondary" data-value="1">★</span>
                    <span class="star text-secondary" data-value="2">★</span>
                    <span class="star text-secondary" data-value="3">★</span>
                    <span class="star text-secondary" data-value="4">★</span>
                    <span class="star text-secondary" data-value="5">★</span>
                </div>

                <span class="badge-ui" id="ratingText">Chưa chọn</span>
            </div>

            @error('rating')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="fw-bold">Nhận xét:</label>
            <textarea name="comment" class="form-control" rows="4"
                      placeholder="Nhập nhận xét của bạn...">{{ old('comment') }}</textarea>

            @error('comment')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn-primary-ui">
                Gửi đánh giá
            </button>

            <a href="{{ route('customer.bookings.index') }}" class="btn-outline-ui">
                Quay lại
            </a>
        </div>

    </form>

</div>

<script>
    const stars = document.querySelectorAll(".star");
    const ratingInput = document.getElementById("ratingInput");
    const ratingText = document.getElementById("ratingText");

    function updateStars(rating) {
        stars.forEach(star => {
            let value = star.getAttribute("data-value");

            if (value <= rating) {
                star.classList.remove("text-secondary");
                star.classList.add("text-warning");
            } else {
                star.classList.remove("text-warning");
                star.classList.add("text-secondary");
            }
        });

        if (rating == 0) {
            ratingText.innerText = "Chưa chọn";
        } else {
            ratingText.innerText = rating + " sao";
        }
    }

    stars.forEach(star => {
        star.addEventListener("click", function() {
            let rating = this.getAttribute("data-value");
            ratingInput.value = rating;
            updateStars(rating);
        });

        star.addEventListener("mouseover", function() {
            let rating = this.getAttribute("data-value");
            updateStars(rating);
        });

        star.addEventListener("mouseout", function() {
            updateStars(ratingInput.value);
        });
    });
</script>

@endsection