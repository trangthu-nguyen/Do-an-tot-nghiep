<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffSchedule extends Model
{
    protected $table = 'staff_schedules';
    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'staff_id',
        'work_date',
        'shift_name',
        'start_time',
        'end_time',
        'status',
        'note'
    ];
}