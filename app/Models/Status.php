<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // products
    const PRODUCT_TYPE = 'Product';
    const AVAILABLE = 1;
    const UNAVAILABLE = 2;
    const COMING_SOON = 3;
    const DRAFT = 4;

    // store
    const STORE_TYPE = 'Store';
    const ACTIVE = 5;
    const INACTIVE = 6;

    const ORDER_TYPE = 'Order';
    const PAID = 7;
    const PENDING = 8;
    const REFUND = 9;
    const UNPAID = 10;
    const FAIL = 11;

    const PRODUCT = [
        self::AVAILABLE => 'Available',
        self::UNAVAILABLE => 'Unavailable',
        self::COMING_SOON => 'Coming soon',
        self::DRAFT => 'Draft',
    ];

    const STORE = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive',
    ];

    const ORDER = [
        self::PAID => 'Paid',
        self::PENDING => 'Pending',
        self::REFUND => 'Refund',
        self::UNPAID => 'Unpaid',
        self::FAIL => 'Fail'
    ];

    protected $guarded = ['id'];

    public $timestamps = false;
}
