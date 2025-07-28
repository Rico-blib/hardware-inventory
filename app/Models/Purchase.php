<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Purchase extends Model
{
    protected $fillable = [
        'supplier_id',
        'invoice_no',
        'date',
        'total',
        'payment_status',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
    public function recalculateTotal()
    {
        $total = $this->items()->sum(DB::raw('quantity * cost_price'));
        $this->update(['total' => $total]);
        // or use the fallback if needed
    }
}
