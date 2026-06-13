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
        'created_at',
        'status',
        'is_hidden',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    // Các dịch vụ nằm trong booking đã được đánh giá
    public function services()
    {
        return $this->belongsToMany(
            Service::class,
            'booking_details',
            'booking_id',
            'service_id',
            'booking_id',
            'service_id'
        );
    }
}