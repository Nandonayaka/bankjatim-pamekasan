<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebitController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

// Guest / Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (Require Login)
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Purchase (Kulaan)
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::delete('/purchases/{id}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');

    // Sales (Penjualan)
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::delete('/sales/{id}', [SaleController::class, 'destroy'])->name('sales.destroy');

    // Debit (Keuntungan / Margin)
    Route::get('/debit', [DebitController::class, 'index'])->name('debit.index');

    // Stock (Stok Barang)
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
    Route::put('/stock/{id}', [StockController::class, 'update'])->name('stock.update');
    Route::delete('/stock/{id}', [StockController::class, 'destroy'])->name('stock.destroy');
});
