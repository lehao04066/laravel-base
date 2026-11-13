<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'))->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::view('/dashboard/admin', 'auth.dashboard', [
        'roleLabel' => 'Quản trị viên',
        'roleDescription' => 'Quản lý và vận hành hệ thống GoSport.',
    ])->middleware('roles:super_admin,admin')->name('dashboard.admin');

    Route::view('/dashboard/owner', 'auth.dashboard', [
        'roleLabel' => 'Chủ sân',
        'roleDescription' => 'Theo dõi hoạt động và cơ sở sân của bạn.',
    ])->middleware('roles:owner')->name('dashboard.owner');

    Route::view('/dashboard/staff', 'auth.dashboard', [
        'roleLabel' => 'Nhân viên',
        'roleDescription' => 'Truy cập công cụ hỗ trợ vận hành và phục vụ khách hàng.',
    ])->middleware('roles:staff')->name('dashboard.staff');

    Route::view('/dashboard/coach', 'auth.dashboard', [
        'roleLabel' => 'Huấn luyện viên',
        'roleDescription' => 'Quản lý hoạt động huấn luyện và lịch học.',
    ])->middleware('roles:coach')->name('dashboard.coach');

    Route::view('/dashboard/customer', 'auth.dashboard', [
        'roleLabel' => 'Thành viên',
        'roleDescription' => 'Đặt sân và kết nối với cộng đồng GoSport.',
    ])->middleware('roles:customer')->name('dashboard.customer');
});
