<?php

use App\Http\Controllers\Admin\SalesReportExportController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', StorefrontController::class)->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::post('cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('cart/{productId}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('checkout', [CartController::class, 'checkout'])->name('checkout');

    Route::get('admin/reports/sales/{format}', SalesReportExportController::class)
        ->whereIn('format', ['excel', 'pdf'])
        ->name('admin.reports.sales');
});

require __DIR__.'/settings.php';
