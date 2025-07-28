<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\PurchaseItem;


class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');

        $products = Product::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('manufacturer', 'like', "%$search%");
            })
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        $categories = Category::all();

        return view('products.index', compact('products', 'perPage', 'search', 'categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'packaging' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
        ]);

        Product::create($request->only('name', 'category_id', 'packaging', 'manufacturer'));

        return redirect()->back()->with('success', 'Product created successfully.');
    }
    
    public function getSellingPrice($id)
    {
        // Get latest selling price for this product
        $price = PurchaseItem::where('product_id', $id)
            ->orderByDesc('created_at') // assuming latest purchase is most accurate
            ->value('selling_price');

        return response()->json(['price' => $price ?? 0]);
    }


    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'packaging' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
        ]);

        $product->update($request->only('name', 'category_id', 'packaging', 'manufacturer'));

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
