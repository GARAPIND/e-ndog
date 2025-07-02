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
        $origin = 31555; // ID kota asal (misal: ID toko)
        $destination = $request->city_id;
        $weight = $request->weight * 1000; // dari kg ke gram
        $couriers = 'jne:sicepat:ide:sap:jnt:ninja:tiki:lion:anteraja:pos:ncs:rex:rpx:sentral:star:wahana:dse';
        $price_type = 'lowest';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query([
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $couriers,
                'price' => $price_type
            ]),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded",
                "key: bfc73a5ac233d6ea88fb80d6b59baeab"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return response()->json(['error' => $err], 500);
        }

        $decoded = json_decode($response, true);

        if (isset($decoded['data']) && is_array($decoded['data'])) {
            return response()->json($decoded['data']); // hanya return list ongkir
        } else {
            return response()->json(['error' => 'Invalid response from API', 'raw' => $decoded], 500);
        }
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
