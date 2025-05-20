<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ProfileToko;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class PesananController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function get_data_pesanan(Request $request)
    {
        $pelanggan = Customer::where('user_id', Auth::user()->id)->first();
        $data = Transaksi::with('alamat', 'pelanggan', 'detail.produk')
            ->where('pelanggan_id', $pelanggan->id)
            ->where('status_pengiriman', $request->status_pengiriman)->get();
        return response()->json($data);
    }

    public function bayar_ulang(Request $request)
    {
        $id = $request->id;
        $transaksi = Transaksi::With('pelanggan.user')->find($id);
        $kode_transaksi = "";

        if (!$transaksi->snap_token || $this->isSnapTokenExpired($transaksi->updated_at)) {
            $kode_transaksi = 'ORDER-' . rand();
            $params = [
                'transaction_details' => [
                    'order_id' => $kode_transaksi,
                    'gross_amount' => $transaksi->sub_total + $transaksi->ongkir,
                ],
                'customer_details' => [
                    'first_name' => $transaksi->pelanggan->user->name,
                    'email' => $transaksi->pelanggan->user->email,
                    'phone' => $transaksi->pelanggan->telp,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            $transaksi->kode_transaksi = $kode_transaksi;
            $transaksi->snap_token = $snapToken;
            $transaksi->save();
        } else {
            $kode_transaksi = $transaksi->kode_transaksi;
            $snapToken = $transaksi->snap_token;
        }
        return response()->json([
            'status' => 'success',
            'snap_token' => $snapToken,
            'order_id' => $kode_transaksi
        ]);
    }
    private function isSnapTokenExpired($updatedAt)
    {
        return now()->diffInMinutes($updatedAt) > 1440;
    }

    public function detail($id)
    {
        $data = Transaksi::with('alamat', 'pelanggan.user', 'detail.produk', 'kurir')->find($id);
        $profile = ProfileToko::find(1);
        return view('pengunjung.belanja.detail_pesanan', compact('data', 'profile'));
    }

    public function hapus_pesanan(Request $request)
    {
        $id = $request->id;
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json(['status' => 'error', 'message' => 'Pesanan tidak ditemukan'], 404);
        }

        $transaksi->delete();

        return response()->json(['status' => 'success', 'message' => 'Pesanan berhasil dihapus']);
    }
}
