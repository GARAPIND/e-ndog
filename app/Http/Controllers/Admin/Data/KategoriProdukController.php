<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriProduk;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class KategoriProdukController extends Controller
{
    public function index()
    {
        return view('admin.data.kategori-produk');
    }

    public function getData(Request $request)
    {
        $kategoriProduk = KategoriProduk::select([
            'id',
            'nama',
            'slug',
            'deskripsi',
            'aktif',
            'created_at'
        ]);

        return DataTables::of($kategoriProduk)
            ->addColumn('status', function ($row) {
                $badge = $row->aktif ? 'badge-success' : 'badge-danger';
                $text = $row->aktif ? 'Aktif' : 'Tidak Aktif';
                return '<span class="badge ' . $badge . '">' . $text . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" class="btn btn-primary btn-sm edit-btn" data-id="' . $row->id . '">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function getKategori($id)
    {
        $kategori = KategoriProduk::findOrFail($id);
        return response()->json($kategori);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:kategori_produk',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $data = $request->all();
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['nama']);
            }

            $data['aktif'] = $request->has('aktif') ? true : false;
            KategoriProduk::create($data);

            DB::commit();

            return response()->json(['success' => 'Kategori produk berhasil ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menambahkan kategori produk: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriProduk::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:kategori_produk,slug,' . $id,
            'deskripsi' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $data = $request->all();
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['nama']);
            }

            $data['aktif'] = $request->has('aktif') ? true : false;
            $kategori->update($data);

            DB::commit();

            return response()->json(['success' => 'Kategori produk berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal memperbarui kategori produk: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $kategori = KategoriProduk::findOrFail($id);

        try {
            $produkCount = $kategori->produk()->count();
            if ($produkCount > 0) {
                return response()->json(['error' => 'Kategori ini digunakan oleh ' . $produkCount . ' produk. Hapus atau pindahkan produk terlebih dahulu.'], 422);
            }

            $kategori->delete();

            return response()->json(['success' => 'Kategori produk berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus kategori produk: ' . $e->getMessage()], 500);
        }
    }
}
