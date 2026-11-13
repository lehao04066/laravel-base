<?php

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

// Dashboard Admin
Route::view('/admin', 'admin.dashboard')->name('admin.dashboard');
