<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class PesananController extends Controller
{
    public function get_data_pesanan(Request $request)
    {
        $pelanggan = Customer::where('user_id', Auth::user()->id)->first();
        $data = Transaksi::with('alamat', 'pelanggan', 'detail.produk')
            ->where('pelanggan_id', $pelanggan->id)
            ->where('status_pengiriman', $request->status_pengiriman)->get();
        return response()->json($data);
    }
}
