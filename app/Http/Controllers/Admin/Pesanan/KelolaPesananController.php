<?php

namespace App\Http\Controllers\Admin\Pesanan;

use App\Helpers\SendWaHelper;
use App\Http\Controllers\Controller;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\Kurir;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KelolaPesananController extends Controller
{
    public function index()
    {
        return view('admin.pesanan.kelola_pesanan');
    }

    public function getData(Request $request)
    {
        $query = Transaksi::with(['pelanggan.user'])->where(function ($query) {
            $query->whereNull('cancel')->orWhereIn('cancel', [0, 2]);
        });

        if ($request->status) {
            $query->where('status_pengiriman', $request->status);
        }

        return DataTables::of($query)
            ->addColumn('pelanggan', function ($transaksi) {
                if ($transaksi->is_onsite) {
                    return $transaksi->nama_pelanggan_onsite . ' <span class="badge badge-info">ONSITE</span>';
                }
                return $transaksi->pelanggan ? $transaksi->pelanggan->user->name : '-';
            })
            ->addColumn('total', function ($transaksi) {
                return $transaksi->sub_total + $transaksi->ongkir;
            })
            ->addColumn('cod_badge', function ($transaksi) {
                if ($transaksi->is_onsite) {
                    return '<span class="badge badge-success">ONSITE</span>';
                }
                return $transaksi->is_cod ? '<span class="badge badge-warning">COD</span>' : '<span class="badge badge-info">Transfer</span>';
            })
            ->rawColumns(['pelanggan', 'cod_badge'])
            ->make(true);
    }

    public function getPesanan($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'alamat', 'detail.produk'])
            ->findOrFail($id);
        $transaksi_id = $id;
        return view('admin.pesanan.detail_pesanan', compact('transaksi', 'transaksi_id'));
    }

    public function getTransaksiData($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'kurir_id' => $transaksi->kurir_id,
                'catatan_penjual' => $transaksi->catatan_penjual,
                'status_pengiriman' => $transaksi->status_pengiriman
            ]
        ]);
    }


    public function updateStatus(Request $request, $id)
    {
        $validationRules = [
            'status' => 'required|in:Dikemas,Dikirim,Selesai',
            'catatan_penjual' => 'nullable|string'
        ];
        $transaksi = Transaksi::with('detail')->findOrFail($id);

        if ($request->status === 'Dikirim' && $transaksi->is_cod == 1) {
            $validationRules['kurir_id'] = 'required|exists:kurir,id';
            $validationRules['estimasi_waktu'] = 'required|string';
        }

        $request->validate($validationRules);

        $transaksi->status_pengiriman = $request->status;

        if ($request->has('catatan_penjual')) {
            $transaksi->catatan_penjual = $request->catatan_penjual;
        }

        if ($request->status === 'Dikirim' && $request->has('kurir_id')) {
            $transaksi->kurir_id = $request->kurir_id;
            $transaksi->estimasi_waktu = $request->estimasi_waktu;
            $totalBerat = $transaksi->detail->sum('berat');
            $jarakMeter = $transaksi->jarak ?? 0;
            if ($jarakMeter < 1000) {
                $ongkirJarak = 2000;
            } else {
                $ongkirJarak = ($jarakMeter / 1000) * 2000;
            }
            if ($totalBerat < 5000) {
                $ongkirBerat = 1000;
            } else {
                $ongkirBerat = ($totalBerat / 5000) * 1000;
            }

            $totalOngkir = round($ongkirJarak + $ongkirBerat);
            $transaksi->status_pembayaran = 'Menunggu Pembayaran';

            $transaksi->ongkir = $totalOngkir;
        } else {
            $transaksi->status_pembayaran = 'Sudah Dibayar';
        }

        $transaksi->save();

        if ($transaksi->status_pengiriman == 'Dikirim') {
            $sendWaHelper = new SendWaHelper();
            $sendWaHelper->sendOrderShippingNotification($transaksi->id, $transaksi->kurir_id);
        } else if ($transaksi->status_pengiriman == 'Selesai') {
            $sendWaHelper = new SendWaHelper();
            $sendWaHelper->sendOrderCompletedNotification($transaksi->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pengiriman berhasil diubah',
        ]);
    }


    // nyari kurir yang direkomendasikan
    public function getKurirRecommendations()
    {
        $kurir = Kurir::with('user')
            ->where('status', 'active')
            ->select('kurir.*')
            ->selectRaw('(SELECT COUNT(*) FROM transaksi WHERE kurir_id = kurir.id AND status_pengiriman = "Dikirim") as delivery_count')
            ->orderBy('delivery_count', 'asc')
            ->get();

        if ($kurir->count() > 0) {
            $minDeliveryCount = $kurir->min('delivery_count');

            foreach ($kurir as $k) {
                $k->is_recommended = ($k->delivery_count == $minDeliveryCount);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $kurir
        ]);
    }

    public function validasi_pembatalan(Request $request)
    {
        $transaksi = Transaksi::findOrFail($request->id);

        if ($request->aksi === 'tolak') {
            $transaksi->cancel = 2;
            $transaksi->catatan_cancel_penjual = $request->catatan;
            $transaksi->save();
            $sendWaHelper = new SendWaHelper();
            $sendWaHelper->sendDeclineCancelNotification($transaksi->id);
        } elseif ($request->aksi === 'setujui') {
            $transaksi->cancel = 1;
            $transaksi->catatan_cancel_penjual = $request->catatan;
            $transaksi->save();
            $sendWaHelper = new SendWaHelper();
            $sendWaHelper->sendAcceptCancelNotification($transaksi->id);
            foreach ($transaksi->detail as $item) {
                $produk = Produk::findOrFail($item->produk_id);
                $produk->tambahStok($item->jumlah, 'Pembatalan Pesanan #' . $transaksi->id);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Validasi',
        ]);
    }


    // onsite
    public function createOnsite()
    {
        $produk = Produk::where('stok', '>', 0)->get();
        return view('admin.pesanan.create_onsite', compact('produk'));
    }

    public function storeOnsite(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string',
            'no_telepon' => 'required|string',
            'alamat' => 'required|string',
            'produk' => 'required|array',
            'produk.*.id' => 'required|exists:produk,id',
            'produk.*.jumlah' => 'required|integer|min:1',
            'catatan_penjual' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $kode_transaksi = 'ONSITE-' . date('Ymd') . '-' . rand(1000, 9999);
            $sub_total = 0;
            $total_berat = 0;

            // Hitung subtotal dan berat
            foreach ($request->produk as $item) {
                $produk = Produk::findOrFail($item['id']);
                $sub_total += $produk->harga_aktif * $item['jumlah'];
                $total_berat += $produk->berat * $item['jumlah'];
            }

            // Buat transaksi
            $transaksi = Transaksi::create([
                'kode_transaksi' => $kode_transaksi,
                'tanggal_transaksi' => now(),
                'pelanggan_id' => null, // Null untuk onsite
                'alamat_id' => null,
                'status_pembayaran' => 'Sudah Dibayar',
                'status_pengiriman' => 'Selesai',
                'jarak' => 0,
                'is_cod' => 0,
                'is_onsite' => 1, // Flag untuk onsite
                'ekspedisi' => null,
                'estimasi_waktu' => null,
                'sub_total' => $sub_total,
                'ongkir' => 0,
                'catatan_pelanggan' => $request->alamat,
                'catatan_penjual' => $request->catatan_penjual,
                'nama_pelanggan_onsite' => $request->nama_pelanggan,
                'no_telepon_onsite' => $request->no_telepon,
                'alamat_onsite' => $request->alamat
            ]);

            // Buat detail transaksi
            foreach ($request->produk as $item) {
                $produk = Produk::findOrFail($item['id']);

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['id'],
                    'status_harga' => 'ecer',
                    'jumlah' => $item['jumlah'],
                    'berat' => $produk->berat * $item['jumlah'],
                    'sub_total' => $produk->harga_aktif * $item['jumlah']
                ]);

                // Kurangi stok
                $produk->kurangiStok($item['jumlah'], 'Pembelian Onsite #' . $transaksi->id);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan onsite berhasil dibuat',
                'transaksi_id' => $transaksi->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProdukData()
    {
        $produk = Produk::where('stok', '>', 0)
            ->select('id', 'nama', 'harga_aktif', 'stok', 'berat', 'foto')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $produk
        ]);
    }
}
