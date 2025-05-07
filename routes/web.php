<?php

use App\Http\Controllers\Admin\Data\CustomerController;
use App\Http\Controllers\Admin\Data\DashboardController;
use App\Http\Controllers\Admin\Data\KategoriProdukController;
use App\Http\Controllers\Admin\Data\KurirController;
use App\Http\Controllers\Admin\Data\ProdukController;
use App\Http\Controllers\Admin\Data\ProfileTokoController;
use App\Http\Controllers\Admin\Data\StokController;
use App\Http\Controllers\Admin\Data\StokKeluarController;
use App\Http\Controllers\Admin\Data\StokMasukController;
use App\Http\Controllers\Admin\Pesanan\KelolaPesananController;
use App\Http\Controllers\Admin\Tampilan\CarouselController;
use App\Http\Controllers\Admin\Tampilan\PromosiController;
use App\Http\Controllers\Api\RajaOngkirController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Pengunjung\BelanjaController;
use App\Http\Controllers\Pengunjung\DashboardController as PengunjungDashboardController;
use App\Http\Controllers\Pengunjung\MidtransController;
use App\Http\Controllers\Pengunjung\ProdukController as PengunjungProdukController;
use App\Http\Controllers\Pengunjung\ProfileController;
use App\Http\Controllers\Pengunjung\TentangKamiController;
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

        Route::prefix('kategori')->name('admin.kategori.')->group(function () {
            Route::get('/', [KategoriProdukController::class, 'index'])->name('index');
            Route::get('/data', [KategoriProdukController::class, 'getData'])->name('data');
            Route::get('/{id}', [KategoriProdukController::class, 'getKategori']);
            Route::post('/', [KategoriProdukController::class, 'store'])->name('store');
            Route::put('/{id}', [KategoriProdukController::class, 'update'])->name('update');
            Route::delete('/{id}', [KategoriProdukController::class, 'destroy'])->name('destroy');
        });
        // Profile Toko
        Route::prefix('profile-toko')->name('admin.profile-toko.')->group(function () {
            Route::get('profile-toko', [ProfileTokoController::class, 'index'])->name('index');
            Route::post('profile-toko', [ProfileTokoController::class, 'update'])->name('update');
        });

        // Produk
        Route::prefix('produk')->name('admin.produk.')->group(function () {
            Route::get('/', [ProdukController::class, 'index'])->name('index');
            Route::get('/data', [ProdukController::class, 'getData'])->name('data');
            Route::get('/list', [ProdukController::class, 'getDataList'])->name('list');
            Route::get('/{id}', [ProdukController::class, 'getProduk']);
            Route::post('/', [ProdukController::class, 'store'])->name('store');
            Route::put('/{id}', [ProdukController::class, 'update'])->name('update');
            Route::delete('/{id}', [ProdukController::class, 'destroy'])->name('destroy');
        });

        // Stok
        Route::prefix('stok')->name('admin.stok.')->group(function () {
            Route::get('/', [StokController::class, 'index'])->name('index');
            Route::get('/data', [StokController::class, 'getStokData'])->name('data');
            Route::get('/history/{id}', [StokController::class, 'getStokHistory'])->name('history');
            Route::post('/update', [StokController::class, 'updateStok'])->name('update');
        });

        // Stok Masuk
        Route::prefix('stok-masuk')->name('admin.stok-masuk.')->group(function () {
            Route::get('/', [StokMasukController::class, 'index'])->name('index');
            Route::get('/data', [StokMasukController::class, 'getStokMasukData'])->name('data');
            Route::get('/detail/{id}', [StokMasukController::class, 'getStokMasukDetail'])->name('detail');
            Route::post('/tambah', [StokMasukController::class, 'tambahStok'])->name('tambah');
        });

        // Stok Keluar
        Route::prefix('stok-keluar')->name('admin.stok-keluar.')->group(function () {
            Route::get('/', [StokKeluarController::class, 'index'])->name('index');
            Route::get('/data', [StokKeluarController::class, 'getStokKeluarData'])->name('data');
            Route::get('/detail/{id}', [StokKeluarController::class, 'getStokKeluarDetail'])->name('detail');
            Route::post('/kurangi', [StokKeluarController::class, 'kurangiStok'])->name('kurangi');
        });

        // Kurir
        Route::prefix('kurir')->name('admin.kurir.')->group(function () {
            Route::get('/', [KurirController::class, 'index'])->name('index');
            Route::get('/data', [KurirController::class, 'getData'])->name('data');
            Route::get('/{id}', [KurirController::class, 'getKurir']);
            Route::post('/', [KurirController::class, 'store'])->name('store');
            Route::put('/{id}', [KurirController::class, 'update'])->name('update');
            Route::delete('/{id}', [KurirController::class, 'destroy'])->name('destroy');
        });
        // Pesanan

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
    Route::prefix('admin/pesanan')->name('admin.pesanan.')->group(function () {
        Route::get('/', [KelolaPesananController::class, 'index'])->name('index');
        Route::get('/data', [KelolaPesananController::class, 'getData'])->name('data');
        Route::get('/{id}', [KelolaPesananController::class, 'getPesanan'])->name('detail');
        Route::post('/{id}/update-status', [KelolaPesananController::class, 'updateStatus'])->name('update-status');
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
        Route::get('/get_data_alamat_aktif', [BelanjaController::class, 'get_data_alamat_aktif'])->name('get_data_alamat_aktif');
        Route::get('/get_data_alamat', [BelanjaController::class, 'get_data_alamat'])->name('get_data_alamat');
        Route::post('/ganti_alamat', [BelanjaController::class, 'ganti_alamat'])->name('ganti_alamat');
        Route::post('/cek_ongkir', [BelanjaController::class, 'cek_ongkir'])->name('cek_ongkir');
        Route::get('/sukses/{orderId}', [BelanjaController::class, 'sukses'])->name('sukses');
        Route::get('/pesanan', [BelanjaController::class, 'pesanan'])->name('pesanan');

        Route::post('/create-transaction', [MidtransController::class, 'createTransaction'])->name('createTransaction');
    });
    Route::get('/tentang-kami', [TentangKamiController::class, 'index'])->name('tentang-kami');

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', [ProfileController::class, 'profile'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');

        Route::get('/addresses', [ProfileController::class, 'addresses'])->name('addresses');
        Route::get('/addresses/add', [ProfileController::class, 'addAddress'])->name('addresses.add');
        Route::post('/addresses/store', [ProfileController::class, 'storeAddress'])->name('addresses.store');
        Route::get('/addresses/edit/{id}', [ProfileController::class, 'editAddress'])->name('addresses.edit');
        Route::post('/addresses/update/{id}', [ProfileController::class, 'updateAddress'])->name('addresses.update');
        Route::post('/addresses/set-primary/{id}', [ProfileController::class, 'setPrimaryAddress'])->name('addresses.set-primary');
        Route::delete('/addresses/delete/{id}', [ProfileController::class, 'destroyAddress'])->name('addresses.delete');
        Route::get('/get-addresses', [ProfileController::class, 'getAddresses'])->name('get-addresses');
    });
});
Route::prefix('api/rajaongkir')->group(function () {
    Route::get('/provinces', [RajaOngkirController::class, 'getProvinces'])->name('rajaongkir.provinces');
    Route::get('/cities', [RajaOngkirController::class, 'getCities'])->name('rajaongkir.cities');
    Route::get('/districts', [RajaOngkirController::class, 'getDistricts'])->name('rajaongkir.districts');
    Route::post('/cost', [RajaOngkirController::class, 'getCost'])->name('rajaongkir.cost');
    Route::get('/origin', [RajaOngkirController::class, 'getOriginCity'])->name('rajaongkir.origin');
});
// Raja Ongkir API Routes
Route::middleware(['auth'])->group(function () {});

Route::post('/midtrans-callback', [MidtransController::class, 'callback'])->name('callback');
