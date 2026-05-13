<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'admin_id';
    public $timestamps = false;

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'created_at'
    ];
}