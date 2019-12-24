<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const USD = 'USD';
    
    const CURRENCY = [
        self::USD
    ];

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'meta' => 'array'
    ];
}
