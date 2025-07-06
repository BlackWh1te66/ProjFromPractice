<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'product_id', 'product_name', 'product_price', 'customer_name', 'customer_phone', 'customer_email', 'customer_address', 'status'
    ];
}