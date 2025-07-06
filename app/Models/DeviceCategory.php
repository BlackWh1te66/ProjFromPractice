<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceCategory extends Model
{
    protected $table = 'device_categories';
    public $timestamps = false;
    protected $fillable = ['name'];
}
