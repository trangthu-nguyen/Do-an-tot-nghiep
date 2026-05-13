@extends('customer.layout')

@section('title', 'Thông báo')

@section('content')

<style>
    :root {
        --primary: #7b5554;
        --primary-dark: #684847;
        --accent: #ebbab9;

        --bg: #faf7f7;
        --card: #ffffff;
        --text: #2f2323;
        --muted: #7d7272;

        --outline: rgba(235, 186, 185, 0.55);
        --shadow: rgba(123, 85, 84, 0.10);
    }

    body {
        background: var(--bg);
    }

    .page-title {
        font-family: 'Noto Serif', serif;
        font-weight: 900;
        font-size: 30px;
        color: var(--text);
    }

    .btn-read-all {
        background: rgba(235, 186, 185, 0.35);
        border: 1px solid rgba(123, 85, 84, 0.25);
        color: var(--primary);
        padding: 10px 16px;
        border-radius: 999px;
        font-weight: 800;
        font-size: 14px;
        transition: 0.25s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-read-all:hover {
        background: rgba(235, 186, 185, 0.55);
        color: var(--primary-dark);
    }

    .btn-read-all-dark {
        background: var(--primary);
        border: none;
        color: white;
        padding: 10px 18px;
        border-radius: 999px;
        font-weight: 800;
        font-size: 14px;
        transition: 0.25s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-read-all-dark:hover {
        background: var(--primary-dark);
    }

    .notify-tabs {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .notify-tab {
        padding: 10px 16px;
        border-radius: 999px;
        border: 1px solid rgba(235, 186, 185, 0.55);
        background: white;
        font-weight: 800;
        font-size: 13px;
        color: var(--primary);
        cursor: pointer;
        transition: 0.2s;
    }

    .notify-tab.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .notify-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 22px;
        border: 1px solid var(--outline);
        box-shadow: 0 18px 45px var(--shadow);
        padding: 18px 20px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        transition: 0.25s;
        margin-bottom: 14px;
        position: relative;
    }

    .notify-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 22px 55px rgba(123, 85, 84, 0.14);
    }

    .notify-dot {
        width: 10px;
        height: 10px;
        border-radius: 999px;
        background: #d81b60;
        position: absolute;
        top: 18px;
        right: 18px;
    }

    .notify-left {
        display: flex;
        gap: 14px;
        flex: 1;
    }

    .notify-icon {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(235, 186, 185, 0.40);
        color: var(--primary);
        font-size: 20px;
        flex-shrink: 0;
    }

    .notify-title {
        font-weight: 900;
        font-size: 16px;
        color: var(--text);
        margin-bottom: 4px;
    }

    .notify-content {
        color: var(--muted);
        font-size: 14px;
        line-height: 1.7;
        margin-bottom: 8px;
        max-width: 700px;
    }

    .notify-time {
        font-size: 12px;
        font-weight: 700;
        color: #9a8d8d;
    }

    .notify-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        min-width: 150px;
        align-items: flex-end;
    }

    .btn-mark {
        background: var(--primary);
        color: white;
        border: none;
        padding: 10px 14px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 13px;
        transition: 0.25s;
        width: 150px;
    }

    .btn-mark:hover {
        background: var(--primary-dark);
    }

    .btn-mark-disabled {
        background: #f2f0f0;
        border: 1px solid #e2dddd;
        color: #888;
        padding: 10px 14px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 13px;
        width: 150px;
        cursor: not-allowed;
    }

    .empty-box {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(235, 186, 185, 0.55);
        border-radius: 22px;
        padding: 40px;
        text-align: center;
        color: #7d7272;
        font-weight: 700;
        box-shadow: 0 18px 45px rgba(123, 85, 84, 0.08);
    }

    @media(max-width: 768px) {
        .notify-card {
            flex-direction: column;
        }

        .notify-actions {
            width: 100%;
            align-items: stretch;
        }

        .btn-mark,
        .btn-mark-disabled {
            width: 100%;
        }
    }
</style>

@php
    $unreadCount = $notifications->where('is_read', 0)->count();
@endphp

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h3 class="page-title mb-1">Thông báo</h3>
        <div class="text-muted fw-semibold">
            Cập nhật mới nhất về lịch đặt và thanh toán
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        <a href="{{ route('customer.notifications.index') }}" class="btn-read-all">
            <i class="bi bi-arrow-clockwise"></i>
            Làm mới
        </a>

        @if($unreadCount > 0)
            <form action="{{ route('customer.notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="btn-read-all-dark">
                    <i class="bi bi-check2-all"></i>
                    Đánh dấu tất cả đã đọc
                </button>
            </form>
        @endif

    </div>
</div>

<div class="notify-tabs">
    <div class="notify-tab active">
        Tất cả
    </div>

    @if($unreadCount > 0)
        <div class="notify-tab">
            Chưa đọc: {{ $unreadCount }}
        </div>
    @endif
</div>

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

@if($notifications->count() == 0)

    <div class="empty-box">
        Bạn chưa có thông báo nào.
    </div>

@else

    @foreach($notifications as $notify)

        <div class="notify-card">

            @if($notify->is_read == 0)
                <div class="notify-dot"></div>
            @endif

            <div class="notify-left">

                <div class="notify-icon">
                    <i class="bi bi-bell-fill"></i>
                </div>

                <div style="flex:1;">
                    <div class="notify-title">
                        {{ $notify->title }}
                    </div>

                    <div class="notify-content">
                        {{ $notify->content }}
                    </div>

                    <div class="notify-time">
                        {{ \Carbon\Carbon::parse($notify->created_at)->diffForHumans() }}
                    </div>
                </div>

            </div>

            <div class="notify-actions">

                @if($notify->is_read == 0)
                    <form action="{{ route('customer.notifications.read', $notify->notification_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-mark">
                            Đánh dấu đã đọc
                        </button>
                    </form>
                @else
                    <button class="btn-mark-disabled" disabled>
                        Đã đọc
                    </button>
                @endif

            </div>

        </div>

    @endforeach

@endif

@endsection