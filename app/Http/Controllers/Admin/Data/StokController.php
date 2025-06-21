<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\StokKeluar;
use App\Models\StokMasuk;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index()
    {
        return view('admin.data.stok');
    }

    public function getStokData(Request $request)
    {
        $produk = Produk::with('kategori')->select('produk.*');

        return DataTables::of($produk)
            ->addColumn('nama_lengkap', function ($row) {
                return $row->kode . ' - ' . $row->nama;
            })
            ->addColumn('kategori', function ($row) {
                return $row->kategori ? $row->kategori->nama : 'Tidak Terkategori';
            })
            ->addColumn('status_stok', function ($row) {
                if ($row->stok <= 0) {
                    return '<span class="badge badge-danger">Habis</span>';
                } elseif ($row->stok <= $row->stok_minimum) {
                    return '<span class="badge badge-warning">Minimum</span>';
                } else {
                    return '<span class="badge badge-success">Tersedia</span>';
                }
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" class="btn btn-success btn-sm tambah-stok-btn" data-id="' . $row->id . '" data-nama="' . $row->nama . '">
                        <i class="fas fa-plus-circle"></i> Tambah
                    </button>
                    <button type="button" class="btn btn-warning btn-sm kurang-stok-btn" data-id="' . $row->id . '" data-nama="' . $row->nama . '">
                        <i class="fas fa-minus-circle"></i> Kurang
                    </button>
                    <button type="button" class="btn btn-info btn-sm sesuai-stok-btn" data-id="' . $row->id . '" data-nama="' . $row->nama . '">
                        <i class="fas fa-sync-alt"></i> Sesuaikan
                    </button>
                    <button type="button" class="btn btn-primary btn-sm history-stok-btn" data-id="' . $row->id . '" data-nama="' . $row->nama . '">
                        <i class="fas fa-history"></i> Riwayat
                    </button>
                ';
            })
            ->rawColumns(['status_stok', 'action'])
            ->make(true);
    }

    public function getStokHistory($id)
    {
        $produk = Produk::findOrFail($id);
        $stokMasuk = StokMasuk::where('produk_id', $id)
            ->with('user')
            ->get()
            ->map(function ($item) {
                $item->direction = 'masuk';
                return $item;
            });

        $stokKeluar = StokKeluar::where('produk_id', $id)
            ->with('user')
            ->get()
            ->map(function ($item) {
                $item->direction = 'keluar';
                return $item;
            });

        // Gabungkan dan urutkan berdasarkan tanggal
        $histories = $stokMasuk->concat($stokKeluar)->sortByDesc('created_at')->values();

        return response()->json([
            'produk' => $produk,
            'histories' => $histories
        ]);
    }

    public function updateStok(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'produk_id' => 'required|exists:produk,id',
            'tipe' => 'required|in:tambah,kurang,sesuai',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $produk = Produk::findOrFail($request->produk_id);
            $keterangan = $request->keterangan ?? null;
            $result = false;

            switch ($request->tipe) {
                case 'tambah':
                    $result = $produk->tambahStok($request->jumlah, $keterangan);
                    break;
                case 'kurang':
                    $result = $produk->kurangiStok($request->jumlah, $keterangan);
                    break;
                case 'sesuai':
                    $result = $produk->sesuaikanStok($request->jumlah, $keterangan);
                    break;
            }

            if (!$result) {
                DB::rollBack();
                return response()->json(['error' => 'Gagal mengubah stok. Pastikan stok tidak menjadi negatif.'], 400);
            }

            DB::commit();
            return response()->json(['success' => 'Stok berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
