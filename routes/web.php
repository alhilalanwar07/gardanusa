<?php

use Illuminate\Support\Facades\{Route, Auth};

// disable register, reset password
Auth::routes(['register' => false, 'reset' => false]);

Route::middleware('auth')->group(function () {
    Route::view('dashboard', 'dashboard')->name('home');
    Route::view('manajemen-user', 'manajemen-user')->name('admin.manajemen-user');
    Route::view('profil', 'profil')->name('profil');
});

// beranda
Route::view('/', 'user.beranda')->name('beranda');
