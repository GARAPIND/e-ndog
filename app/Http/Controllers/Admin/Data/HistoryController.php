<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class HistoryController extends Controller
{
    public function index()
    {
        return view('admin.data.history');
    }

    public function get_data()
    {
        $data = Transaksi::with('pelanggan.user')->where('status_pengiriman', 'Selesai')->get();
        return DataTables::of($data)
            ->addColumn("nama_pelanggan", function ($row) {
                return $row->pelanggan->user->name;
            })
            ->addColumn("tanggal_transaksi", function ($row) {
                return tanggalIndoLengkap($row->tanggal_transaksi);
            })
            ->addColumn("total_belanja", function ($row) {
                $total = $row->sub_total + $row->ongkir;
                return 'Rp ' . number_format($total, 0, ',', '.');
            })
            ->addColumn("aksi", function ($row) {
                $url = route('admin.pesanan.detail', ['id' => $row->id]);

                $button = "<a href='$url' class='btn btn-warning btn-sm'>
                    <i class='fas fa-eye'></i> Detail
               </a>";

                return $button;
            })
            ->rawColumns(['aksi', 'foto'])
            ->make(true);
    }
}
