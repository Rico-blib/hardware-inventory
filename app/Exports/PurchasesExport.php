<?php

namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchasesExport implements FromCollection, WithHeadings
{
    protected $purchases;

    public function __construct(Collection $purchases)
    {
        $this->purchases = $purchases;
    }

    public function collection()
    {
        return $this->purchases->map(function ($purchase) {
            return [
                'Invoice No'   => $purchase->invoice_no,
                'Supplier'     => $purchase->supplier->name ?? 'Unknown',
                'Date'         => $purchase->created_at->format('Y-m-d'),
                'Total (Ksh)'  => number_format($purchase->total, 2),
            ];
        });
    }

    public function headings(): array
    {
        return ['Invoice No', 'Supplier', 'Date', 'Total (Ksh)'];
    }
}
