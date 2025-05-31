<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kurir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KelolaProfilKurirController extends Controller
{
    public function index()
    {
        $kurir = Kurir::where('user_id', Auth::user()->id)->first();
        if (!$kurir) {
            $kurir = Kurir::create([
                'user_id' => Auth::user()->id,
                'latitude' => '-7.8166',
                'longitude' => '112.0114',
                'status' => 'aktif'
            ]);
        }
        return view('kurir.kelola-profil', compact('kurir'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'telp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'plat_nomor' => 'required|string|max:20',
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $kurir = Kurir::where('user_id', Auth::user()->id)->first();
            if (!$kurir) {
                $kurir = new Kurir();
                $kurir->user_id = Auth::user()->id;
            }

            $kurir->telp = $request->telp;
            $kurir->alamat = $request->alamat;
            $kurir->plat_nomor = $request->plat_nomor;
            $kurir->jenis_kendaraan = $request->jenis_kendaraan;
            $kurir->latitude = $request->latitude;
            $kurir->longitude = $request->longitude;

            if ($request->hasFile('photo')) {
                if ($kurir->photo) {
                    Storage::delete('public/' . $kurir->photo);
                }
                $photo_path = $request->file('photo')->store('kurir', 'public');
                $kurir->photo = $photo_path;
            }

            $kurir->save();

            return redirect()->route('kurir.profil.index')
                ->with('success', 'Profil kurir berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profil kurir: ' . $e->getMessage())
                ->withInput();
        }
    }
}
