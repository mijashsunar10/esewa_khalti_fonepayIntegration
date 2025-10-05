<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




use App\Http\Controllers\KhaltiController;
use App\Http\Controllers\ProductController;
// use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Product Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Khalti Payment Routes
Route::get('khalti-checkout/{product:slug}', [KhaltiController::class, 'checkout'])->name('khalti.checkout');
Route::get('khalti-verification/{product:slug}', [KhaltiController::class, 'verification'])->name('khalti.verification');
Route::get('payment/success/{payment}', [KhaltiController::class, 'success'])->name('payment.success');
Route::get('payment/failed/{payment}', [KhaltiController::class, 'failed'])->name('payment.failed');
require __DIR__.'/auth.php';
