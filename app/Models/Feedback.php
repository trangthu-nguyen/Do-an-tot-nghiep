<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';
    protected $primaryKey = 'feedback_id';
    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'rating',
        'comment',
        'created_at'
    ];

    // Quan hệ: Feedback thuộc về Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    // Quan hệ: Feedback thuộc về Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
}