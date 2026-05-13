@extends('staff.layout')

@section('title','Đăng ký lịch làm')

@section('page-title','')

@section('content')

<style>
    :root{
        --primary:#7b5554;
        --primary-dark:#684847;
        --accent:#ebbab9;
        --text:#2f2323;
        --muted:#7d7272;
        --border:#eadede;
    }

    .schedule-title{
        font-size:30px;
        font-weight:900;
        font-family:'Noto Serif', serif;
        color:var(--text);
        margin-bottom:24px;
    }

    .schedule-layout{
        display:grid;
        grid-template-columns:1fr 360px;
        gap:28px;
        align-items:start;
    }

    .card-ui{
        background:white;
        border:1px solid #f1e7e7;
        border-radius:24px;
        padding:24px;
        box-shadow:0 14px 38px rgba(123,85,84,0.07);
    }

    .section-title{
        font-size:20px;
        font-weight:900;
        font-family:'Noto Serif', serif;
        color:var(--text);
        margin-bottom:18px;
    }

    .calendar-grid{
        display:grid;
        grid-template-columns:repeat(7, 1fr);
        gap:10px;
    }

    .day-name{
        text-align:center;
        color:var(--muted);
        font-size:12px;
        font-weight:900;
        padding-bottom:8px;
    }

    .day-box{
        min-height:76px;
        border-radius:14px;
        border:1px solid #f1e7e7;
        background:#fffafa;
        padding:10px;
        font-size:13px;
        font-weight:900;
        color:#5f5656;
        cursor:pointer;
        transition:0.2s;
    }

    .day-box:hover{
        background:rgba(235,186,185,0.22);
        border-color:var(--accent);
    }

    .day-box.selected{
        background:rgba(235,186,185,0.40);
        border-color:var(--primary);
        color:var(--primary);
    }

    .day-box.registered{
        background:rgba(25,135,84,0.08);
        border-color:rgba(25,135,84,0.18);
    }

    .day-box.busy{
        background:rgba(220,53,69,0.08);
        border-color:rgba(220,53,69,0.18);
    }

    .shift-label{
        margin-top:8px;
        display:inline-block;
        padding:4px 8px;
        border-radius:999px;
        background:var(--primary);
        color:white;
        font-size:10px;
        font-weight:900;
    }

    .busy-label{
        background:#dc3545;
    }

    .summary-grid{
        display:grid;
        grid-template-columns:repeat(2,1fr);
        gap:18px;
        margin-top:22px;
    }

    .summary-card{
        background:white;
        border:1px solid #f1e7e7;
        border-radius:22px;
        padding:22px;
        box-shadow:0 12px 30px rgba(123,85,84,0.06);
    }

    .summary-label{
        color:var(--muted);
        font-size:13px;
        font-weight:900;
        margin-bottom:8px;
    }

    .summary-number{
        font-size:30px;
        font-weight:900;
        color:var(--primary);
    }

    .shift-card{
        border:1px solid #f1e7e7;
        border-radius:18px;
        padding:16px;
        margin-bottom:14px;
        background:white;
        cursor:pointer;
        transition:0.2s;
    }

    .shift-card.active{
        border-color:var(--primary);
        background:rgba(235,186,185,0.22);
    }

    .shift-name{
        font-weight:900;
        color:var(--text);
        margin-bottom:4px;
    }

    .shift-time{
        color:var(--muted);
        font-size:13px;
        font-weight:700;
    }

    .form-label-ui{
        font-weight:900;
        color:#5f5656;
        font-size:13px;
        margin-bottom:8px;
    }

    .form-control-ui{
        width:100%;
        border:1px solid var(--border);
        border-radius:14px;
        padding:12px 14px;
        font-weight:700;
        color:#5f5656;
        outline:none;
        background:white;
    }

    .form-control-ui:focus{
        border-color:var(--primary);
        box-shadow:0 0 0 4px rgba(235,186,185,0.35);
    }

    .btn-save{
        width:100%;
        border:none;
        background:var(--primary);
        color:white;
        padding:14px;
        border-radius:15px;
        font-weight:900;
        transition:0.25s;
    }

    .btn-save:hover{
        background:var(--primary-dark);
    }

    .btn-busy{
        width:100%;
        border:1px solid rgba(220,53,69,0.18);
        background:rgba(220,53,69,0.06);
        color:#dc3545;
        padding:13px;
        border-radius:15px;
        font-weight:900;
        margin-top:12px;
    }

    .btn-clear{
        width:100%;
        border:1px dashed var(--accent);
        background:white;
        color:var(--primary);
        padding:12px;
        border-radius:15px;
        font-weight:900;
        margin-top:12px;
    }

    .tip-box{
        margin-top:22px;
        background:rgba(235,186,185,0.20);
        border:1px solid rgba(235,186,185,0.65);
        border-radius:20px;
        padding:18px;
        color:#6b5c5c;
        font-size:13px;
        font-weight:700;
        line-height:1.7;
    }

    @media(max-width:992px){
        .schedule-layout{
            grid-template-columns:1fr;
        }
    }
</style>

@php
    use Carbon\Carbon;

    Carbon::setLocale('vi');

    $today = Carbon::today();
    $startOfMonth = $today->copy()->startOfMonth();
    $daysInMonth = $today->daysInMonth;

    $scheduleByDate = $schedules->keyBy(function($item){
        return Carbon::parse($item->work_date)->format('Y-m-d');
    });

    $registeredCount = $schedules->where('status', 'available')->count();
    $busyCount = $schedules->where('status', 'busy')->count();

    $totalMinutes = $schedules->where('status', 'available')->sum(function($item){
        if(!$item->start_time || !$item->end_time){
            return 0;
        }

        return Carbon::parse($item->start_time)->diffInMinutes(Carbon::parse($item->end_time));
    });

    $totalHourText = floor($totalMinutes / 60) . 'h';
    if($totalMinutes % 60 > 0){
        $totalHourText .= ' ' . ($totalMinutes % 60) . 'p';
    }
@endphp

<div class="schedule-title">
    Đăng ký lịch làm
</div>

<div class="schedule-layout">

    {{-- LEFT --}}
    <div>

        <div class="card-ui">
            <div class="section-title">
                Lịch tháng {{ $today->format('m/Y') }}
            </div>

            <div class="calendar-grid">
                <div class="day-name">T2</div>
                <div class="day-name">T3</div>
                <div class="day-name">T4</div>
                <div class="day-name">T5</div>
                <div class="day-name">T6</div>
                <div class="day-name">T7</div>
                <div class="day-name">CN</div>

                @for($blank = 1; $blank < $startOfMonth->dayOfWeekIso; $blank++)
                    <div></div>
                @endfor

                @for($i = 1; $i <= $daysInMonth; $i++)
                    @php
                        $dateObj = $today->copy()->day($i);
                        $dateKey = $dateObj->format('Y-m-d');
                        $schedule = $scheduleByDate->get($dateKey);
                    @endphp

                    <div class="day-box {{ $schedule ? ($schedule->status == 'busy' ? 'busy' : 'registered') : '' }}"
                         onclick="selectDate('{{ $dateKey }}', this)">

                        {{ $i }}

                        @if($schedule)
                            @if($schedule->status == 'busy')
                                <div class="shift-label busy-label">
                                    Bận / Nghỉ
                                </div>
                            @else
                                <div class="shift-label">
                                    {{ $schedule->shift_name }}
                                </div>
                            @endif
                        @endif

                    </div>
                @endfor
            </div>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-label">Tổng giờ đã đăng ký</div>
                <div class="summary-number">{{ $totalHourText }}</div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Ngày đã đăng ký</div>
                <div class="summary-number">{{ $registeredCount }}</div>
                <div class="text-muted fw-bold" style="font-size:12px;">
                    Nghỉ / bận: {{ $busyCount }} ngày
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div>

        <div class="card-ui">
            <div class="section-title">
                Chi tiết ca làm
            </div>

            <form action="{{ route('staff.scheduleRegistration.store') }}" method="POST" id="scheduleForm">
                @csrf

                <div class="mb-3">
                    <div class="form-label-ui">Ngày làm việc</div>
                    <input type="date"
                           name="work_date"
                           id="work_date"
                           class="form-control-ui"
                           value="{{ now()->toDateString() }}"
                           required>
                </div>

                <input type="hidden" name="shift_name" id="shift_name" value="Ca sáng">
                <input type="hidden" name="start_time" id="start_time" value="08:00">
                <input type="hidden" name="end_time" id="end_time" value="12:00">

                <div class="shift-card active"
                     onclick="selectShift(this, 'Ca sáng', '08:00', '12:00')">
                    <div class="shift-name">
                        <i class="bi bi-sun"></i> Ca sáng
                    </div>
                    <div class="shift-time">08:00 - 12:00</div>
                </div>

                <div class="shift-card"
                     onclick="selectShift(this, 'Ca chiều', '13:00', '17:00')">
                    <div class="shift-name">
                        <i class="bi bi-brightness-high"></i> Ca chiều
                    </div>
                    <div class="shift-time">13:00 - 17:00</div>
                </div>

                <div class="shift-card"
                     onclick="selectShift(this, 'Ca tối', '18:00', '21:00')">
                    <div class="shift-name">
                        <i class="bi bi-moon"></i> Ca tối
                    </div>
                    <div class="shift-time">18:00 - 21:00</div>
                </div>

                <div class="mb-3 mt-3">
                    <div class="form-label-ui">Ghi chú</div>
                    <textarea name="note"
                              class="form-control-ui"
                              rows="3"
                              placeholder="Ví dụ: Có thể nhận lịch khu vực gần nhà..."></textarea>
                </div>

                <button type="submit" class="btn-save">
                    <i class="bi bi-save"></i>
                    Lưu lịch làm việc
                </button>

            </form>

            <form action="{{ route('staff.scheduleRegistration.busy') }}" method="POST">
                @csrf

                <input type="hidden" name="work_date" id="busy_work_date" value="{{ now()->toDateString() }}">

                <button type="submit"
                        class="btn-busy"
                        onclick="return confirm('Bạn muốn đánh dấu ngày này là bận/xin nghỉ?')">
                    <i class="bi bi-x-circle"></i>
                    Đánh dấu bận / Xin nghỉ
                </button>
            </form>

            <button type="button" class="btn-clear" onclick="clearSelection()">
                <i class="bi bi-arrow-clockwise"></i>
                Chọn lại
            </button>
        </div>

        <div class="tip-box">
            <b><i class="bi bi-info-circle"></i> Mẹo nhỏ</b><br>
            Chọn ngày trên lịch, chọn ca sáng/chiều/tối rồi bấm lưu. Nếu ngày đó bạn không làm được, bấm “Đánh dấu bận / Xin nghỉ”.
        </div>

    </div>

</div>

<script>
    function selectDate(date, element){
        document.getElementById('work_date').value = date;
        document.getElementById('busy_work_date').value = date;

        document.querySelectorAll('.day-box').forEach(item => {
            item.classList.remove('selected');
        });

        element.classList.add('selected');
    }

    function selectShift(element, shiftName, startTime, endTime){
        document.querySelectorAll('.shift-card').forEach(item => {
            item.classList.remove('active');
        });

        element.classList.add('active');

        document.getElementById('shift_name').value = shiftName;
        document.getElementById('start_time').value = startTime;
        document.getElementById('end_time').value = endTime;
    }

    function clearSelection(){
        document.getElementById('work_date').value = "{{ now()->toDateString() }}";
        document.getElementById('busy_work_date').value = "{{ now()->toDateString() }}";

        document.querySelectorAll('.day-box').forEach(item => {
            item.classList.remove('selected');
        });

        const firstShift = document.querySelector('.shift-card');
        if(firstShift){
            selectShift(firstShift, 'Ca sáng', '08:00', '12:00');
        }
    }
</script>

@endsection