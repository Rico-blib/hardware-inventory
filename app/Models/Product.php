<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'category_id', 'packaging', 'manufacturer'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
