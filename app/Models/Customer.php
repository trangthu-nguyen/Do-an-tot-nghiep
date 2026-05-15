<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'password',
        'address',
        'birth_date',
        'gender',
        'avatar',
        'created_at'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id', 'customer_id');
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? asset('uploads/customers/' . $this->avatar)
            : asset('uploads/avatar/default-avatar.png');
    }
}