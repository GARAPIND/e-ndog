<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\StokKeluar;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class StokKeluarController extends Controller
{
    public function index()
    {
        return view('admin.data.stok-keluar');
    }

    public function getStokKeluarData(Request $request)
    {
        $histories = StokKeluar::with(['produk', 'user'])
            ->select('stok_keluar.*');

        return DataTables::of($histories)
            ->addColumn('nama_produk', function ($row) {
                return $row->produk ? $row->produk->kode . ' - ' . $row->produk->nama : 'Produk tidak ditemukan';
            })
            ->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : 'Sistem';
            })
            ->addColumn('tanggal', function ($row) {
                return $row->created_at->format('d-m-Y H:i:s');
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" class="btn btn-info btn-sm detail-btn" data-id="' . $row->id . '">
                        <i class="fas fa-eye"></i> Detail
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getStokKeluarDetail($id)
    {
        $history = StokKeluar::with(['produk', 'user'])->findOrFail($id);
        // dd($history);
        if ($history->tipe != 'keluar') {
            return response()->json(['error' => 'Data tidak valid'], 400);
        }

        return response()->json([
            'history' => $history,
            'produk' => $history->produk,
            'user' => $history->user
        ]);
    }

    public function kurangiStok(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'produk_id' => 'required|exists:produk,id',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $produk = Produk::findOrFail($request->produk_id);
            $keterangan = $request->keterangan ?? null;
            if ($produk->stok < $request->jumlah) {
                DB::rollBack();
                return response()->json(['error' => 'Stok tidak mencukupi.'], 400);
            }

            $result = $produk->kurangiStok($request->jumlah, $keterangan);

            if (!$result) {
                DB::rollBack();
                return response()->json(['error' => 'Gagal mengurangi stok.'], 400);
            }

            DB::commit();
            return response()->json(['success' => 'Stok berhasil dikurangi']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
