<?php

use App\Http\Controllers\Admin\Data\CustomerController;
use App\Http\Controllers\Admin\Data\DashboardController;
use App\Http\Controllers\Admin\Data\KategoriProdukController;
use App\Http\Controllers\Admin\Data\ProdukController;
use App\Http\Controllers\Admin\Data\StokController;
use App\Http\Controllers\Admin\Data\StokKeluarController;
use App\Http\Controllers\Admin\Data\StokMasukController;
use App\Http\Controllers\Admin\Tampilan\CarouselController;
use App\Http\Controllers\Admin\Tampilan\PromosiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Pengunjung\BelanjaController;
use App\Http\Controllers\Pengunjung\DashboardController as PengunjungDashboardController;
use App\Http\Controllers\Pengunjung\ProdukController as PengunjungProdukController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'admin'])->group(function () {
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
        Route::get('/produk/list', [ProdukController::class, 'getDataList'])->name('admin.data.produk.list');
        Route::get('/produk/{id}', [ProdukController::class, 'getProduk']);
        Route::post('/produk', [ProdukController::class, 'store'])->name('admin.data.produk.store');
        Route::put('/produk/{id}', [ProdukController::class, 'update']);
        Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);

        Route::get('/stok', [StokController::class, 'index'])->name('admin.data.stok.index');
        Route::get('/stok/data', [StokController::class, 'getStokData'])->name('admin.data.stok.data');
        Route::get('/stok/history/{id}', [StokController::class, 'getStokHistory']);
        Route::post('/stok/update', [StokController::class, 'updateStok'])->name('admin.data.stok.update');
        Route::get('/stok-masuk', [StokMasukController::class, 'index'])
            ->name('admin.stok-masuk.index');
        Route::get('/stok-masuk/data', [StokMasukController::class, 'getStokMasukData'])
            ->name('admin.stok-masuk.data');
        Route::get('/stok-masuk/detail/{id}', [StokMasukController::class, 'getStokMasukDetail']);
        Route::post('/stok-masuk/tambah', [StokMasukController::class, 'tambahStok'])
            ->name('admin.stok-masuk.tambah');
        Route::get('/stok-keluar', [StokKeluarController::class, 'index'])
            ->name('admin.stok-keluar.index');
        Route::get('/stok-keluar/data', [StokKeluarController::class, 'getStokKeluarData'])
            ->name('admin.stok-keluar.data');
        Route::get('/stok-keluar/detail/{id}', [StokKeluarController::class, 'getStokKeluarDetail']);
        Route::post('/stok-keluar/kurangi', [StokKeluarController::class, 'kurangiStok'])
            ->name('admin.stok-keluar.kurangi');

        Route::group(['prefix' => 'carousel', 'as' => 'carousel.'], function () {
            Route::get('/', [CarouselController::class, 'index'])->name('index');
            Route::get('/get_data', [CarouselController::class, 'get_data'])->name('get_data');
            Route::post('/get_data_id', [CarouselController::class, 'get_data_id'])->name('get_data_id');
            Route::post('/tambah_data', [CarouselController::class, 'tambah_data'])->name('tambah_data');
            Route::post('/edit_data', [CarouselController::class, 'edit_data'])->name('edit_data');
            Route::post('/hapus_data', [CarouselController::class, 'hapus_data'])->name('hapus_data');
        });

        Route::group(['prefix' => 'promosi', 'as' => 'promosi.'], function () {
            Route::get('/', [PromosiController::class, 'index'])->name('index');
            Route::get('/get_data', [PromosiController::class, 'get_data'])->name('get_data');
            Route::post('/get_data_id', [PromosiController::class, 'get_data_id'])->name('get_data_id');
            Route::post('/tambah_data', [PromosiController::class, 'tambah_data'])->name('tambah_data');
            Route::post('/edit_data', [PromosiController::class, 'edit_data'])->name('edit_data');
            Route::post('/hapus_data', [PromosiController::class, 'hapus_data'])->name('hapus_data');
        });
    });
});

// dashboard pengunjung
Route::middleware(['pelanggan'])->group(function () {
    Route::get('/', [PengunjungDashboardController::class, 'index'])->name('dashboard.pengunjung');

    Route::group(['prefix' => 'produk', 'as' => 'produk.'], function () {
        Route::get('/', [PengunjungProdukController::class, 'index'])->name('list');
        Route::get('/get_data', [PengunjungProdukController::class, 'get_data'])->name('get_data');
        Route::get('/detail/{id}', [PengunjungProdukController::class, 'detail_produk'])->name('detail');
    });

    Route::group(['prefix' => 'belanja', 'as' => 'belanja.'], function () {
        Route::get('/', [BelanjaController::class, 'index'])->name('list');
    });
});
