<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Kurir;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class BelanjaController extends Controller
{
    public function index()
    {
        $produk = Produk::where('aktif', 1)->get();
        $kurir = Kurir::with('user')->where('status', 'active')->get();
        return view('pengunjung.belanja.index', compact('produk', 'kurir'));
    }

    public function get_data_alamat_aktif()
    {
        $pelanggan = Customer::where('user_id', Auth::user()->id)->first();
        $data = Address::where('pelanggan_id', $pelanggan->id)->where('is_primary', 1)->first();
        if (!$data) {
            return response()->json(['status' => 'error']);
        }
        return response()->json([
            'alamat' => $data->alamat . " ($data->keterangan)",
            'alamat_id' => $data->id,
            'city_id' => $data->city_id,
            'latitude' => $data->latitude,
            'longitude' => $data->longitude
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

    public function cek_ongkir(Request $request)
    {
        $city_id = $request->city_id;
        $weight = $request->weight * 1000;
        $city_id_toko = 178;
        // tes update

        $couriers = ['jne', 'pos', 'tiki'];
        $allCosts = [];

        foreach ($couriers as $courier) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "origin=$city_id_toko&destination=$city_id&weight=$weight&courier=$courier",
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded",
                    "key: 2472843d6a402ff2319489c07cc5cf73"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                return response()->json(['error' => $err], 500);
            }

            $decoded = json_decode($response, true);

            if (isset($decoded['rajaongkir']['results'][0])) {
                $allCosts[$courier] = $decoded['rajaongkir']['results'][0]['costs'];
            }
        }

        return response()->json($allCosts);
    }

    public function sukses($orderId)
    {
        $kode_transaksi = $orderId;
        $data = Transaksi::with('alamat', 'pelanggan', 'detail.produk')->where('kode_transaksi', $kode_transaksi)->first();

        return view('pengunjung.belanja.sukses', compact('data'));
    }

    public function gagal($orderId)
    {
        $kode_transaksi = $orderId;
        $data = Transaksi::with('alamat', 'pelanggan', 'detail.produk')->where('kode_transaksi', $kode_transaksi)->first();

        return view('pengunjung.belanja.gagal', compact('data'));
    }

    public function pesanan(Request $request)
    {
        return view('pengunjung.belanja.pesanan');
    }
}
