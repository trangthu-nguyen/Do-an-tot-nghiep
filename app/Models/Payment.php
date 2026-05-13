<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'payment_method',
        'amount',
        'payment_status',
        'payment_date'
    ];

    public function booking()
    {
        return $this->belongsTo(\App\Models\Booking::class, 'booking_id', 'booking_id');
    }
}