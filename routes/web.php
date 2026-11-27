<?php

use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminCourtController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminCrmController;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\AdminPricingController;
use App\Http\Controllers\AdminVenueController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourtBookingController;
use App\Http\Controllers\ContentHubController;
use App\Http\Controllers\CustomerBookingController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\MatchmakingController;
use App\Http\Controllers\MatchHistoryController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\VnpayController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - GoSport Badminton System
|--------------------------------------------------------------------------
*/

// Public Client Routes
Route::get('/', [ExploreController::class, 'index'])->name('home');
Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
Route::get('/venues/{slug}', [CourtBookingController::class, 'showVenue'])->name('venues.show');
Route::get('/venue/{slug}', [CourtBookingController::class, 'showVenue'])->name('explore.venue');
Route::get('/hub', [ContentHubController::class, 'index'])->name('hub');
Route::get('/hub/{post:slug}', [ContentHubController::class, 'show'])->name('hub.show');
Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');
Route::get('/api/courts/{id}/slots', [CourtBookingController::class, 'getSlotsApi'])->name('api.courts.slots');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// VNPay server callbacks are public endpoints authenticated by VNPay HMAC.
Route::get('/payments/vnpay/return', [VnpayController::class, 'return'])->name('vnpay.return');
Route::get('/payments/vnpay/ipn', [VnpayController::class, 'ipn'])->name('vnpay.ipn');

// Authenticated Client Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/promotions/{id}/save', [PromotionController::class, 'saveVoucher'])->name('promotions.save');
    Route::get('/wallet', [WalletController::class, 'show'])->name('wallet.show');
    Route::post('/wallet/topup', [WalletController::class, 'topup'])->name('wallet.topup');
    Route::put('/profile', [WalletController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [WalletController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('/matchmaking', [MatchmakingController::class, 'index'])->name('matchmaking');
    Route::post('/matchmaking', [MatchmakingController::class, 'store'])->name('matchmaking.store');
    Route::get('/matchmaking/{matchPost}', [MatchmakingController::class, 'show'])->name('matchmaking.show');
    Route::post('/matchmaking/{matchPost}/join', [MatchmakingController::class, 'join'])->name('matchmaking.join');
    Route::get('/match-history', [MatchHistoryController::class, 'index'])->name('match-history.index');
    Route::post('/match-history', [MatchHistoryController::class, 'store'])->name('match-history.store');
    Route::post('/booking/submit', [CourtBookingController::class, 'submitBooking'])->name('booking.submit');
    Route::post('/my-bookings/{id}/vnpay', [VnpayController::class, 'create'])->name('my-bookings.vnpay');
    Route::get('/my-bookings', [CustomerBookingController::class, 'index'])->name('my-bookings');
    Route::get('/my-bookings/list', [CustomerBookingController::class, 'index'])->name('my-bookings.index');
    Route::get('/my-bookings/{id}', [CustomerBookingController::class, 'show'])->name('my-bookings.show');
    Route::get('/my-bookings/{id}/qr', [CustomerBookingController::class, 'qr'])->name('my-bookings.qr');
    Route::post('/my-bookings/{id}/cancel', [CustomerBookingController::class, 'cancel'])->name('my-bookings.cancel');
    Route::post('/my-bookings/{id}/review', [CustomerBookingController::class, 'submitReview'])->name('my-bookings.review');
});

// Admin Operations & Management Portal Routes (Strict Server-Side Authorization)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Venues (Cụm sân)
    Route::get('/venues', [AdminVenueController::class, 'index'])->name('venues.index');
    Route::get('/venues/create', [AdminVenueController::class, 'create'])->name('venues.create');
    Route::post('/venues', [AdminVenueController::class, 'store'])->name('venues.store');
    Route::get('/venues/{id}/edit', [AdminVenueController::class, 'edit'])->name('venues.edit');
    Route::put('/venues/{id}', [AdminVenueController::class, 'update'])->name('venues.update');
    Route::post('/venues/{id}/toggle', [AdminVenueController::class, 'toggleActive'])->name('venues.toggle');

    // Child Courts (Sân con)
    Route::post('/venues/{venueId}/courts', [AdminCourtController::class, 'store'])->name('courts.store');
    Route::put('/courts/{id}', [AdminCourtController::class, 'update'])->name('courts.update');
    Route::post('/courts/{id}/toggle', [AdminCourtController::class, 'toggleActive'])->name('courts.toggle');
    Route::delete('/courts/{id}', [AdminCourtController::class, 'destroy'])->name('courts.destroy');

    // Operating Hours & Hourly Pricing Rules (Giờ hoạt động & Bảng giá theo giờ)
    Route::get('/pricing', [AdminPricingController::class, 'index'])->name('pricing.index');
    Route::post('/pricing', [AdminPricingController::class, 'update'])->name('pricing.update');
    Route::post('/pricing/rule', [AdminPricingController::class, 'storeRule'])->name('pricing.storeRule');
    Route::delete('/pricing/rule/{id}', [AdminPricingController::class, 'destroyRule'])->name('pricing.destroyRule');
    Route::post('/pricing/operating-hours', [AdminPricingController::class, 'updateOperatingHours'])->name('pricing.operatingHours');

    // Bookings Management (Quản lý đơn đặt sân)
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');

    Route::get('/crm', [AdminCrmController::class, 'index'])->name('crm.index');
    Route::post('/crm/membership', [AdminCrmController::class, 'assignMembership'])->name('crm.membership');
    Route::post('/crm/contracts', [AdminCrmController::class, 'storeContract'])->name('crm.contracts.store');
    Route::post('/crm/contracts/{contract}/toggle', [AdminCrmController::class, 'toggleContract'])->name('crm.contracts.toggle');

    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reply', [AdminReviewController::class, 'reply'])->name('reviews.reply');
});
