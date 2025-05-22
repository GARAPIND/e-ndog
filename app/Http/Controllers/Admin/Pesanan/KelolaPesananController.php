<?php

namespace App\Http\Controllers\Admin\Pesanan;

use App\Helpers\SendWaHelper;
use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Kurir;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KelolaPesananController extends Controller
{
    public function index()
    {
        return view('admin.pesanan.kelola_pesanan');
    }

    public function getData(Request $request)
    {
        $query = Transaksi::with(['pelanggan.user'])->where(function ($query) {
            $query->whereNull('cancel')->orWhereIn('cancel', [0, 2]);
        });
        if ($request->status) {
            $query->where('status_pengiriman', $request->status);
        }

        return DataTables::of($query)
            ->addColumn('pelanggan', function ($transaksi) {
                return $transaksi->pelanggan ? $transaksi->pelanggan->user->name : '-';
            })
            ->addColumn('total', function ($transaksi) {
                return $transaksi->sub_total + $transaksi->ongkir;
            })
            ->make(true);
    }

    public function getPesanan($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'alamat', 'detail.produk'])
            ->findOrFail($id);
        return view('admin.pesanan.detail_pesanan', compact('transaksi'));
    }

    public function getTransaksiData($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'kurir_id' => $transaksi->kurir_id,
                'catatan_penjual' => $transaksi->catatan_penjual,
                'status_pengiriman' => $transaksi->status_pengiriman
            ]
        ]);
    }


    public function updateStatus(Request $request, $id)
    {
        $validationRules = [
            'status' => 'required|in:Dikemas,Dikirim,Selesai',
            'catatan_penjual' => 'nullable|string'
        ];
        $transaksi = Transaksi::with('detail')->findOrFail($id);

        if ($request->status === 'Dikirim' && $transaksi->is_cod == 1) {
            $validationRules['kurir_id'] = 'required|exists:kurir,id';
        }

        $request->validate($validationRules);

        $transaksi->status_pengiriman = $request->status;

        if ($request->has('catatan_penjual')) {
            $transaksi->catatan_penjual = $request->catatan_penjual;
        }

        if ($request->status === 'Dikirim' && $request->has('kurir_id')) {
            $transaksi->kurir_id = $request->kurir_id;
            $totalBerat = $transaksi->detail->sum('berat');
            $jarakMeter = $transaksi->jarak ?? 0;
            if ($jarakMeter < 1000) {
                $ongkirJarak = 2000;
            } else {
                $ongkirJarak = ($jarakMeter / 1000) * 2000;
            }
            if ($totalBerat < 5000) {
                $ongkirBerat = 1000;
            } else {
                $ongkirBerat = ($totalBerat / 5000) * 1000;
            }

            $totalOngkir = round($ongkirJarak + $ongkirBerat);
            $transaksi->ongkir = $totalOngkir;
        }

        $transaksi->save();

        if ($transaksi->status_pengiriman == 'Dikirim') {
            $sendWaHelper = new SendWaHelper();
            $sendWaHelper->sendOrderShippingNotification($transaksi->id, $transaksi->kurir_id);
        } else if ($transaksi->status_pengiriman == 'Selesai') {
            $sendWaHelper = new SendWaHelper();
            $sendWaHelper->sendOrderCompletedNotification($transaksi->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pengiriman berhasil diubah',
        ]);
    }


    // nyari kurir yang direkomendasikan
    public function getKurirRecommendations()
    {
        $kurir = Kurir::with('user')
            ->where('status', 'active')
            ->select('kurir.*')
            ->selectRaw('(SELECT COUNT(*) FROM transaksi WHERE kurir_id = kurir.id AND status_pengiriman = "Dikirim") as delivery_count')
            ->orderBy('delivery_count', 'asc')
            ->get();

        if ($kurir->count() > 0) {
            $minDeliveryCount = $kurir->min('delivery_count');

            foreach ($kurir as $k) {
                $k->is_recommended = ($k->delivery_count == $minDeliveryCount);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $kurir
        ]);
    }

    public function validasi_pembatalan(Request $request)
    {
        $transaksi = Transaksi::findOrFail($request->id);

        if ($request->aksi === 'tolak') {
            $transaksi->cancel = 2;
        } elseif ($request->aksi === 'setujui') {
            $transaksi->cancel = 1;
            foreach ($transaksi->detail as $item) {
                $produk = Produk::findOrFail($item->produk_id);
                $produk->tambahStok($item->jumlah, 'Pembatalan Pesanan #' . $transaksi->id);
            }
        }

        $transaksi->catatan_cancel_penjual = $request->catatan;
        $transaksi->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Validasi',
        ]);
    }
}
