<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use App\Models\Produk;
use App\Models\Promosi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $produk = Produk::where('aktif', 1)->inRandomOrder()->take(4)->get();
        $carousel = Carousel::all();
        $promosi = Promosi::inRandomOrder()->take(4)->get();
        return view('pengunjung.index', compact('produk', 'carousel', 'promosi'));
    }
}
