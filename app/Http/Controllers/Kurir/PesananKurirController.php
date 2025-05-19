<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class PesananKurirController extends Controller
{
    public function index()
    {
        return view('kurir.kelola-pesanan');
    }

    public function getData(Request $request)
    {
        $userId = Auth::user();

        $kurirId = Kurir::where('user_id', $userId->id)->first()->id;
        $pesanan = Transaksi::with(['pelanggan', 'alamat'])
            ->where('kurir_id', $kurirId)
            ->where(function ($query) {
                $query->where('status_pengiriman', 'Dikemas')
                    ->orWhere('status_pengiriman', 'Dikirim')
                    ->orWhere('status_pengiriman', 'Selesai');
            });

        if ($request->has('status') && $request->status != '') {
            $pesanan->where('status_pengiriman', $request->status);
        }

        return DataTables::of($pesanan)
            ->addColumn('pelanggan', function ($row) {
                $user = User::where('id', $row->pelanggan->user_id)->first();
                return $user->name;
            })
            ->addColumn('alamat', function ($row) {
                return $row->alamat->alamat . ', ' . $row->alamat->kecamatan;
            })
            ->addColumn('maps', function ($row) {
                $maps = '';
                if ($row->alamat->latitude && $row->alamat->longitude) {
                    $maps = '<button class="btn btn-sm btn-success btn-maps" data-lat="' . $row->alamat->latitude . '" data-lng="' . $row->alamat->longitude . '"><i class="fas fa-map-marker-alt"></i></button>';
                }
                return $maps;
            })
            ->addColumn('action', function ($row) {
                $detail = '<button class="btn btn-sm btn-info btn-detail me-1" data-id="' . $row->id . '"><i class="fas fa-eye"></i> Detail</button>';

                $status = '';
                if ($row->foto == null) {
                    $status = '<button class="btn btn-sm btn-warning btn-status me-1" data-id="' . $row->id . '" data-status="' . $row->status_pengiriman . '"><i class="fas fa-check"></i> Update</button>';
                }

                return $detail . ' ' . $status;
            })
            ->rawColumns(['action', 'maps'])
            ->make(true);
    }

    public function getPesanan($id)
    {
        $userId = Auth::user();
        $kurirId = Kurir::where('user_id', $userId->id)->first()->id;
        $pesanan = Transaksi::with(['pelanggan.user', 'alamat', 'detail.produk'])
            ->where('id', $id)
            ->where('kurir_id', $kurirId)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $pesanan
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Dikemas,Dikirim,Selesai',
            'foto_bukti' => 'required_if:status,Dikirim|image|max:2048',
            'catatan_kurir' => 'nullable|string|max:255',
        ], [
            'foto_bukti.required_if' => 'Foto bukti pengiriman wajib diupload saat mengubah status menjadi Dikirim',
            'foto_bukti.image' => 'File harus berupa gambar',
            'foto_bukti.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $userId = Auth::user();
        $kurirId = Kurir::where('user_id', $userId->id)->first()->id;

        $transaksi = Transaksi::where('id', $id)
            ->where('kurir_id', $kurirId)
            ->firstOrFail();

        $transaksi->status_pengiriman = $request->status;

        if ($request->filled('catatan_kurir')) {
            $transaksi->catatan_penjual = $transaksi->catatan_penjual . "\n\nCatatan Kurir: " . $request->catatan_kurir;
        }

        if ($request->status == 'Dikirim' && $request->hasFile('foto_bukti')) {
            if ($transaksi->foto) {
                Storage::disk('public')->delete($transaksi->foto);
            }
            $folderPath = 'bukti-pengiriman/' . $id;
            $foto = $request->file('foto_bukti');
            $extension = $foto->getClientOriginalExtension();
            $filename = Str::random(20) . '.' . $extension;
            $foto->storeAs($folderPath, $filename, 'public');
            $transaksi->foto = $folderPath . '/' . $filename;
        }

        if ($request->status == 'Selesai' && $transaksi->is_cod) {
            $transaksi->status_pembayaran = 'Lunas';
        }

        $transaksi->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status pengiriman berhasil diperbarui'
        ]);
    }
}
