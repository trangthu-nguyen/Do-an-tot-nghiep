<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    protected $table = 'booking_details';
    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'service_id',
        'price',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }
}
