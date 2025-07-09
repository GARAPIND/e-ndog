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
        $data = Transaksi::with('pelanggan.user')->where('status_pengiriman', 'Selesai')->orWhere('cancel', 1)->get();
        return DataTables::of($data)
            ->addColumn('nama_pelanggan', function ($transaksi) {
                if ($transaksi->is_onsite) {
                    return $transaksi->nama_pelanggan_onsite . ' <span class="badge badge-info">ONSITE</span>';
                }
                return $transaksi->pelanggan ? $transaksi->pelanggan->user->name : '-';
            })
            ->addColumn("tanggal_transaksi", function ($row) {
                return tanggalIndoLengkap($row->tanggal_transaksi);
            })
            ->addColumn("status", function ($row) {
                if ($row->cancel == 1) {
                    $catatan = '';
                    if (!empty($row->catatan_cancel)) {
                        $catatan .= '<br><small>Catatan: ' . e($row->catatan_cancel) . '</small>';
                    }
                    if (!empty($row->catatan_cancel_penjual)) {
                        $catatan .= '<br><small>Catatan Penjual: ' . e($row->catatan_cancel_penjual) . '</small>';
                    }
                    return '<span class="badge badge-danger">Dibatalkan</span>' . $catatan;
                } elseif ($row->status_pengiriman == 'Selesai') {
                    return '<span class="badge badge-success">Selesai</span>';
                }
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
            ->rawColumns(['aksi', 'foto', 'status', 'nama_pelanggan'])
            ->make(true);
    }
}
