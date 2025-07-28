<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;


class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Purchase::with('supplier');

        // Search by invoice number or supplier name
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('invoice_no', 'like', "%{$searchTerm}%")
                ->orWhereHas('supplier', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                });
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        // Pagination
        $perPage = $request->input('per_page', 10);
        $purchases = $query->latest()->paginate($perPage)->withQueryString();

        return view('purchases.index', compact('purchases'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('purchases.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_no' => 'required|string',
            'date' => 'required|date',
            'payment_status' => 'required|in:unpaid,partial,paid',
        ]);

        $purchase = Purchase::create($validated);

        return redirect()->route('purchase-items.create', ['purchase_id' => $purchase->id])
            ->with('success', 'Purchase details saved. Now add items.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::all();
        return view('purchases.edit', compact('purchase', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'invoice_no' => 'required|string',
            'date' => 'required|date',
            'payment_status' => 'required|in:unpaid,partial,paid',
        ]);

        $purchase->update($validated);

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['items.product', 'supplier']);
        return view('purchases.show', compact('purchase'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return redirect()->back()->with('success', 'Purchase deleted successfully.');
    }
}
