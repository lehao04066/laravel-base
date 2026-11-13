<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth')->name('logout');


// VNPay payment flow: authenticated payment creation, browser return, and server IPN.
Route::middleware('auth')->get('/bookings/{bookingCode}/payment', [PaymentController::class, 'showVnpayPaymentForm'])
    ->name('payment.vnpay.form');
Route::middleware('auth')->post('/bookings/{bookingCode}/payment/vnpay', [PaymentController::class, 'createVnpayPaymentUrl'])
    ->name('payment.vnpay.create');
Route::get('/payment/vnpay-return', [PaymentController::class, 'vnpayReturn'])
    ->name('payment.vnpay.return');
Route::match(['get', 'post'], '/payment/vnpay-ipn', [PaymentController::class, 'vnpayIpn'])
    ->name('payment.vnpay.ipn');
