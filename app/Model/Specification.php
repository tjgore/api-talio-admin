<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Specification extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'property' => 'array',
    ];

    public $hidden = ['created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
