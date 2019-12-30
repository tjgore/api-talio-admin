<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const PAGINATE = 40;
    
    protected $guarded = [
        'id', 'deleted_at', 'created_at', 'updated_at','business_id'
    ];

    protected $hidden = ['deleted_at'];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function specs()
    {
        return $this->hasMany(Specification::class);
    }

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }
}
