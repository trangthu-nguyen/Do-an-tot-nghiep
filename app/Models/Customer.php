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
        'created_at'
    ];
}