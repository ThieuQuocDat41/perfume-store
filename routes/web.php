<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/admin-test', function(){

    return 'Admin only';
    
    })->middleware('admin');

require __DIR__.'/auth.php';

// DEMO: choose admin middleware depending on security mode
$adminMiddleware = ['auth'];
if (config('app.security_mode', env('APP_SECURITY_MODE', 'secure')) === 'secure') {
    // DEMO: secure mode requires admin middleware
    $adminMiddleware = ['auth','admin'];
} else {
    // DEMO: vulnerable mode only requires auth for admin routes
    // (intentionally permissive for demo)
    $adminMiddleware = ['auth'];
}

Route::get('/products',
[ProductController::class,'index']);
Route::get('/products/create', [ProductController::class, 'create'])->middleware('auth');

Route::get('/products/{id}',
[ProductController::class,'show']);

Route::post('/products/{id}/image', [ProductController::class, 'updateImage'])->middleware('auth','admin');
Route::post('/products/{id}/tags', [ProductController::class, 'updateTags'])->middleware('auth','admin');

Route::post('/cart/add/{productId}', [CartController::class, 'add'])->middleware('auth');

Route::get('/admin/products/clean-missing', [ProductController::class, 'cleanMissing'])->middleware('auth','admin');
Route::post('/admin/products/{id}/delete', [ProductController::class, 'destroy'])->middleware('auth','admin');

Route::get(
    '/add-to-cart/{productId}',
    [CartController::class,'add']
    )->middleware('auth');
    
    Route::get(
    '/cart',
    [CartController::class,'viewCart']
    )->middleware('auth');

    Route::post('/checkout', [OrderController::class, 'confirm'])
        ->middleware('auth')
        ->name('checkout');

    Route::post('/checkout/confirm', [OrderController::class, 'finalize'])
        ->middleware('auth')
        ->name('checkout.confirm');
        
Route::get('/orders', [OrderController::class, 'index'])
    ->middleware('auth')
    ->name('orders.index');

Route::get('/orders/{id}', [OrderController::class, 'show'])
    ->middleware('auth')
    ->name('orders.show');
Route::get('/profile', function () {
    return view('profile');
})->middleware('auth');


Route::get('/profile', [ProfileController::class, 'index'])
    ->name('profile')
    ->middleware('auth');
Route::get('/profile/edit', [ProfileController::class, 'edit'])
    ->middleware('auth')
    ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
    ->name('profile.update')
    ->middleware('auth');

Route::post('/products', [ProductController::class, 'store'])->middleware('auth');

Route::middleware($adminMiddleware)->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard']);

    // Users
    Route::get('/users', [AdminController::class, 'usersIndex']);
    Route::get('/users/{id}', [AdminController::class, 'usersShow']);
    Route::get('/users/{id}/edit', [AdminController::class, 'usersEdit']);
    Route::patch('/users/{id}', [AdminController::class, 'usersUpdate']);
    Route::post('/users/{id}/delete', [AdminController::class, 'usersDestroy']);

    // Products (admin management)
    Route::get('/products', [AdminController::class, 'productsIndex']);
    Route::get('/products/{id}/edit', [AdminController::class, 'productsEdit']);
    Route::patch('/products/{id}', [AdminController::class, 'productsUpdate']);
    
    // Vouchers
    Route::get('/vouchers', [AdminController::class, 'vouchersIndex']);
    Route::get('/vouchers/create', [AdminController::class, 'vouchersCreate']);
    Route::post('/vouchers', [AdminController::class, 'vouchersStore']);
    Route::post('/vouchers/{id}/delete', [AdminController::class, 'vouchersDestroy']);
});

Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])
    ->name('cart.remove');
    Route::patch('/cart/{id}/increase', [CartController::class, 'increase'])
    ->name('cart.increase');

Route::patch('/cart/{id}/decrease', [CartController::class, 'decrease'])
    ->name('cart.decrease');