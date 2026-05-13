<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staffs';
    protected $primaryKey = 'staff_id';
    public $timestamps = false;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'password',
        'address',
        'skill',
        'status',
        'bio'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'staff_id', 'staff_id');
    }
}