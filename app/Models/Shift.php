<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shifts';

    protected $primaryKey = 'shift_id';

    public $timestamps = true;

    protected $fillable = [
        'staff_id',
        'shift_date',
        'start_time',
        'end_time',
        'status'
    ];
}