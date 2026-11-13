<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - GoSport UI Preview on Laravel Base
|--------------------------------------------------------------------------
*/

// Trang chu GoSport
Route::view('/', 'home')->name('home');

// Dashboard Admin GoSport
Route::view('/admin', 'admin.dashboard')->name('admin.dashboard');
