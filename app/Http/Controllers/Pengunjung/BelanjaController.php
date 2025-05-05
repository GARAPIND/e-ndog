<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BelanjaController extends Controller
{
    public function index()
    {
        $produk = Produk::where('aktif', 1)->get();
        return view('pengunjung.belanja.index', compact('produk'));
    }

    public function get_data_alamat_aktif()
    {
        $pelanggan = Customer::where('user_id', Auth::user()->id)->first();
        $data = Address::where('pelanggan_id', $pelanggan->id)->where('is_primary', 1)->first();
        return response()->json([
            'alamat' => $data->alamat . " ($data->keterangan)",
            'alamat_id' => $data->id
        ]);
    }

    public function get_data_alamat()
    {
        $pelanggan = Customer::where('user_id', Auth::user()->id)->first();
        $alamat = Address::where('pelanggan_id', $pelanggan->id)->get();
        return response()->json($alamat);
    }

    public function ganti_alamat(Request $request)
    {
        $alamat_id = $request->alamat_id;
        $pelanggan = Customer::where('user_id', Auth::user()->id)->value('id');

        Address::where('pelanggan_id', $pelanggan)->update(['is_primary' => 0]);
        Address::where('id', $alamat_id)->where('pelanggan_id', $pelanggan)->update(['is_primary' => 1]);

        return response()->json(['message' => 'Alamat berhasil diperbarui']);
    }
}
