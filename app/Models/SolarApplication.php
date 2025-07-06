<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolarApplication extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'location', 'system_config', 'message'
    ];
}
