@extends('admin.layout')

@section('title', 'Thanh toán')

@section('content')

<style>
    :root {
        --primary: #7b5554;
        --primary-dark: #684847;
        --outline: #d4c2c2;
        --text: #2f2323;
        --text-muted: #766c6c;
        --surface: #faf9f9;
    }

    .page-title {
        font-family: 'Noto Serif', serif;
        font-weight: 800;
        font-size: 28px;
        color: var(--text);
    }

    .table-custom {
        background: white;
        border-radius: 22px;
        overflow: hidden;
        border: 1px solid var(--outline);
        box-shadow: 0 12px 40px rgba(123, 85, 84, 0.08);
    }

    .table-custom thead {
        background: rgba(235, 186, 185, 0.18);
        color: var(--primary);
        font-weight: 800;
    }

    .table-custom th {
        border-bottom: 1px solid var(--outline) !important;
        padding: 14px 16px;
        font-size: 14px;
    }

    .table-custom td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(212, 194, 194, 0.35);
        font-size: 14px;
        color: #3f3333;
    }

    .table-custom tbody tr:hover {
        background: rgba(235, 186, 185, 0.12);
    }

    .money-text {
        font-weight: 900;
        color: #ba1a1a;
    }

    .method-text {
        font-weight: 700;
        color: #4b3f3f;
        letter-spacing: 0.2px;
        font-size: 13px;
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

    .badge-paid {
        background: rgba(40, 167, 69, 0.15);
        color: #198754;
    }

    .badge-pending {
        background: rgba(255, 193, 7, 0.20);
        color: #a16a00;
    }

    .badge-unpaid {
        background: rgba(186, 26, 26, 0.15);
        color: #ba1a1a;
    }

    .date-text {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .btn-save {
        background: var(--primary);
        color: white;
        font-weight: 800;
        border-radius: 14px;
        padding: 10px 18px;
        border: none;
        transition: 0.25s;
    }

    .btn-save:hover {
        background: var(--primary-dark);
    }

    .select-ui {
        border-radius: 14px !important;
        border: 1px solid var(--outline) !important;
        font-weight: 700;
        font-size: 13px;
        padding: 10px 12px;
    }

    .select-ui:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 4px rgba(123,85,84,0.15) !important;
    }
</style>

<h3 class="page-title mb-4">Danh sách thanh toán</h3>

<div class="table-custom">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-custom">
            <thead>
                <tr>
                    <th width="70">ID</th>
                    <th width="110">Booking</th>
                    <th width="150">Số tiền</th>
                    <th width="180">Phương thức</th>
                    <th width="150">Trạng thái</th>
                    <th width="200">Ngày thanh toán</th>
                    <th width="280">Cập nhật</th>
                </tr>
            </thead>

            <tbody>
                @forelse($payments as $payment)

                    @php
                        $method = strtoupper(trim($payment->payment_method ?? ''));

                        if ($method == 'COD') {
                            $methodText = 'Thanh toán khi hoàn thành';
                        } elseif ($method == 'VNPAY') {
                            $methodText = 'VNPAY';
                        } elseif ($method == 'MOMO') {
                            $methodText = 'MOMO';
                        } elseif ($method == 'BANK') {
                            $methodText = 'BANK';
                        } elseif ($method == 'ONLINE') {
                            $methodText = 'ONLINE';
                        } else {
                            $methodText = $method ?: '---';
                        }
                    @endphp

                    <tr>
                        <td class="fw-bold" style="color: var(--primary);">
                            #{{ $payment->payment_id }}
                        </td>

                        <td class="fw-bold">
                            {{ $payment->booking_id }}
                        </td>

                        <td class="money-text">
                            {{ number_format($payment->amount) }} VNĐ
                        </td>

                        <!-- METHOD -->
                        <td class="method-text">
                            {{ $methodText }}
                        </td>

                        <!-- STATUS BADGE -->
                        <td>
                            @if($payment->payment_status == 'paid' || $payment->payment_status == 'đã thanh toán')
                                <span class="badge-ui badge-paid">Đã thanh toán</span>
                            @elseif($payment->payment_status == 'pending')
                                <span class="badge-ui badge-pending">Chờ xử lý</span>
                            @else
                                <span class="badge-ui badge-unpaid">Chưa thanh toán</span>
                            @endif
                        </td>

                        <td class="date-text">
                            {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') : '—' }}
                        </td>

                        <td>
                            <form action="{{ route('admin.payments.updateStatus', $payment->payment_id) }}" method="POST">
                                @csrf
                                <div class="d-flex gap-2">
                                    <select name="payment_status" class="form-select select-ui">
                                        <option value="pending" {{ $payment->payment_status == 'pending' ? 'selected' : '' }}>
                                            Chờ xử lý
                                        </option>
                                        <option value="paid" {{ $payment->payment_status == 'paid' ? 'selected' : '' }}>
                                            Đã thanh toán
                                        </option>
                                        <option value="unpaid" {{ $payment->payment_status == 'unpaid' ? 'selected' : '' }}>
                                            Chưa thanh toán
                                        </option>
                                    </select>

                                    <button class="btn-save">
                                        Lưu
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5" style="color: var(--text-muted); font-weight:700;">
                            Chưa có thanh toán nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection