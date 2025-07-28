<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'cost_price',
        'selling_price',
        'batch_no',
        'expiry_date',
    ];

    protected static function booted()
    {
        static::created(function ($item) {
            $item->purchase?->recalculateTotal();
        });

        static::updated(function ($item) {
            $item->purchase?->recalculateTotal();
        });

        static::deleted(function ($item) {
            $item->purchase?->recalculateTotal();
        });
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
