<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BelanjaController extends Controller
{
    public function index()
    {
        return view('pengunjung.belanja.index');
    }
}
