<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Admin;
use App\Models\Product;
use App\Models\Order;

class Store extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
