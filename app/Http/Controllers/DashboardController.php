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

        return view('dashboard', compact(
            'salesToday',
            'purchasesToday',
            'categoryCount',
            'expiredProductsCount',
            'lowStockCount',
            'userCount',
            'customerCount'
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
}
