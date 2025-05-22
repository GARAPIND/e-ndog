<?php

namespace App\Http\Controllers\Pengunjung;

use App\Helpers\SendWaHelper;
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
        if ($request->status_pengiriman == "cancel") {
            $data = Transaksi::with('alamat', 'pelanggan', 'detail.produk')
                ->where('pelanggan_id', $pelanggan->id)
                ->whereIn('cancel', [0, 1])->get();
        } else {
            $data = Transaksi::with('alamat', 'pelanggan', 'detail.produk')
                ->where('pelanggan_id', $pelanggan->id)
                ->where(function ($query) {
                    $query->whereNull('cancel')->orWhere('cancel', 2);
                })
                ->where('status_pengiriman', $request->status_pengiriman)
                ->get();
        }
        return response()->json($data);
    }

    public function bayar_ulang(Request $request)
    {
        $id = $request->id;
        $transaksi = Transaksi::With('pelanggan.user')->find($id);
        $kode_transaksi = "";

        if (!$transaksi->snap_token || $this->isSnapTokenExpired($transaksi->created_at)) {
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
    private function isSnapTokenExpired($created_at)
    {
        return now()->diffInMinutes($created_at) > 1440;
    }

    public function detail($id)
    {
        $data = Transaksi::with('alamat', 'pelanggan.user', 'detail.produk', 'kurir')->find($id);
        $profile = ProfileToko::find(1);
        return view('pengunjung.belanja.detail_pesanan', compact('data', 'profile'));
    }

    public function batal_pesanan(Request $request)
    {
        $id = $request->id;
        $alasan = $request->alasan;
        $transaksi = Transaksi::find($id);
        $transaksi->cancel = 0;
        $transaksi->catatan_cancel = $alasan;
        $transaksi->save();

        $sendWaHelper = new SendWaHelper();
        $sendWaHelper->sendCancelTransactionNotification($transaksi->id);

        return response()->json(['status' => 'success', 'message' => 'Pesanan berhasil dihapus']);
    }

    public function selesai_pesanan(Request $request)
    {
        $id = $request->id;
        $transaksi = Transaksi::find($id);
        $transaksi->status_pengiriman = 'Selesai';
        $transaksi->save();

        $sendWaHelper = new SendWaHelper();
        $sendWaHelper->sendOrderCompletedNotification($transaksi->id);

        return response()->json(['status' => 'success', 'message' => 'Pesanan berhasil diselesaikan']);
    }
}
