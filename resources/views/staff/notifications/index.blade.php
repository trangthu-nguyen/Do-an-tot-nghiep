@extends('staff.layout')

@section('title','Thông báo')

@section('page-title','')

@section('content')

<style>
    .noti-title{
        font-size:34px;
        font-weight:900;
        font-family:'Noto Serif', serif;
        color:#2f2323;
        margin-bottom:8px;
    }

    .noti-subtitle{
        color:#7d7272;
        font-weight:600;
        margin-bottom:26px;
    }

    .noti-list{
        display:flex;
        flex-direction:column;
        gap:16px;
    }

    .noti-card{
        background:white;
        border:1px solid #eadede;
        border-radius:22px;
        padding:20px;
        box-shadow:0 12px 35px rgba(123,85,84,0.07);
        display:flex;
        justify-content:space-between;
        gap:18px;
        align-items:flex-start;
    }

    .noti-card.unread{
        background:rgba(235,186,185,0.18);
        border-color:rgba(123,85,84,0.18);
    }

    .noti-left{
        display:flex;
        gap:14px;
        align-items:flex-start;
    }

    .noti-icon{
        width:46px;
        height:46px;
        border-radius:16px;
        background:#ebbab9;
        color:#7b5554;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:20px;
        flex-shrink:0;
    }

    .noti-name{
        font-weight:900;
        color:#2f2323;
        font-size:17px;
        margin-bottom:6px;
    }

    .noti-content{
        color:#6b5c5c;
        font-weight:600;
        line-height:1.7;
        margin-bottom:8px;
    }

    .noti-date{
        color:#9b8f8f;
        font-size:12px;
        font-weight:700;
    }

    .noti-status{
        border-radius:999px;
        padding:7px 12px;
        font-size:12px;
        font-weight:900;
        white-space:nowrap;
    }

    .status-unread{
        background:#7b5554;
        color:white;
    }

    .status-read{
        background:#f1eeee;
        color:#7d7272;
    }

    .btn-read{
        border:none;
        background:white;
        color:#7b5554;
        border:1px solid #eadede;
        border-radius:999px;
        padding:8px 14px;
        font-weight:900;
        font-size:12px;
        margin-top:10px;
    }

    .empty-box{
        background:white;
        border:1px dashed #ebbab9;
        border-radius:24px;
        padding:38px;
        text-align:center;
        color:#7d7272;
        font-weight:800;
    }
</style>

<div class="noti-title">Thông báo nhân viên</div>
<div class="noti-subtitle">
    Các thông báo liên quan đến lịch đặt, phân công nhân viên và trạng thái lịch hẹn.
</div>

@if($notifications->count() == 0)

    <div class="empty-box">
        <i class="bi bi-bell-slash" style="font-size:42px;color:#ebbab9;"></i>
        <div class="mt-3">Bạn chưa có thông báo nào.</div>
    </div>

@else

    <div class="noti-list">

        @foreach($notifications as $noti)

            <div class="noti-card {{ $noti->is_read == 0 ? 'unread' : '' }}">

                <div class="noti-left">

                    <div class="noti-icon">
                        @if(str_contains(strtolower($noti->title), 'hủy'))
                            <i class="bi bi-x-circle"></i>
                        @elseif(str_contains(strtolower($noti->title), 'phân công'))
                            <i class="bi bi-person-check"></i>
                        @elseif(str_contains(strtolower($noti->title), 'lịch'))
                            <i class="bi bi-calendar-check"></i>
                        @else
                            <i class="bi bi-bell"></i>
                        @endif
                    </div>

                    <div>
                        <div class="noti-name">
                            {{ $noti->title }}
                        </div>

                        <div class="noti-content">
                            {{ $noti->content }}
                        </div>

                        <div class="noti-date">
                            {{ \Carbon\Carbon::parse($noti->created_at)->format('d/m/Y H:i') }}
                        </div>

                        @if($noti->is_read == 0)
                            <form action="{{ route('staff.notifications.read', $noti->notification_id) }}"
                                  method="POST">
                                @csrf
                                <button class="btn-read">
                                    Đánh dấu đã đọc
                                </button>
                            </form>
                        @endif
                    </div>

                </div>

                <div>
                    @if($noti->is_read == 0)
                        <span class="noti-status status-unread">Chưa đọc</span>
                    @else
                        <span class="noti-status status-read">Đã đọc</span>
                    @endif
                </div>

            </div>

        @endforeach

    </div>

@endif

@endsection