<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseItemController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleItemController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\SuperAdminOnly;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ActivationController;



Route::get('/activate', [ActivationController::class, 'show'])->name('activation.show');
Route::post('/activate', [ActivationController::class, 'activate'])->name('activation.submit');
// Public welcome
Route::get('/', [AuthenticatedSessionController::class, 'create'])->middleware('guest');


// Dashboard - all authenticated users with trial check
Route::middleware(['auth', 'check.trial'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/expired-items', [DashboardController::class, 'getExpiredItems'])->name('dashboard.expired-items');
    Route::get('/dashboard/low-stock-items', [DashboardController::class, 'getLowStockItems'])->name('dashboard.low-stock-items');
    Route::get('/dashboard/top-selling-items', [DashboardController::class, 'getTopSellingProducts'])->name('dashboard.top-selling-items');
    Route::get('/dashboard/bottom-selling-items', [DashboardController::class, 'getBottomSellingProducts'])->name('dashboard.bottom-selling-items');
});

// Profile - all authenticated users with trial check
Route::middleware(['auth', 'check.trial'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Super-admin only routes with trial check
Route::middleware(['auth', 'check.trial', SuperAdminOnly::class])->group(function () {
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('products', ProductController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::resource('purchase-items', PurchaseItemController::class);
    Route::get('/products/{id}/price', [ProductController::class, 'getSellingPrice']);

    // Settings page
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

// Sales & reports for all authenticated users with trial check
Route::middleware(['auth', 'check.trial'])->group(function () {
    // Sales
    Route::resource('sales', SaleController::class);
    Route::resource('sale-items', SaleItemController::class)->only(['index', 'update', 'destroy']);
    Route::get('/sales/{id}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
    Route::get('sale-items-export', [SaleItemController::class, 'export'])->name('sale-items.export');

    // Reports
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::post('/reports/sales/data', [ReportController::class, 'salesData'])->name('reports.sales.data');
    Route::get('/reports/sales/export/{type}', [ReportController::class, 'exportSales'])->name('reports.sales.export');

    Route::get('/reports/purchases', [ReportController::class, 'purchases'])->name('reports.purchases');
    Route::post('/reports/purchases/data', [ReportController::class, 'purchasesData'])->name('reports.purchases.data');
    Route::get('/reports/purchases/export/{type}', [ReportController::class, 'exportPurchases'])->name('reports.purchases.export');
});

require __DIR__ . '/auth.php';
