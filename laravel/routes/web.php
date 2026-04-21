<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\HelpDeskController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\PurchaseItemController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReceiptDownloadController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\ChatController;
use App\Http\Controllers\Store\CheckoutController;
use App\Http\Controllers\Store\InvoiceController;
use App\Http\Controllers\Store\OrderController;
use App\Http\Controllers\Store\ProductController;
use App\Http\Controllers\Store\SupportRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:5,1');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::post('/chat', [ChatController::class, 'message'])
    ->middleware(['throttle:chat', 'captcha'])
    ->name('chat.message');

Route::post('/support-requests', [SupportRequestController::class, 'store'])
    ->middleware(['throttle:support', 'captcha'])
    ->name('support-requests.store');

Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{stock}', [ProductController::class, 'show'])->whereNumber('stock')->name('products.show');
    Route::post('/cart/add/{stock}', [CartController::class, 'store'])->whereNumber('stock')->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/remove/{item}', [CartController::class, 'destroy'])->where('item', '[0-9]+')->name('cart.remove');
    Route::post('/checkout/stripe', [CheckoutController::class, 'stripe'])->middleware('throttle:10,1')->name('checkout.stripe');
    Route::get('/stripe/return', [CheckoutController::class, 'return'])->name('stripe.return');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/invoice/{purchase}', [InvoiceController::class, 'show'])->where('purchase', '[A-Za-z0-9\-]+')->name('invoice.show');
    Route::get('/download/receipt', [ReceiptDownloadController::class, 'show'])->name('receipt.download');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('stock', StockController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('deliveries', DeliveryController::class)->except(['show', 'create', 'store']);
    Route::resource('purchases', PurchaseController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('purchase-items', PurchaseItemController::class)->only(['edit', 'update', 'destroy']);
    Route::get('help-desk', [HelpDeskController::class, 'index'])->name('help-desk.index');
    Route::get('help-desk/{supportRequest}', [HelpDeskController::class, 'show'])->name('help-desk.show');
    Route::patch('help-desk/{supportRequest}', [HelpDeskController::class, 'update'])->name('help-desk.update');
});
