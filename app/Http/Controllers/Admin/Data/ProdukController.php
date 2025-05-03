<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\KategoriProduk;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function index()
    {
        // $kategoriList = KategoriProduk::where('aktif', true)->get();
        // return view('admin.data.produk', compact('kategoriList'));
        return view('admin.data.produk');
    }

    public function getData(Request $request)
    {
        $produk = Produk::
            // with('kategori')->
            select([
                'produk.id',
                'produk.nama',
                'produk.kode',
                'produk.harga',
                'produk.harga_diskon',
                'produk.stok',
                'produk.berat',
                'produk.satuan',
                'produk.foto',
                'produk.aktif',
                // 'produk.kategori_id',
                'produk.created_at'
            ]);

        return DataTables::of($produk)
            ->addColumn('foto', function ($row) {
                if ($row->foto) {
                    return '<img src="' . asset('storage/foto-produk/' . $row->foto) . '" alt="Foto Produk" class="img-thumbnail" style="max-height: 50px;">';
                }
                return '<span class="badge badge-secondary">Tidak Ada Foto</span>';
            })
            // ->addColumn('kategori', function ($row) {
            //     return $row->kategori ? $row->kategori->nama : 'Tidak Terkategori';
            // })
            ->addColumn('harga_format', function ($row) {
                $harga = 'Rp ' . number_format($row->harga, 0, ',', '.');
                if ($row->harga_diskon) {
                    $harga = '<span class="text-decoration-line-through text-muted">' . $harga . '</span><br>';
                    $harga .= 'Rp ' . number_format($row->harga_diskon, 0, ',', '.');
                }
                return $harga;
            })
            ->addColumn('status', function ($row) {
                $badge = $row->aktif ? 'badge-success' : 'badge-danger';
                $text = $row->aktif ? 'Aktif' : 'Tidak Aktif';
                return '<span class="badge ' . $badge . '">' . $text . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" class="btn btn-info btn-sm view-btn" data-id="' . $row->id . '">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button type="button" class="btn btn-primary btn-sm edit-btn" data-id="' . $row->id . '">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['foto', 'harga_format', 'status', 'action'])
            ->make(true);
    }

    public function getProduk($id)
    {
        $produk = Produk::findOrFail($id);
        return response()->json($produk);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:produk',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'harga_diskon' => 'nullable|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:20',
            // 'kategori_id' => 'nullable|exists:kategori_produk,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoName = 'produk_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->storeAs('foto-produk', $fotoName, 'public');
                $data['foto'] = $fotoName;
            }

            $data['aktif'] = $request->has('aktif');
            $produk = Produk::create($data);

            DB::commit();

            return response()->json(['success' => 'Produk berhasil ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menambahkan produk: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:produk,kode,' . $id,
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'harga_diskon' => 'nullable|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:20',
            // 'kategori_id' => 'nullable|exists:kategori_produk,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            if ($request->hasFile('foto')) {
                if ($produk->foto) {
                    Storage::disk('public')->delete('foto-produk/' . $produk->foto);
                }

                $foto = $request->file('foto');
                $fotoName = 'produk_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->storeAs('foto-produk', $fotoName, 'public');
                $data['foto'] = $fotoName;
            }

            $data['aktif'] = $request->has('aktif');
            $produk->update($data);

            DB::commit();

            return response()->json(['success' => 'Produk berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal memperbarui produk: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        try {
            if ($produk->foto) {
                Storage::disk('public')->delete('foto-produk/' . $produk->foto);
            }
            $produk->delete();

            return response()->json(['success' => 'Produk berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus produk: ' . $e->getMessage()], 500);
        }
    }

    // Modified ProdukController method
    public function getDataList()
    {
        try {
            $products = Produk::where('aktif', 1)
                ->select(['id', 'kode', 'nama', 'stok'])
                ->orderBy('nama')
                ->get();

            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load products'], 500);
        }
    }
}
