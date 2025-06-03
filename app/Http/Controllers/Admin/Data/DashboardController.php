<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DetailTransaksi;
use App\Models\Kurir;
use App\Models\Produk;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pelanggan = Customer::count();
        $kurir = Kurir::count();
        $produk = Produk::sum('stok');
        $produk_terjual = DetailTransaksi::sum('jumlah');
        $pembelian = Customer::whereHas('transaksi')
            ->with(['user', 'transaksi.detail'])
            ->get();
        return view('admin.data.dashboard', compact('pelanggan', 'kurir', 'produk', 'produk_terjual', 'pembelian'));
    }
}
