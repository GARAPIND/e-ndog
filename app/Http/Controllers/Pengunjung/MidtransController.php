<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;

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
        $transactionDetails = [
            'order_id' => 'ORDER-' . rand(),
            'gross_amount' => 100000,
        ];

        $customerDetails = [
            'first_name' => "Budi",
            'last_name' => "Santoso",
            'email' => "budi.santoso@mail.com",
            'phone' => "+628123456789",
        ];

        $transaction = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($transaction);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function callback(Request $request)
    {
        $notif = new Notification();

        // Ambil data status transaksi dari Midtrans
        $transactionStatus = $notif->transaction_status;
        $orderId = $notif->order_id;
        $grossAmount = $notif->gross_amount;
        $paymentType = $notif->payment_type;

        Log::info('Transaksi Midtrans: ', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'gross_amount' => $grossAmount,
            'payment_type' => $paymentType,
        ]);

        // Kirimkan response sukses
        return response()->json(['status' => 'success']);
    }
}
