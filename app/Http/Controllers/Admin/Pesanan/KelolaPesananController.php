<?php

namespace App\Http\Controllers\Admin\Pesanan;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
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
                // dd($transaksi->pelanggan);
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
        // dd($transaksi);
        return view('admin.pesanan.detail_pesanan', compact('transaksi'));
    }

    public function updateStatus(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'status' => 'required|in:Dikemas,Dikirim,Selesai',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status_pengiriman = $request->status;
        $transaksi->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pengiriman berhasil diubah',
        ]);
    }
}
