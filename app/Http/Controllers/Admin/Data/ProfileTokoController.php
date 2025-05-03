<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProfileToko;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileTokoController extends Controller
{
    public function index()
    {
        $profile = ProfileToko::first();
        if (!$profile) {
            $profile = ProfileToko::create([
                'nama_toko' => 'Nama Toko',
            ]);
        }

        return view('admin.data.profile-toko', compact('profile'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_toko' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'deskripsi' => 'nullable|string',
            'jam_operasional' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $profile = ProfileToko::first();
            if (!$profile) {
                $profile = new ProfileToko();
            }

            $profile->nama_toko = $request->nama_toko;
            $profile->alamat = $request->alamat;
            $profile->telepon = $request->telepon;
            $profile->email = $request->email;
            $profile->deskripsi = $request->deskripsi;
            $profile->jam_operasional = $request->jam_operasional;
            $profile->facebook = $request->facebook;
            $profile->instagram = $request->instagram;
            $profile->twitter = $request->twitter;
            $profile->whatsapp = $request->whatsapp;
            if ($request->hasFile('logo')) {
                if ($profile->logo) {
                    Storage::delete('public/' . $profile->logo);
                }

                $logoPath = $request->file('logo')->store('logos', 'public');
                $profile->logo = $logoPath;
            }

            $profile->save();

            return redirect()->route('admin.profile-toko.index')
                ->with('success', 'Profil toko berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profil toko: ' . $e->getMessage())
                ->withInput();
        }
    }
}
