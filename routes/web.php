<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ProductController::class, 'index'])->name('index');
Route::get('/home', [ProductController::class, 'index']);

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/store-checkout', [CheckoutController::class, 'store'])->name('store-checkout');

Route::get('/coupon-show/{code}', [CouponController::class, 'show'])->name('coupon-show');

Route::get('/payment/{token}/{order_id}', [PaymentController::class, 'index']);
