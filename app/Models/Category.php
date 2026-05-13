<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    protected $fillable = [
        'category_name',
        'description'
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id', 'category_id');
    }
}