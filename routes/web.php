<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoachDashboardController;
use App\Http\Controllers\ExploreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - GoSport Badminton System on Laravel Base
|--------------------------------------------------------------------------
*/

// CV01: Khám phá cụm sân & Bộ lọc đa tiêu chí
Route::get('/', [ExploreController::class, 'index'])->name('home');
Route::get('/explore', [ExploreController::class, 'index'])->name('explore');

// Dashboard Admin (Giao diện quản trị GoSport)
Route::view('/admin', 'admin.dashboard')
    ->middleware(['auth', 'roles:super_admin,admin'])
    ->name('admin.dashboard');

// Authentication Routes
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::view('/dashboard/admin', 'admin.dashboard')
        ->middleware('roles:super_admin,admin')
        ->name('dashboard.admin');

    Route::view('/dashboard/owner', 'auth.role-dashboard', [
        'dashboardType' => 'owner',
        'roleLabel' => 'Chủ sân',
        'roleDescription' => 'Theo dõi hoạt động và cơ sở sân của bạn.',
    ])->middleware('roles:owner')->name('dashboard.owner');

    Route::view('/dashboard/staff', 'auth.role-dashboard', [
        'dashboardType' => 'staff',
        'roleLabel' => 'Nhân viên',
        'roleDescription' => 'Truy cập công cụ hỗ trợ vận hành và phục vụ khách hàng.',
    ])->middleware('roles:staff')->name('dashboard.staff');

    Route::get('/dashboard/coach', CoachDashboardController::class)
        ->middleware('roles:coach')
        ->name('dashboard.coach');

    Route::view('/dashboard/customer', 'auth.dashboard', [
        'roleLabel' => 'Thành viên',
        'roleDescription' => 'Đặt sân và kết nối với cộng đồng GoSport.',
    ])->middleware('roles:customer')->name('dashboard.customer');
});
