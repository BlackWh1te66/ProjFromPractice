<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    public $timestamps = false;
    protected $fillable = [
        'name', 'category_id', 'description', 'price', 'duration_minutes', 'image'
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\ServiceCategory::class, 'category_id');
    }
}
