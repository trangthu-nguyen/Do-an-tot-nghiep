<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'booking_id';
    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'staff_id',
        'total_amount',
        'booking_date',
        'booking_time',
        'address',
        'note',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id', 'customer_id');
    }

    public function bookingDetails()
    {
        return $this->hasMany(\App\Models\BookingDetail::class, 'booking_id', 'booking_id');
    }

    public function staff()
    {
        return $this->belongsTo(\App\Models\Staff::class, 'staff_id', 'staff_id');
    }

    public function feedback()
    {
        return $this->hasOne(\App\Models\Feedback::class, 'booking_id', 'booking_id');
    }

    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class, 'booking_id', 'booking_id');
        
    }
}