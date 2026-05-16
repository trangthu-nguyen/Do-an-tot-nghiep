@extends('admin.layout')

@section('title','Thông báo')

@section('content')

<style>
    :root{
        --primary:#7b5554;
        --primary-soft:#f7eeee;
        --outline:#eadede;
        --text:#2f2323;
        --muted:#938787;
    }

    .notify-header{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:20px;
        margin-bottom:24px;
        flex-wrap:wrap;
    }

    .notify-title{
        font-family:'Noto Serif',serif;
        font-size:36px;
        font-weight:900;
        color:var(--primary);
        margin-bottom:4px;
    }

    .notify-sub{
        color:var(--muted);
        font-weight:600;
    }

    .mark-all-btn{
        border:none;
        border-radius:16px;
        padding:12px 18px;
        background:white;
        border:1px solid var(--outline);
        color:var(--primary);
        font-weight:800;
        transition:.2s;
    }

    .mark-all-btn:hover{
        background:var(--primary);
        color:white;
    }

    .filter-bar{
        display:flex;
        gap:12px;
        flex-wrap:wrap;
        margin-bottom:24px;
    }

    .filter-btn{
        text-decoration:none;
        padding:10px 18px;
        border-radius:999px;
        background:#f4f0f0;
        color:#7b6d6d;
        font-weight:800;
        font-size:13px;
        transition:.2s;
    }

    .filter-btn.active{
        background:var(--primary);
        color:white;
    }

    .notification-list{
        display:flex;
        flex-direction:column;
        gap:16px;
    }

    .notification-card{
        background:white;
        border-radius:24px;
        border:1px solid var(--outline);
        padding:22px;
        display:flex;
        align-items:flex-start;
        gap:18px;
        transition:.25s;
        box-shadow:0 8px 25px rgba(123,85,84,.05);
    }

    .notification-card:hover{
        transform:translateY(-2px);
        box-shadow:0 14px 35px rgba(123,85,84,.10);
    }

    .notification-card.unread{
        border:2px solid rgba(123,85,84,.35);
        background:#fffdfd;
    }

    .notify-avatar{
        width:58px;
        height:58px;
        border-radius:18px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:24px;
        flex-shrink:0;
    }

    .avatar-booking{
        background:#f7eaea;
        color:#7b5554;
    }

    .avatar-system{
        background:#ede9ff;
        color:#7c4dff;
    }

    .avatar-payment{
        background:#e7f7ec;
        color:#16a34a;
    }

    .notify-body{
        flex:1;
    }

    .notify-top{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:20px;
        margin-bottom:8px;
    }

    .notify-name{
        font-size:17px;
        font-weight:900;
        color:var(--text);
        margin-bottom:5px;
    }

    .notify-desc{
        color:#6e6464;
        line-height:1.8;
        font-size:14px;
    }

    .notify-time{
        color:#a09292;
        font-size:12px;
        font-weight:700;
        white-space:nowrap;
    }

    .notify-footer{
        display:flex;
        align-items:center;
        gap:10px;
        flex-wrap:wrap;
        margin-top:14px;
    }

    .badge-type{
        padding:6px 12px;
        border-radius:999px;
        font-size:11px;
        font-weight:900;
    }

    .badge-booking{
        background:#f7eaea;
        color:#7b5554;
    }

    .badge-system{
        background:#ede9ff;
        color:#7c4dff;
    }

    .badge-payment{
        background:#e7f7ec;
        color:#16a34a;
    }

    .badge-status{
        padding:6px 12px;
        border-radius:999px;
        font-size:11px;
        font-weight:900;
    }

    .status-unread{
        background:#ffe5e5;
        color:#d62828;
    }

    .status-read{
        background:#e7f7ec;
        color:#15803d;
    }

    .read-btn{
        margin-left:auto;
        border:none;
        background:var(--primary);
        color:white;
        border-radius:14px;
        padding:10px 14px;
        font-size:12px;
        font-weight:800;
        transition:.2s;
    }

    .read-btn:hover{
        background:#684847;
    }

    .load-more{
        margin-top:28px;
        text-align:center;
    }

    .load-btn{
        border:none;
        background:#f4eeee;
        color:#6f6161;
        border-radius:16px;
        padding:13px 28px;
        font-weight:800;
    }

    .empty-box{
        background:white;
        border-radius:28px;
        border:1px solid var(--outline);
        padding:80px 20px;
        text-align:center;
        color:#988c8c;
    }
</style>

<div class="notify-header">

    <div>

        <div class="notify-sub">
            
            Luôn cập nhật những hoạt động mới nhất trong trình quản lý của bạn.
        </div>
    </div>

    <form action="{{ route('admin.notifications.readAll') }}" method="POST">
        @csrf

        <button class="mark-all-btn">
            <i class="bi bi-check2-all"></i>
            Đánh dấu tất cả là đã đọc
        </button>
    </form>

</div>

{{-- FILTER --}}
<div class="filter-bar">

    <a href="{{ route('admin.notifications.index') }}"
       class="filter-btn {{ request('type') == null ? 'active' : '' }}">
        Tất cả
    </a>

    <a href="{{ route('admin.notifications.index',['read'=>0]) }}"
       class="filter-btn {{ request('read') == '0' ? 'active' : '' }}">
        Chưa đọc
    </a>

    <a href="{{ route('admin.notifications.index',['type'=>'system']) }}"
       class="filter-btn {{ request('type') == 'system' ? 'active' : '' }}">
        Hệ thống
    </a>

    <a href="{{ route('admin.notifications.index',['type'=>'booking']) }}"
       class="filter-btn {{ request('type') == 'booking' ? 'active' : '' }}">
        Đặt lịch
    </a>

</div>

@if($notifications->count())

<div class="notification-list">

    @foreach($notifications as $noti)

        @php

            $avatarClass = 'avatar-system';
            $badgeClass = 'badge-system';
            $icon = 'bi-bell';

            if(str_contains(strtolower($noti->title), 'booking') ||
               str_contains(strtolower($noti->content), 'lịch'))
            {
                $avatarClass = 'avatar-booking';
                $badgeClass = 'badge-booking';
                $icon = 'bi-calendar-check';
            }

            if(str_contains(strtolower($noti->title), 'payment') ||
               str_contains(strtolower($noti->content), 'thanh toán'))
            {
                $avatarClass = 'avatar-payment';
                $badgeClass = 'badge-payment';
                $icon = 'bi-credit-card';
            }

        @endphp

        <div class="notification-card {{ $noti->is_read == 0 ? 'unread' : '' }}">

            <div class="notify-avatar {{ $avatarClass }}">
                <i class="bi {{ $icon }}"></i>
            </div>

            <div class="notify-body">

                <div class="notify-top">

                    <div>
                        <div class="notify-name">
                            {{ $noti->title }}
                        </div>

                        <div class="notify-desc">
                            {{ $noti->content }}
                        </div>
                    </div>

                    <div class="notify-time">
                        {{ \Carbon\Carbon::parse($noti->created_at)->diffForHumans() }}
                    </div>

                </div>

                <div class="notify-footer">

                    

                    @if($noti->is_read == 0)

                        <span class="badge-status status-unread">
                            Chưa đọc
                        </span>

                        <form action="{{ route('admin.notifications.read', $noti->notification_id) }}"
                              method="POST"
                              class="ms-auto">
                            @csrf

                            <button class="read-btn">
                                Xem
                            </button>
                        </form>

                    @else

                        <span class="badge-status status-read">
                            Đã đọc
                        </span>

                    @endif

                </div>

            </div>

        </div>

    @endforeach

</div>

<div class="load-more">
    <button class="load-btn">
        Tải thông báo cũ hơn
    </button>
</div>

@else

<div class="empty-box">

    <i class="bi bi-bell-slash fs-1"></i>

    <div class="fw-bold mt-3">
        Chưa có thông báo nào
    </div>

</div>

@endif

@endsection