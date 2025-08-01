<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_no',     // ✅ Add this
        'total',
        'discount',
        'grand_total',    // ✅ Add this (if you want it stored)
        'user_id',
        'payment_method',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
