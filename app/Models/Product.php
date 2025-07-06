<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name', 'category_id', 'description', 'price', 'stock_status', 'image', 'brand'
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\DeviceCategory::class, 'category_id');
    }
}
