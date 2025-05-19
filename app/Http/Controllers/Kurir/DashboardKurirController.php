<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardKurirController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;
        $kurirId = Kurir::where('user_id', $userId)->first()->id;

        // Removed Dikemas since it's no longer needed
        $dikirimCount = Transaksi::where('kurir_id', $kurirId)
            ->where('status_pengiriman', 'Dikirim')
            ->count();

        $selesaiCount = Transaksi::where('kurir_id', $kurirId)
            ->where('status_pengiriman', 'Selesai')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->count();

        $codCount = Transaksi::where('kurir_id', $kurirId)
            ->where('is_cod', true)
            ->where('status_pengiriman', 'Dikirim') // Changed from whereIn to only show 'Dikirim'
            ->count();

        $pendingOrders = Transaksi::with(['pelanggan.user', 'alamat'])
            ->where('kurir_id', $kurirId)
            ->where('status_pengiriman', 'Dikirim') // Changed from whereIn to only show 'Dikirim'
            ->orderBy('tanggal_transaksi', 'asc')
            ->limit(5)
            ->get();

        $completedOrders = Transaksi::with(['pelanggan.user', 'alamat'])
            ->where('kurir_id', $kurirId)
            ->where('status_pengiriman', 'Selesai')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $deliveryLocations = Transaksi::with(['alamat', 'pelanggan.user'])
            ->where('kurir_id', $kurirId)
            ->where('status_pengiriman', 'Dikirim')
            ->get()
            ->map(function ($transaction) {
                if (!$transaction->alamat || !$transaction->alamat->latitude || !$transaction->alamat->longitude) {
                    return null;
                }

                return [
                    'kode_transaksi' => $transaction->kode_transaksi,
                    'lat' => $transaction->alamat->latitude,
                    'lng' => $transaction->alamat->longitude,
                    'customer_name' => $transaction->pelanggan->user->name,
                    'address' => $transaction->alamat->alamat . ', ' . $transaction->alamat->kecamatan,
                    'status' => $transaction->status_pengiriman
                ];
            })
            ->filter()
            ->values();

        return view('kurir.dashboard', compact(
            'dikirimCount',
            'selesaiCount',
            'codCount',
            'pendingOrders',
            'completedOrders',
            'deliveryLocations'
        ));
    }
}
