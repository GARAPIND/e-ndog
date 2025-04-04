<?php

use App\Http\Controllers\Admin\Data\CustomerController;
use App\Http\Controllers\Admin\Data\DashboardController;
use App\Http\Controllers\Admin\Data\KategoriProdukController;
use App\Http\Controllers\Admin\Data\ProdukController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin/customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/data', [CustomerController::class, 'getData'])->name('customers.data');
        Route::get('/{id}', [CustomerController::class, 'getCustomer'])->name('customers.get');
        Route::post('/', [CustomerController::class, 'store'])->name('customers.store');
        Route::put('/{id}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    });
    Route::prefix('admin/data')->group(function () {
        Route::get('/kategori', [KategoriProdukController::class, 'index'])->name('admin.data.kategori.index');
        Route::get('/kategori/data', [KategoriProdukController::class, 'getData'])->name('admin.data.kategori.data');
        Route::get('/kategori/{id}', [KategoriProdukController::class, 'getKategori']);
        Route::post('/kategori', [KategoriProdukController::class, 'store'])->name('admin.data.kategori.store');
        Route::put('/kategori/{id}', [KategoriProdukController::class, 'update']);
        Route::delete('/kategori/{id}', [KategoriProdukController::class, 'destroy']);

        Route::get('/produk', [ProdukController::class, 'index'])->name('admin.data.produk.index');
        Route::get('/produk/data', [ProdukController::class, 'getData'])->name('admin.data.produk.data');
        Route::get('/produk/{id}', [ProdukController::class, 'getProduk']);
        Route::post('/produk', [ProdukController::class, 'store'])->name('admin.data.produk.store');
        Route::put('/produk/{id}', [ProdukController::class, 'update']);
        Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);
    });
});
