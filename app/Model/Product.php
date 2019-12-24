<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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
