<?php

namespace App\Http\Controllers;

use App\Models\SaleItem;
use Illuminate\Http\Request;
use App\Exports\SaleItemsExport;
use Maatwebsite\Excel\Facades\Excel;

class SaleItemController extends Controller
{
    // Display list of sale items with filters
    public function index(Request $request)
    {
        $query = SaleItem::with(['product', 'sale.customer']);

        if ($search = $request->input('search')) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($start = $request->input('start_date')) {
            $query->whereDate('created_at', '>=', $start);
        }

        if ($end = $request->input('end_date')) {
            $query->whereDate('created_at', '<=', $end);
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->orderByDesc('created_at')->paginate($perPage);

        return view('sale-items.index', compact('items'));
    }

    // Update sale item (for inline editing)
    public function update(Request $request, SaleItem $sale_item)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $sale_item->update([
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        return redirect()->route('sale-items.index')->with('success', 'Sale item updated successfully.');
    }

    // Delete sale item
    public function destroy(SaleItem $sale_item)
    {
        $sale_item->delete();

        return redirect()->route('sale-items.index')->with('success', 'Sale item deleted.');
    }

    // Export sale items to Excel
    public function export()
    {
        return Excel::download(new SaleItemsExport, 'sale_items.xlsx');
    }
}
