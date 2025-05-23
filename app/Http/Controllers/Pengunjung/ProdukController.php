<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ProdukController extends Controller
{
    public function index()
    {
        return view('pengunjung.produk.index');
    }

    public function get_data(Request $request)
    {
        $perPage = 3;
        $filters = $request->filters;

        $query = Produk::where('aktif', 1);

        if (!empty($filters)) {
            $query->where(function ($query) use ($filters) {
                foreach ($filters as $filter) {
                    $priceRange = explode('-', $filter);

                    if (count($priceRange) === 2) {
                        $query->orWhereBetween('harga', [trim($priceRange[0]), trim($priceRange[1])])
                            ->orWhereBetween('harga_grosir', [trim($priceRange[0]), trim($priceRange[1])])
                            ->orWhereBetween('harga_pengampu', [trim($priceRange[0]), trim($priceRange[1])]);
                    } else {
                        $query->orWhere('harga', '>', trim($priceRange[0]))
                            ->orWhere('harga_grosir', '>', trim($priceRange[0]))
                            ->orWhere('harga_pengampu', '>', trim($priceRange[0]));
                    }
                }
            });
        }

        $data = $query->paginate($perPage);
        return response()->json([
            'data' => $data->items(),
            'current_page' => $data->currentPage(),
            'total_pages' => $data->lastPage(),
            'total_items' => $data->total(),
        ]);
    }

    public function detail_produk($id)
    {
        $data = Produk::with('kategori')->where('id', $id)->first();
        $all_produk = Produk::with('kategori')->where('aktif', 1)->where('id', '!=', $id)->inRandomOrder()->take(10)->get();
        return view('pengunjung.produk.detail', compact('data', 'all_produk'));
    }
}
