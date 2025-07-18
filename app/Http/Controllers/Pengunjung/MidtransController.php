<?php

namespace App\Http\Controllers\Pengunjung;

use App\Helpers\SendWaHelper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
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

    public function createTransaction(Request $request)
    {
        $pelanggan = Customer::where('user_id', $request->users_id)->first();
        $kode_transaksi = 'ORDER-' . rand();
        $pelanggan_id = $pelanggan->id;
        $tanggal_transaksi = $request->tanggal_transaksi;
        $alamat_id = $request->alamat_id;
        $jarak = $request->jarak;
        $total_berat = $request->total_berat;
        $is_cod = $request->is_cod;
        $ekspedisi = $request->ekspedisi;
        $estimasi_waktu = $request->estimasi_waktu;
        if (strpos($estimasi_waktu, 'day') !== false) {
            $estimasi_waktu = str_replace('day', 'hari', $estimasi_waktu);
        }
        $sub_total = $request->sub_total;
        $ongkir = $request->ongkir;
        $catatan_pelanggan = $request->catatan_pelanggan;
        $barang_data = json_decode($request->barang_data, true);

        if (empty($barang_data)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada produk yang dibeli'
            ], 400);
        }

        if ($is_cod == 1) {
            DB::beginTransaction();
            try {
                if ($jarak < 1000) {
                    $ongkirJarak = 2000;
                } else {
                    $ongkirJarak = ($jarak / 1000) * 2000;
                }

                if ($total_berat < 5000) {
                    $ongkirBerat = 1000;
                } else {
                    $ongkirBerat = ($total_berat / 5000) * 1000;
                }
                $ongkir = round($ongkirJarak + $ongkirBerat);

                $data_transaksi = [
                    'kode_transaksi' => $kode_transaksi,
                    'tanggal_transaksi' => $tanggal_transaksi,
                    'pelanggan_id' => $pelanggan_id,
                    'alamat_id' => $alamat_id,
                    'status_pembayaran' => 'Menunggu Pembayaran',
                    'status_pengiriman' => 'Dikemas',
                    'jarak' => $jarak,
                    'is_cod' => $is_cod,
                    'ekspedisi' => $ekspedisi,
                    'estimasi_waktu' => $estimasi_waktu,
                    'sub_total' => $sub_total,
                    'ongkir' => $ongkir,
                    'catatan_pelanggan' => $catatan_pelanggan,
                ];
                $transaksi = Transaksi::create($data_transaksi);

                foreach ($barang_data as $barang) {
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $barang['barang_id'],
                        'status_harga' => $barang['status_harga'],
                        'jumlah' => $barang['jumlah'],
                        'berat' => $barang['berat'],
                        'sub_total' => $barang['sub_total']
                    ]);
                    $produk = Produk::findOrFail($barang['barang_id']);
                    $produk->kurangiStok($barang['jumlah'], 'Pembelian Online');
                }

                DB::commit();
                $sendWaHelper = new SendWaHelper();
                $sendWaHelper->sendOrderSuccessNotification($transaksi->id);

                return response()->json([
                    'status' => 'success',
                    'type' => 'COD',
                    'order_id' => $transaksi->kode_transaksi
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaksi gagal: ' . $e->getMessage()
                ], 500);
            }
        } else {
            if ($ekspedisi == null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pilih ekspedisi terlebih dahulu'
                ], 400);
            }

            DB::beginTransaction();

            try {
                // insert data ke MIDTRANS
                $transactionDetails = [
                    'order_id' => $kode_transaksi,
                    'gross_amount' => $sub_total + $ongkir,
                ];

                $customerDetails = [
                    'first_name' => Auth::user()->name,
                    'last_name' => '',
                    'email' => Auth::user()->email,
                    'phone' => $pelanggan->telp ?? '0',
                ];

                $transaction = [
                    'transaction_details' => $transactionDetails,
                    'customer_details' => $customerDetails,
                ];

                $snapToken = Snap::getSnapToken($transaction);

                /// insert data ke DATABASE APLIKASI
                $data_transaksi = [
                    'kode_transaksi' => $kode_transaksi,
                    'tanggal_transaksi' => $tanggal_transaksi,
                    'pelanggan_id' => $pelanggan_id,
                    'alamat_id' => $alamat_id,
                    'status_pembayaran' => 'Menunggu Pembayaran',
                    'status_pengiriman' => 'Menunggu Pembayaran',
                    'jarak' => $jarak,
                    'is_cod' => $is_cod,
                    'ekspedisi' => $ekspedisi,
                    'estimasi_waktu' => $estimasi_waktu,
                    'sub_total' => $sub_total,
                    'ongkir' => $ongkir,
                    'catatan_pelanggan' => $catatan_pelanggan,
                    'snap_token' => $snapToken,
                ];
                $transaksi = Transaksi::create($data_transaksi);

                foreach ($barang_data as $barang) {
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $barang['barang_id'],
                        'status_harga' => $barang['status_harga'],
                        'jumlah' => $barang['jumlah'],
                        'berat' => $barang['berat'],
                        'sub_total' => $barang['sub_total']
                    ]);
                    $produk = Produk::findOrFail($barang['barang_id']);
                    $produk->kurangiStok($barang['jumlah'], 'Pembelian Online');
                }

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'type' => 'ONLINE',
                    'order_id' => $kode_transaksi
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaksi gagal: ' . $e->getMessage()
                ], 500);
            }
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json([
                'message' => 'Invalid signature key',
                'expected' => $hashed,
                'received' => $request->signature_key
            ], 403);
        }

        $transaksi = Transaksi::where('kode_transaksi', $request->order_id)->first();

        if (!$transaksi) {
            return response()->json([
                'message' => 'Transaction not found',
                'order_id' => $request->order_id
            ], 404);
        }

        switch ($request->transaction_status) {
            case 'capture':
            case 'settlement':
                $transaksi->status_pembayaran = 'Sudah Dibayar';
                $transaksi->status_pengiriman = 'Dikemas';
                $sendWaHelper = new SendWaHelper();
                $sendWaHelper->sendOrderSuccessNotification($transaksi->id);
                break;
            case 'pending':
                $transaksi->status_pembayaran = 'Menunggu Pembayaran';
                $transaksi->status_pengiriman = 'Menunggu Pembayaran';
                break;
            case 'deny':
                $transaksi->status_pembayaran = 'Pembayaran Ditolak';
                $transaksi->status_pengiriman = 'Menunggu Pembayaran';
                break;
            case 'expire':
                $transaksi->status_pembayaran = 'Kadaluarsa';
                $transaksi->status_pengiriman = 'Menunggu Pembayaran';
                break;
            case 'cancel':
                $transaksi->status_pembayaran = 'Dibatalkan';
                $transaksi->status_pengiriman = 'Menunggu Pembayaran';
                break;
            default:
                $transaksi->status_pembayaran = 'Status Tidak Dikenal';
                $transaksi->status_pengiriman = 'Menunggu Pembayaran';
                break;
        }

        $transaksi->save();

        return response()->json([
            'message' => 'Callback processed successfully'
        ], 200);
    }
}
