<?php

namespace App\Exports;

use App\Models\SaleItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SaleItemsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Get all sale items with related product and sale data
     */
    public function collection()
    {
        return SaleItem::with(['product', 'sale.customer'])->latest()->get();
    }

    /**
     * Define headings for the Excel sheet
     */
    public function headings(): array
    {
        return [
            'Date',
            'Customer',
            'Product',
            'Quantity',
            'Unit Price',
            'Subtotal',
        ];
    }

    /**
     * Map each sale item to a row
     */
    public function map($item): array
    {
        return [
            $item->created_at->format('Y-m-d'),
            $item->sale->customer->name ?? 'Walk-in',
            $item->product->name ?? 'Unknown',
            $item->quantity,
            number_format($item->unit_price, 2),
            number_format($item->quantity * $item->unit_price, 2),
        ];
    }
}
