<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\PurchaseItem;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;



class SaleController extends Controller
{
    // Show the create sale form
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();

        return view('sales.create', compact('customers', 'products'));
    }

    // Handle form submission
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,mpesa,bank',

        ]);

        $customerId = $request->input('customer_id') ?: null;

        // ✅ Generate a unique invoice number
        do {
            $invoiceNo = 'INV-' . strtoupper(uniqid());
        } while (Sale::where('invoice_no', $invoiceNo)->exists());

        // ✅ Calculate total
        $total = 0;
        foreach ($request->items as $item) {
            $total += $item['quantity'] * $item['price'];
        }

        $discount = $request->input('discount', 0);
        $grandTotal = $total - $discount;

        // ✅ Create the sale
        $sale = Sale::create([
            'customer_id' => $customerId,
            'invoice_no' => $invoiceNo,
            'total' => $total,
            'discount' => $discount,
            'grand_total' => $grandTotal,
            'user_id' => Auth::id(),
            'payment_method' => $request->payment_method,
        ]);

        // ✅ Process each sale item and deduct stock
        foreach ($request->items as $item) {
            $productId = $item['product_id'];
            $quantityToDeduct = $item['quantity'];

            // Create the sale item record
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $productId,
                'quantity' => $quantityToDeduct,
                'price' => $item['price'],
                'subtotal' => $quantityToDeduct * $item['price'],
            ]);

            // Deduct stock using FIFO from purchase_items
            $stocks = PurchaseItem::where('product_id', $productId)
                ->where('quantity', '>', 0)
                ->orderBy('expiry_date') // FIFO
                ->get();

            foreach ($stocks as $stock) {
                if ($quantityToDeduct <= 0) break;

                if ($stock->quantity >= $quantityToDeduct) {
                    $stock->quantity -= $quantityToDeduct;
                    $stock->save();
                    $quantityToDeduct = 0;
                } else {
                    $quantityToDeduct -= $stock->quantity;
                    $stock->quantity = 0;
                    $stock->save();
                }
            }

            // Optional: handle overselling
            if ($quantityToDeduct > 0) {
                // You can throw an error, log, or allow negative inventory
                // throw ValidationException::withMessages(["Stock for product ID $productId is not enough."]);
            }
        }

        return redirect()->route('sales.index')->with('success', 'Sale recorded successfully!');
    }


    // Display sales list
    public function index()
    {
        $sales = Sale::with('customer')->latest()->paginate(10);
        $customers = Customer::all(); // 
        return view('sales.index', compact('sales', 'customers'));
    }

    // Show individual sale
    public function show(Sale $sale)
    {
        $sale->load('items.product', 'customer');
        return view('sales.show', compact('sale'));
    }

    public function receipt($id)
    {
        // Load the sale with its relations
        $sale = Sale::with(['customer', 'items.product', 'user'])->findOrFail($id);

        // Get settings for logo, app name, contacts
        $settings = Setting::first();

        return view('sales.receipt', compact('sale', 'settings'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'discount' => 'required|numeric|min:0',
        ]);

        $discount = $request->input('discount');
        $total = $sale->items->sum(fn($item) => $item->price * $item->quantity);
        $grandTotal = $total - $discount;

        $sale->update([
            'customer_id' => $request->input('customer_id'),
            'discount' => $discount,
            'total' => $total,
            'grand_total' => $grandTotal,
        ]);

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully!');
    }


    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        // Delete related sale items first to avoid constraint errors
        $sale->items()->delete();

        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }
}
