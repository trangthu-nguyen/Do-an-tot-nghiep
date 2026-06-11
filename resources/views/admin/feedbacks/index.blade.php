@extends('admin.layout')

@section('title','Quản lý Đánh giá & Phản hồi')

@section('content')

<style>
    :root{--primary:#7b5554;--dark:#2f2323;--muted:#8a7e7e;--border:#eadede;--soft:#faf7f7}
    .page-sub{color:var(--muted);font-weight:600}
    .filter-bar,.list-card,.detail-card{background:white;border:1px solid var(--border);border-radius:26px;box-shadow:0 12px 32px rgba(123,85,84,.06)}
    .filter-bar{padding:16px;margin:22px 0}
    .filter-btn{display:inline-flex;align-items:center;gap:7px;border-radius:999px;padding:10px 16px;background:#f5f2f2;color:#6d5f5f;text-decoration:none;font-weight:900;font-size:13px}
    .filter-btn.active{background:var(--primary);color:white}
    .main-grid{display:grid;grid-template-columns:1fr 360px;gap:22px}
    .list-card{min-height:650px;overflow:hidden}
    .table th{font-size:11px;text-transform:uppercase;color:#9b8f8f;padding:16px;border-bottom:1px solid #f3eeee}
    .table td{padding:16px;border-bottom:1px solid #f7eeee;vertical-align:middle}
    .avatar{width:44px;height:44px;border-radius:50%;object-fit:cover;border:3px solid #f1dddd}
    .name{font-weight:900;color:var(--dark)}
    .small-muted{font-size:12px;color:var(--muted)}
    .stars{color:#ffb648;font-size:13px;white-space:nowrap}
    .content-short{max-width:230px;color:#6f6464;font-size:13px}
    .badge-status{border-radius:999px;padding:6px 10px;font-size:11px;font-weight:900;display:inline-block}
    .st-0{background:#fff4d6;color:#a16207}
    .st-1{background:#dcfce7;color:#15803d}
    .st-hidden{background:#e5e7eb;color:#374151}
    .detail-card{padding:26px;position:sticky;top:24px}
    .detail-title{font-family:'Noto Serif',serif;color:var(--primary);font-size:26px;font-weight:900;margin-bottom:18px}
    .detail-avatar{width:76px;height:76px;border-radius:50%;object-fit:cover;border:4px solid #f1dddd}
    .detail-name{font-size:20px;font-weight:900;color:var(--dark);margin-top:12px}
    .info-block{margin-top:22px}
    .info-label{font-size:11px;text-transform:uppercase;color:#9b8f8f;font-weight:900;margin-bottom:6px}
    .info-value{color:#4f4343;line-height:1.8;font-weight:700}
    .review-box{background:var(--soft);border-radius:18px;padding:18px;color:#6f6464;line-height:1.9;font-style:italic;min-height:120px}
    .action-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:22px}
    .btn-action{border:none;border-radius:14px;padding:12px;font-weight:900}
    .btn-approve{background:var(--primary);color:white}
    .btn-hide{background:#ffefef;color:#d11a2a}
    .btn-show{background:#dcfce7;color:#15803d}
    .feedback-hidden-row{opacity:0.55}
    @media(max-width:1100px){.main-grid{grid-template-columns:1fr}.detail-card{position:static}}
</style>

@php
    function feedbackText($fb) {
        return $fb->content
            ?? $fb->comment
            ?? $fb->message
            ?? $fb->feedback_content
            ?? 'Chưa có nội dung phản hồi.';
    }

    function feedbackDisplayText($fb) {
        if (($fb->is_hidden ?? 0) == 1) {
            return 'Đã ẩn';
        }

        return (int)($fb->status ?? 0) === 1 ? 'Đã duyệt' : 'Chưa duyệt';
    }

    function feedbackDisplayClass($fb) {
        if (($fb->is_hidden ?? 0) == 1) {
            return 'st-hidden';
        }

        return (int)($fb->status ?? 0) === 1 ? 'st-1' : 'st-0';
    }
@endphp

<div class="page-sub">Theo dõi và kiểm duyệt ý kiến khách hàng về chất lượng dịch vụ.</div>

@if(session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<div class="filter-bar">
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.feedbacks.index') }}"
           class="filter-btn {{ request('status') === null && request('hidden') === null ? 'active' : '' }}">
            Tất cả {{ $totalCount }}
        </a>

        <a href="{{ route('admin.feedbacks.index', ['status' => 0]) }}"
           class="filter-btn {{ request('status') === '0' ? 'active' : '' }}">
            Chưa duyệt {{ $pendingCount }}
        </a>

        <a href="{{ route('admin.feedbacks.index', ['status' => 1]) }}"
           class="filter-btn {{ request('status') === '1' ? 'active' : '' }}">
            Đã duyệt {{ $approvedCount }}
        </a>

        <a href="{{ route('admin.feedbacks.index', ['hidden' => 1]) }}"
           class="filter-btn {{ request('hidden') === '1' ? 'active' : '' }}">
            Đã ẩn {{ $hiddenCount }}
        </a>
    </div>
</div>

<div class="main-grid">
    <div class="list-card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Khách hàng</th>
                        <th>Dịch vụ</th>
                        <th>Đánh giá</th>
                        <th>Nội dung phản hồi</th>
                        <th>Trạng thái</th>
                        <th>Ngày</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($feedbacks as $fb)
                        @php
                            $customer = $fb->customer;
                            $service = optional(optional(optional($fb->booking)->bookingDetails->first())->service);
                            $text = feedbackText($fb);
                            $rating = $fb->rating ?? $fb->star ?? 5;

                            $feedbackUrl = route('admin.feedbacks.index', array_filter([
                                'status' => request('status'),
                                'hidden' => request('hidden'),
                                'feedback_id' => $fb->feedback_id,
                            ], fn($value) => $value !== null && $value !== ''));

                            $rowClass = '';
                            if ($selectedFeedback && $selectedFeedback->feedback_id == $fb->feedback_id) {
                                $rowClass .= 'active ';
                            }
                            if (($fb->is_hidden ?? 0) == 1) {
                                $rowClass .= 'feedback-hidden-row';
                            }
                        @endphp

                        <tr onclick="window.location.href='{{ $feedbackUrl }}'"
                            class="{{ $rowClass }}"
                            style="cursor:pointer;">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $customer->avatar_url ?? asset('uploads/avatar/default-avatar.png') }}" class="avatar">
                                    <div>
                                        <div class="name">{{ $customer->full_name ?? 'Khách hàng' }}</div>
                                        <div class="small-muted">{{ $customer->phone ?? '' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="name" style="font-size:13px;">
                                    {{ $service->service_name ?? 'Dịch vụ' }}
                                </div>
                            </td>

                            <td>
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                </div>
                            </td>

                            <td>
                                <div class="content-short">
                                    {{ \Illuminate\Support\Str::limit($text, 60) }}
                                </div>
                            </td>

                            <td>
                                <span class="badge-status {{ feedbackDisplayClass($fb) }}">
                                    {{ feedbackDisplayText($fb) }}
                                </span>
                            </td>

                            <td class="small-muted">
                                {{ $fb->created_at ? \Carbon\Carbon::parse($fb->created_at)->format('d/m/Y') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Chưa có đánh giá nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        @if($selectedFeedback)
            @php
                $customer = $selectedFeedback->customer;
                $service = optional(optional(optional($selectedFeedback->booking)->bookingDetails->first())->service);
                $selectedText = feedbackText($selectedFeedback);
                $selectedRating = $selectedFeedback->rating ?? $selectedFeedback->star ?? 5;
            @endphp

            <div class="detail-card text-center">
                <div class="detail-title">Chi tiết phản hồi</div>

                <img src="{{ $customer->avatar_url ?? asset('uploads/avatar/default-avatar.png') }}" class="detail-avatar">

                <div class="detail-name">{{ $customer->full_name ?? 'Khách hàng' }}</div>
                <div class="small-muted">
    {{ $customer->rank_label ?? 'Khách hàng thường' }}
</div>

                <div class="stars mt-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi {{ $i <= $selectedRating ? 'bi-star-fill' : 'bi-star' }}"></i>
                    @endfor
                </div>

                <div class="info-block text-start">
                    <div class="info-label">Trạng thái</div>
                    <div>
                        <span class="badge-status {{ feedbackDisplayClass($selectedFeedback) }}">
                            {{ feedbackDisplayText($selectedFeedback) }}
                        </span>
                    </div>
                </div>

                <div class="info-block text-start">
                    <div class="info-label">Dịch vụ đã sử dụng</div>
                    <div class="info-value">{{ $service->service_name ?? 'Dịch vụ' }}</div>
                </div>

                <div class="info-block text-start">
                    <div class="info-label">Thời gian đánh giá</div>
                    <div class="info-value">
                        {{ $selectedFeedback->created_at ? \Carbon\Carbon::parse($selectedFeedback->created_at)->format('d/m/Y - H:i') : 'N/A' }}
                    </div>
                </div>

                <div class="info-block text-start">
                    <div class="info-label">Nội dung phản hồi</div>
                    <div class="review-box">
                        “{{ $selectedText }}”
                    </div>
                </div>

                <div class="action-row">
                    @if(($selectedFeedback->is_hidden ?? 0) == 1)
                        <form action="{{ route('admin.feedbacks.showAgain', $selectedFeedback->feedback_id) }}"
                              method="POST"
                              onsubmit="return confirm('Bạn muốn hiển thị lại đánh giá này?')">
                            @csrf
                            <button class="btn-action btn-show w-100">
                                Hiện lại đánh giá
                            </button>
                        </form>
                    @else
                        @if((int)$selectedFeedback->status !== 1)
                            <form action="{{ route('admin.feedbacks.approve', $selectedFeedback->feedback_id) }}" method="POST">
                                @csrf
                                <button class="btn-action btn-approve w-100">
                                    Duyệt đánh giá
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.feedbacks.hide', $selectedFeedback->feedback_id) }}"
                              method="POST"
                              onsubmit="return confirm('Bạn chắc chắn muốn ẩn đánh giá này?')">
                            @csrf
                            <button class="btn-action btn-hide w-100">
                                Ẩn đánh giá
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @else
            <div class="detail-card text-center text-muted">
                Chọn một phản hồi để xem chi tiết.
            </div>
        @endif
    </div>
</div>

@endsection