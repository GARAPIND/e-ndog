<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\ProfileToko;
use Illuminate\Http\Request;

class TentangKamiController extends Controller
{
    //
    public function index()
    {
        $profile = ProfileToko::first();
        return view('pengunjung.tentang-kami', compact('profile'));
    }
}
