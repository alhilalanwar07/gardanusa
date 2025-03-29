<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\{Route, Auth};

// disable register, reset password
Auth::routes(['register' => false, 'reset' => false]);

Route::middleware('auth')->group(function () {
    Route::view('dashboard', 'dashboard')->name('home');
    Route::view('layanan', 'layanan')->name('admin.layanan');
    Route::view('produk', 'produk')->name('admin.produk');
    Route::view('klien', 'klien')->name('admin.klien');
    Route::view('tim', 'tim')->name('admin.tim');
    Route::view('blog', 'blog')->name('admin.blog');
    Route::view('portofolio', 'portofolio')->name('admin.portofolio');
    Route::view('testimoni', 'testimoni')->name('admin.testimoni');

    Route::view('manajemen-user', 'manajemen-user')->name('admin.manajemen-user');
    Route::view('profil', 'profil')->name('profil');

    Route::post('/image-upload', [ImageController::class, 'upload'])->name('image.upload');
});

// beranda
Route::view('/', 'user.beranda')->name('beranda');

