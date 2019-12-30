<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Order;

class Store extends Model
{
    const FB = 'facebook';
    const INSTA = 'instagram';
    const LINKEDIN = 'linkedin';
    const TWITTER = 'twitter';
    const YOUTUBE = 'youtube.com';

    const SOCIAL_ACCOUNTS = [
        self::FB,
        self::INSTA,
        self::LINKEDIN,
        self::TWITTER,
        self::YOUTUBE
    ];

    const TOTAL_SOCIAL_ACCOUNTS = 5;

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
