<?php

namespace App\Helpers;

use App\Models\ProfileToko;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Log;

class SendWaHelper
{
    /**
     * Data list for common use
     * 
     * @return array
     */
    public function dataList()
    {
        return [
            'site_name' => config('app.name', 'E-NDOG'),
            'site_url' => config('app.url', 'https://endog.com'),
            'admin_phone' => env('ADMIN_PHONE', '6285158311928'),
        ];
    }

    /**
     * Send WhatsApp message
     * 
     * @param string $receiver
     * @param string $message
     * @return array
     */
    public function sendWa($receiver, $message)
    {
        try {
            $token = "MNBGY8gGdEyDaZYpCVdf";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $receiver,
                    'message' => $message,
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $token
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            return [
                'fonnte_id' => $result['id'][0] ?? null,
                'status' => $result['status'] ?? null,
                'process' => $result['process'] ?? null,
            ];
        } catch (\Throwable $th) {
            // Log error instead of dumping to screen
            Log::error('SendWa Error: ' . $th->getMessage());
            return [
                'fonnte_id' => null,
                'status' => 'error',
                'process' => $th->getMessage(),
            ];
        }
    }

    private function generateOrderItems($transaksi)
    {
        $items = "";
        $no = 1;
        foreach ($transaksi->detail as $item) {
            $harga = $item->produk->harga_diskon ?? $item->produk->harga;
            $subtotal = $item->jumlah *  $harga;
            $items .= "{$no}. {$item->produk->nama} ({$item->jumlah} x " . number_format($harga, 0, ',', '.') . ") = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
            $no++;
        }
        return $items;
    }

    public function sendOrderSuccessNotification($transaksiId)
    {
        try {
            $transaksi = Transaksi::with(['pelanggan.user', 'alamat', 'detail.produk'])
                ->findOrFail($transaksiId);

            $data = $this->dataList();
            $storeName = $data['site_name'];
            $customerName = $transaksi->pelanggan->user->name;
            $orderCode = $transaksi->kode_transaksi;
            $orderDate = Carbon::parse($transaksi->tanggal_transaksi)->format('d F Y H:i');
            $paymentStatus = $transaksi->status_pembayaran == 'paid' ? 'Lunas' : 'Belum Lunas';
            $paymentMethod = $transaksi->is_cod ? 'COD (Cash On Delivery)' : 'Transfer Bank';
            $orderItems = $this->generateOrderItems($transaksi);
            $subtotal = number_format($transaksi->sub_total, 0, ',', '.');
            $shipping = number_format($transaksi->ongkir, 0, ',', '.');
            $total = number_format($transaksi->sub_total + $transaksi->ongkir, 0, ',', '.');

            $message = "*PESANAN BERHASIL* ✅\n\n";
            $message .= "Halo {$customerName},\n\n";
            $message .= "Terima kasih telah berbelanja di {$storeName}. Pesanan Anda telah berhasil dibuat.\n\n";
            $message .= "*Detail Pesanan:*\n";
            $message .= "Kode Pesanan: *{$orderCode}*\n";
            $message .= "Tanggal: {$orderDate}\n";
            $message .= "Status Pembayaran: {$paymentStatus}\n";
            $message .= "Metode Pembayaran: {$paymentMethod}\n\n";
            $message .= "*Daftar Produk:*\n{$orderItems}\n";
            $message .= "Subtotal: Rp {$subtotal}\n";
            $message .= "Ongkir: Rp {$shipping}\n";
            $message .= "Total: Rp {$total}\n\n";

            if ($transaksi->status_pembayaran != 'paid' && !$transaksi->is_cod) {
                $message .= "Silakan lakukan pembayaran sesuai dengan instruksi yang telah diberikan.\n\n";
            }

            $message .= "Kami akan segera memproses pesanan Anda dan mengirimkannya secepatnya.\n";
            $message .= "Terima kasih telah berbelanja di {$storeName}! 🙏";

            return $this->sendWa($transaksi->pelanggan->telp, $message);
        } catch (\Throwable $th) {
            Log::error('SendOrderSuccessNotification Error: ' . $th->getMessage());
            return null;
        }
    }

    public function sendOrderShippingNotification($transaksiId, $kurirId)
    {
        try {
            $transaksi = Transaksi::with(['pelanggan.user', 'alamat', 'detail.produk', 'kurir.user'])
                ->where('id', $transaksiId)
                ->where('kurir_id', $kurirId)
                ->firstOrFail();

            $data = $this->dataList();
            $storeName = $data['site_name'];

            // Send notification to customer
            $customerName = $transaksi->pelanggan->user->name;
            $courierName = $transaksi->kurir->user->name ?? $transaksi->ekspedisi;
            $courierPhone = $transaksi->kurir->telp ?? '-';
            $orderCode = $transaksi->kode_transaksi;
            $customerAddress = $transaksi->alamat->alamat_lengkap;

            $customerMessage = "*PESANAN SEDANG DIKIRIM* 🚚\n\n";
            $customerMessage .= "Halo {$customerName},\n\n";
            $customerMessage .= "Pesanan Anda dengan kode *{$orderCode}* sedang dalam perjalanan!\n\n";
            $customerMessage .= "*Detail Pengiriman:*\n";
            $customerMessage .= "Nama Kurir: {$courierName}\n";
            $customerMessage .= "Kontak Kurir: {$courierPhone}\n";
            $customerMessage .= "Sedang dikirimkan ke alamat anda\n\n";

            if ($transaksi->is_cod) {
                $total = number_format($transaksi->sub_total + $transaksi->ongkir, 0, ',', '.');
                $customerMessage .= "Metode Pembayaran: COD (Cash On Delivery)\n";
                $customerMessage .= "Total Pembayaran: Rp {$total}\n\n";
                $customerMessage .= "Mohon siapkan uang pas untuk memudahkan proses pembayaran.\n\n";
            }

            $customerMessage .= "Kurir akan segera tiba di lokasi Anda. Jika ada pertanyaan, silakan hubungi kurir kami.\n\n";
            $customerMessage .= "Terima kasih telah berbelanja di {$storeName}! 🙏";

            $customerResult = $this->sendWa($transaksi->pelanggan->telp, $customerMessage);

            // Send notification to courier
            $customerPhone = $transaksi->pelanggan->telp;
            $orderItems = $this->generateOrderItems($transaksi);

            $courierMessage = "*TUGAS PENGIRIMAN BARU* 📦\n\n";
            $courierMessage .= "Halo {$courierName},\n\n";
            $courierMessage .= "Anda telah ditugaskan untuk mengirimkan pesanan berikut:\n\n";
            $courierMessage .= "*Detail Pesanan:*\n";
            $courierMessage .= "Kode Pesanan: *{$orderCode}*\n";
            $courierMessage .= "Nama Pelanggan: {$customerName}\n";
            $courierMessage .= "Kontak Pelanggan: {$customerPhone}\n";
            $courierMessage .= "Sedang dikirimkan ke alamat anda\n\n";
            $courierMessage .= "*Daftar Produk:*\n{$orderItems}\n";

            if ($transaksi->is_cod) {
                $total = number_format($transaksi->sub_total + $transaksi->ongkir, 0, ',', '.');
                $courierMessage .= "Metode Pembayaran: COD (Cash On Delivery)\n";
                $courierMessage .= "Total yang harus ditagih: Rp {$total}\n\n";
            }

            if ($transaksi->catatan_pelanggan) {
                $courierMessage .= "*Catatan Pelanggan:*\n{$transaksi->catatan_pelanggan}\n\n";
            }

            $courierMessage .= "Mohon segera lakukan pengiriman dan konfirmasi setelah pesanan diterima oleh pelanggan.\n";
            $courierMessage .= "Terima kasih! 🙏";

            if ($transaksi->is_cod == 1) {
                $courierResult = $this->sendWa($transaksi->kurir->telp, $courierMessage);
            } else {
                $courierResult = [];
            }

            return [
                'customer' => $customerResult,
                'courier' => $courierResult
            ];
        } catch (\Throwable $th) {
            dd($th);
            Log::error('SendOrderShippingNotification Error: ' . $th->getMessage());
            return [
                'customer' => null,
                'courier' => null
            ];
        }
    }

    /**
     * Send WhatsApp notification to customer when order is completed
     * 
     * @param int $transaksiId
     * @return array|null
     */
    public function sendOrderCompletedNotification($transaksiId)
    {
        try {
            $transaksi = Transaksi::with(['pelanggan.user', 'alamat', 'detail.produk'])
                ->findOrFail($transaksiId);

            $data = $this->dataList();
            $storeName = $data['site_name'];
            $siteUrl = $data['site_url'];
            $customerName = $transaksi->pelanggan->user->name;
            $orderCode = $transaksi->kode_transaksi;

            $message = "*PESANAN SELESAI* ✅\n\n";
            $message .= "Halo {$customerName},\n\n";
            $message .= "Selamat! Pesanan Anda dengan kode *{$orderCode}* telah selesai.\n\n";
            $message .= "Terima kasih telah berbelanja di {$storeName}. Kami harap Anda puas dengan produk dan layanan kami.\n\n";
            $message .= "Jika Anda memiliki pertanyaan atau masukan, jangan ragu untuk menghubungi kami.\n\n";
            $message .= "Semoga hari Anda menyenangkan! 😊\n\n";
            $message .= "Salam,\n";
            $message .= "Tim {$storeName}";

            return $this->sendWa($transaksi->pelanggan->telp, $message);
        } catch (\Throwable $th) {
            Log::error('SendOrderCompletedNotification Error: ' . $th->getMessage());
            return null;
        }
    }

    public function sendCancelTransactionNotification($transaksiId)
    {
        try {
            $transaksi = Transaksi::with(['pelanggan.user', 'alamat', 'detail.produk'])
                ->findOrFail($transaksiId);

            $customerName = $transaksi->pelanggan->user->name;
            $notesCancel = $transaksi->catatan_cancel;
            $orderCode = $transaksi->kode_transaksi;
            $profile_toko = ProfileToko::first();

            $message = "*PEMBATALAN PESANAN* ❌\n\n";
            $message .= "Telah terjadi pembatalan pesanan oleh pelanggan atas nama *{$customerName}*.\n\n";
            $message .= "Dengan alasan pembatalan yaitu {$notesCancel}.\n\n";
            $message .= "Kode pesanan: *{$orderCode}*\n\n";
            $message .= "Mohon untuk menindaklanjuti pembatalan ini sesuai dengan prosedur yang berlaku.\n\n";
            $message .= "Terima kasih atas perhatian dan kerjasamanya.\n\n";
            $message .= "Salam,\n";
            $message .= "Sistem {$profile_toko->nama_toko}";

            return $this->sendWa($profile_toko->whatsapp, $message);
        } catch (\Throwable $th) {
            Log::error('SendOrderCompletedNotification Error: ' . $th->getMessage());
            return null;
        }
    }

    public function sendAcceptCancelNotification($transaksiId)
    {
        try {
            $transaksi = Transaksi::with(['pelanggan.user', 'alamat', 'detail.produk'])
                ->findOrFail($transaksiId);

            $customerName = $transaksi->pelanggan->user->name;
            $notesCancel = $transaksi->catatan_cancel_penjual ?? '-';
            $orderCode = $transaksi->kode_transaksi;
            $profile_toko = ProfileToko::first();

            $message = "*PEMBATALAN PESANAN DISETUJUI* ✅\n\n";
            $message .= "Halo *{$customerName}*,\n\n";
            $message .= "Permintaan pembatalan pesanan Anda telah *disetujui* oleh tim kami.\n\n";
            $message .= "Catatan penjual: {$notesCancel}.\n\n";
            $message .= "Kode pesanan: *{$orderCode}*\n\n";
            $message .= "Kami mohon maaf atas ketidaknyamanan yang terjadi. Jika Anda memiliki pertanyaan lebih lanjut atau membutuhkan bantuan lainnya, jangan ragu untuk menghubungi kami.\n\n";
            $message .= "Terima kasih atas pengertiannya.\n\n";
            $message .= "Salam hangat,\n";
            $message .= "Tim {$profile_toko->nama_toko}";

            return $this->sendWa($transaksi->pelanggan->telp, $message);
        } catch (\Throwable $th) {
            Log::error('SendOrderCompletedNotification Error: ' . $th->getMessage());
            return null;
        }
    }

    public function sendDeclineCancelNotification($transaksiId)
    {
        try {
            $transaksi = Transaksi::with(['pelanggan.user', 'alamat', 'detail.produk'])
                ->findOrFail($transaksiId);

            $customerName = $transaksi->pelanggan->user->name;
            $notesCancel = $transaksi->catatan_cancel_penjual ?? '-';
            $orderCode = $transaksi->kode_transaksi;
            $profile_toko = ProfileToko::first();

            $message = "*PEMBATALAN PESANAN DITOLAK* ❌\n\n";
            $message .= "Halo *{$customerName}*,\n\n";
            $message .= "Mohon maaf, permintaan pembatalan pesanan Anda dengan kode *{$orderCode}* tidak dapat kami setujui.\n\n";
            $message .= "Alasan ditolak: {$notesCancel}.\n\n";
            $message .= "Pesanan Anda saat ini masih dalam proses dan tidak dapat dibatalkan sesuai dengan kebijakan yang berlaku.\n\n";
            $message .= "Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi layanan pelanggan kami untuk informasi lebih lanjut.\n\n";
            $message .= "Terima kasih atas pengertian dan kepercayaannya.\n\n";
            $message .= "Salam hangat,\n";
            $message .= "Tim {$profile_toko->nama_toko}";


            return $this->sendWa($transaksi->pelanggan->telp, $message);
        } catch (\Throwable $th) {
            Log::error('SendOrderCompletedNotification Error: ' . $th->getMessage());
            return null;
        }
    }
}
