<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Category;
use App\Models\User;
use App\Models\Customer;
use App\Models\PurchaseItem;
use App\Models\SaleItem;



class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $salesToday = Sale::whereDate('created_at', $today)->sum('total');
        $purchasesToday = Purchase::whereDate('created_at', $today)->sum('total');
        $categoryCount = Category::count();

        // ✅ Count of expired purchase items
        $expiredProductsCount = DB::table('purchase_items')
            ->whereDate('expiry_date', '<', $today)
            ->count();

        // ✅ Count of products with low total quantity (< 10)
        $lowStockCount = DB::table('purchase_items')
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->having('total_qty', '<', 10)
            ->get()
            ->count();

        $userCount = User::count();
        $customerCount = Customer::count();

        // ✅ NEW: Top 5 selling products
        $topSellingProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select('products.name as product_name', DB::raw('SUM(sale_items.quantity) as total_sold'))
            ->groupBy('sale_items.product_id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // ✅ NEW: Bottom 5 selling products
        $bottomSellingProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select('products.name as product_name', DB::raw('SUM(sale_items.quantity) as total_sold'))
            ->groupBy('sale_items.product_id', 'products.name')
            ->orderBy('total_sold')
            ->limit(5)
            ->get();

        // ✅ Sales for the last 7 days
        $last7Days = collect();
        $salesPerDay = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $last7Days->push($date->format('D')); // e.g., Mon, Tue...
            $sales = Sale::whereDate('created_at', $date)->sum('total');
            $salesPerDay->push($sales);
        }

        // ✅ Pie chart: Top 5 categories by number of products
        $categoryProductCounts = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('COUNT(products.id) as product_count'))
            ->groupBy('categories.name')
            ->orderByDesc('product_count')
            ->limit(5)
            ->get();

        $categoryLabels = $categoryProductCounts->pluck('name');
        $categoryCounts = $categoryProductCounts->pluck('product_count');

        return view('dashboard', compact(
            'salesToday',
            'purchasesToday',
            'categoryCount',
            'expiredProductsCount',
            'lowStockCount',
            'userCount',
            'customerCount',
            'topSellingProducts',
            'bottomSellingProducts',
            'last7Days',
            'salesPerDay',
            'categoryLabels',
            'categoryCounts'
        ));
    }

    public function getExpiredItems()
    {
        $expiredItems = PurchaseItem::with('product')
            ->whereDate('expiry_date', '<', now())
            ->get();

        return view('dashboard.partials.expired_modal_content', compact('expiredItems'));
    }

    public function getLowStockItems()
    {
        $lowStockItems = PurchaseItem::with('product')
            ->select('product_id')
            ->groupBy('product_id')
            ->havingRaw('SUM(quantity) < 10') // Change 10 to your threshold
            ->get();

        return view('dashboard.partials.lowstock_modal_content', compact('lowStockItems'));
    }

    public function getTopSellingProducts()
    {
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select('products.name as product_name', DB::raw('SUM(sale_items.quantity) as total_sold'))
            ->groupBy('sale_items.product_id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('dashboard.partials.top_selling_modal_content', compact('topProducts'));
    }

    public function getBottomSellingProducts()
    {
        $bottomProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select('products.name as product_name', DB::raw('SUM(sale_items.quantity) as total_sold'))
            ->groupBy('sale_items.product_id', 'products.name')
            ->orderBy('total_sold')
            ->limit(5)
            ->get();

        return view('dashboard.partials.bottom_selling_modal_content', compact('bottomProducts'));
    }
}
