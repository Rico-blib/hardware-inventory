<?php

namespace App\Http\Controllers;

use App\Models\PurchaseItem;
use App\Models\Product;
use Illuminate\Http\Request;

class PurchaseItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PurchaseItem::with(['product', 'purchase.supplier']); // added supplier relation

        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $perPage = $request->input('per_page', 10);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                })->orWhere('batch_no', 'like', "%{$search}%");
            });
        }

        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $items = $query->latest()->paginate($perPage)->withQueryString();

        return view('purchase-items.index', compact('items', 'perPage', 'search', 'startDate', 'endDate'));
    }
    
    public function create(Request $request)
    {
        $purchaseId = $request->purchase_id;
        $products = Product::all();
        return view('purchase-items.create', compact('products', 'purchaseId'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_id'   => 'required|exists:purchases,id',
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'required|integer|min:1',
            'cost_price'    => 'required|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'batch_no'      => 'nullable|string|max:255',
            'expiry_date'   => 'nullable|date',
        ]);

        PurchaseItem::create($validated);

        return back()->with('success', 'Item added successfully.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseItem $purchaseItem)
    {
        $validated = $request->validate([
            'quantity'      => 'required|integer|min:1',
            'cost_price'    => 'required|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'batch_no'      => 'nullable|string|max:255',
            'expiry_date'   => 'nullable|date',
        ]);

        $purchaseItem->update($validated);

        return back()->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseItem $purchaseItem)
    {
        $purchaseItem->delete();

        return back()->with('success', 'Item deleted successfully.');
    }
}
