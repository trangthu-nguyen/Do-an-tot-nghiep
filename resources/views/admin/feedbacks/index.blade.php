@extends('admin.layout')

@section('title', 'Quản lý đánh giá')

@section('content')

<h3 class="fw-bold mb-4" style="color:#7b5554;">⭐ Danh sách đánh giá</h3>

<div class="card shadow-sm border-0" style="border-radius:18px;">
    <div class="card-body p-3">

        @if($feedbacks->count() == 0)
            <div class="alert text-center"
                 style="background:#efeded; color:#504443; border-radius:14px;">
                Chưa có đánh giá nào.
            </div>
        @else
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#7b5554; color:white;">
                    <tr>
                        <th width="60" style="border-top-left-radius:14px;">ID</th>
                        <th>Khách hàng</th>
                        <th>Dịch vụ</th>
                        <th width="170">Đánh giá</th>
                        <th>Nhận xét</th>
                        <th width="170" style="border-top-right-radius:14px;">Ngày tạo</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($feedbacks as $fb)
                        <tr>
                            <td class="fw-semibold">{{ $fb->feedback_id }}</td>

                            <td>{{ $fb->customer->full_name ?? 'Không rõ' }}</td>

                            <td class="text-muted">
                                @if($fb->booking && $fb->booking->bookingDetails)
                                    @foreach($fb->booking->bookingDetails as $detail)
                                        {{ $detail->service->service_name ?? 'Không rõ' }}@if(!$loop->last), @endif
                                    @endforeach
                                @else
                                    Không rõ
                                @endif
                            </td>

                            <td>
                                @for($i=1; $i<=5; $i++)
                                    @if($i <= $fb->rating)
                                        <span style="color:#ebbab9; font-size: 18px;">★</span>
                                    @else
                                        <span style="color:#d4c2c2; font-size: 18px;">★</span>
                                    @endif
                                @endfor
                                <span class="text-muted">({{ $fb->rating }}/5)</span>
                            </td>

                            <td class="text-muted">
                                {{ $fb->comment ?? 'Không có nhận xét' }}
                            </td>

                            <td class="text-muted">
                                {{ $fb->created_at }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        @endif

    </div>
</div>

@endsection