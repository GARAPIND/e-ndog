<?php

namespace App\Http\Controllers\Admin\Pesanan;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Kurir;
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
        $query = Transaksi::with(['pelanggan.user']);
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

        if ($request->status === 'Dikirim') {
            $validationRules['kurir_id'] = 'required|exists:kurir,id';
        }

        $request->validate($validationRules);

        $transaksi = Transaksi::with('detail')->findOrFail($id);
        $transaksi->status_pengiriman = $request->status;

        if ($request->has('catatan_penjual')) {
            $transaksi->catatan_penjual = $request->catatan_penjual;
        }

        if ($request->status === 'Dikirim' && $request->has('kurir_id')) {
            $transaksi->kurir_id = $request->kurir_id;

            // Hitung total berat rpoduknya
            $totalBerat = $transaksi->detail->sum('berat');

            // Ambil jarak dari transaksi
            $jarakMeter = $transaksi->jarak ?? 0;

            // Hitung ongkir berdasarkan jarak dan berat
            if ($jarakMeter < 1000) {
                $ongkirJarak = 2000;
            } else {
                $ongkirJarak = ($jarakMeter / 1000) * 2000; // Rp 2.000 per km
            }

            if ($totalBerat < 5000) {
                $ongkirBerat = 1000; // Rp 1.000 per 5kg
            } else {
                $ongkirBerat = ($totalBerat / 5000) * 1000; // Rp 1.000 per 5kg
            }

            $totalOngkir = round($ongkirJarak + $ongkirBerat);
            $transaksi->ongkir = $totalOngkir;
        }

        $transaksi->save();

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
}
