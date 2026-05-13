<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $primaryKey = 'service_id';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'service_name',
        'price',
        'duration',
        'description',
        'image',
        'status'
    ];

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class, 'service_id', 'service_id');
    }
}