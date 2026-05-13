@extends('admin.layout')

@section('title', 'Thông báo')

@section('content')

<h3 class="fw-bold mb-4" style="color:#7b5554;">🔔 Danh sách thông báo</h3>

<div class="card shadow-sm border-0" style="border-radius:18px;">
    <div class="card-body p-3">

        @if($notifications->count() == 0)
            <div class="alert text-center"
                 style="background:#efeded; color:#504443; border-radius:14px;">
                Chưa có thông báo nào.
            </div>
        @else
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#7b5554; color:white;">
                    <tr>
                        <th width="60" style="border-top-left-radius:14px;">ID</th>
                        <th>Tiêu đề</th>
                        <th>Nội dung</th>
                        <th width="140">Trạng thái</th>
                        <th width="180">Ngày tạo</th>
                        <th width="170" style="border-top-right-radius:14px;">Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($notifications as $noti)
                        <tr>
                            <td class="fw-semibold">{{ $noti->notification_id }}</td>

                            <td class="fw-semibold" style="color:#603d3d;">
                                {{ $noti->title }}
                            </td>

                            <td class="text-muted">
                                {{ $noti->content }}
                            </td>

                            <td>
                                @if($noti->is_read == 0)
                                    <span class="badge bg-warning text-dark">Chưa đọc</span>
                                @else
                                    <span class="badge bg-success">Đã đọc</span>
                                @endif
                            </td>

                            <td class="text-muted">
                                {{ $noti->created_at }}
                            </td>

                            <td>
                                @if($noti->is_read == 0)
                                    <form action="{{ route('admin.notifications.read', $noti->notification_id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm px-3"
                                                style="background:#7b5554; color:white; border-radius:12px;">
                                            Đánh dấu đã đọc
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm px-3"
                                            style="background:#efeded; color:#504443; border-radius:12px;"
                                            disabled>
                                        Đã đọc
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        @endif

    </div>
</div>

@endsection