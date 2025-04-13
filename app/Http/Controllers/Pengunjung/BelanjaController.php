<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class BelanjaController extends Controller
{
    public function index()
    {
        $produk = Produk::where('aktif', 1)->get();
        return view('pengunjung.belanja.index', compact('produk'));
    }
}
