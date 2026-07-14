<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\UmkmRegistrationController;
use App\Http\Controllers\WilayahOptionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::livewire('/katalog', 'catalog-search')->name('catalog.index');
Route::livewire('/kategori/{category:slug}', 'catalog-search')->name('categories.show');
Route::livewire('/favorit', 'favorites-page')->name('favorites.index');

Route::get('/daftar-umkm', [UmkmRegistrationController::class, 'create'])->name('umkm.register');
Route::post('/daftar-umkm', [UmkmRegistrationController::class, 'store'])->name('umkm.register.store');

Route::get('/wilayah/regencies/{province}', [WilayahOptionsController::class, 'regencies'])->name('wilayah.regencies');
Route::get('/wilayah/districts/{regency}', [WilayahOptionsController::class, 'districts'])->name('wilayah.districts');
Route::get('/wilayah/villages/{district}', [WilayahOptionsController::class, 'villages'])->name('wilayah.villages');

Route::get('/cara-kerja', [StaticPageController::class, 'about'])->name('about');
Route::get('/kontak', [StaticPageController::class, 'contact'])->name('contact');
Route::get('/profil', [StaticPageController::class, 'profile'])->name('profile.index');

Route::get('/toko/{umkm:slug}/produk/{product:slug}', [ProductController::class, 'show'])
    ->name('products.show')
    ->scopeBindings();

Route::get('/toko/{umkm:slug}', [UmkmController::class, 'show'])->name('umkm.show');
