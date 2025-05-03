<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $customer = $user->customer;
        $addresses = $customer ? $customer->addresses : collect();

        return view('pengunjung.profile.index', compact('user', 'customer', 'addresses'));
    }

    public function edit()
    {
        $user = Auth::user();
        $customer = $user->customer;

        return view('pengunjung.profile.edit', compact('user', 'customer'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'telp' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Update or create customer data
        if ($user->customer) {
            $user->customer->update([
                'telp' => $request->telp,
            ]);
        } else {
            Customer::create([
                'user_id' => $user->id,
                'telp' => $request->telp,
            ]);
        }

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini tidak cocok'])->withInput();
        }
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Password berhasil diperbarui');
    }

    public function addAddress()
    {
        return view('pengunjung.profile.add_address');
    }

    public function storeAddress(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'alamat' => 'required|string',
            'keterangan' => 'required|string|max:255',
            'province_id' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
            'provinsi' => 'required|string',
            'kota' => 'required|string',
            'kecamatan' => 'required|string',
            'kode_pos' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $customer = $user->customer;

        if (!$customer) {
            $customer = Customer::create([
                'user_id' => $user->id,
                'telp' => $user->telp ?? '',
            ]);
        }

        $isPrimary = $customer->addresses()->count() === 0;

        $address = Address::create([
            'pelanggan_id' => $customer->id,
            'alamat' => $request->alamat,
            'keterangan' => $request->keterangan,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kode_pos' => $request->kode_pos,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'district_id' => $request->district_id,
            'is_primary' => $isPrimary,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('profile.addresses')->with('success', 'Alamat berhasil ditambahkan');
    }

    public function addresses()
    {
        $user = Auth::user();
        $customer = $user->customer;
        $addresses = $customer ? $customer->addresses : collect();

        return view('pengunjung.profile.addresses', compact('addresses'));
    }

    public function editAddress($id)
    {
        $user = Auth::user();
        $address = Address::where('id', $id)
            ->whereHas('customer', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->firstOrFail();

        return view('pengunjung.profile.edit_address', compact('address'));
    }

    public function updateAddress(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'alamat' => 'required|string',
            'keterangan' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $address = Address::where('id', $id)
            ->whereHas('customer', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->firstOrFail();

        $address->update([
            'alamat' => $request->alamat,
            'keterangan' => $request->keterangan,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('profile.addresses')->with('success', 'Alamat berhasil diperbarui');
    }

    public function setPrimaryAddress($id)
    {
        $user = Auth::user();
        $customer = $user->customer;

        if ($customer) {
            // First, set all addresses as non-primary
            $customer->addresses()->update(['is_primary' => false]);

            // Then, set the selected address as primary
            $address = Address::where('id', $id)
                ->where('pelanggan_id', $customer->id)
                ->firstOrFail();

            $address->update(['is_primary' => true]);

            return redirect()->route('profile.addresses')->with('success', 'Alamat utama berhasil diperbarui');
        }

        return redirect()->route('profile.addresses')->with('error', 'Gagal mengatur alamat utama');
    }

    /**
     * Remove the specified address.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyAddress($id)
    {
        $user = Auth::user();
        $address = Address::where('id', $id)
            ->whereHas('customer', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->firstOrFail();

        // Check if this is a primary address
        $isPrimary = $address->is_primary;

        $address->delete();

        // If deleted address was primary, set another address as primary
        if ($isPrimary) {
            $firstAddress = $user->customer->addresses()->first();
            if ($firstAddress) {
                $firstAddress->update(['is_primary' => true]);
            }
        }

        return redirect()->route('profile.addresses')->with('success', 'Alamat berhasil dihapus');
    }

    /**
     * Get addresses for select input via AJAX.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAddresses()
    {
        $user = Auth::user();
        $customer = $user->customer;
        $addresses = $customer ? $customer->addresses : collect();

        return response()->json($addresses);
    }
}
