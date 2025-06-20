<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kurir;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KurirController extends Controller
{
    public function index()
    {
        return view('admin.data.kurir');
    }

    public function getData()
    {
        $couriers = User::where('role', 'kurir')
            ->join('kurir', 'users.id', '=', 'kurir.user_id')
            ->select([
                'users.id',
                'users.name',
                'users.username',
                'users.email',
                'kurir.telp',
                'kurir.alamat',
                'kurir.plat_nomor',
                'kurir.jenis_kendaraan',
                'kurir.photo',
                'kurir.status',
                'users.created_at'
            ]);

        return DataTables::of($couriers)
            ->addColumn('photo', function ($row) {
                return $row->photo
                    ? '<img src="' . asset('storage/foto-kurir/' . $row->photo) . '" alt="Kurir Photo" class="img-thumbnail" style="max-height: 50px;">'
                    : '<span class="badge badge-secondary">No Photo</span>';
            })
            ->addColumn('status_badge', function ($row) {
                $status = $row->status ?? 'inactive';
                $badge = $status == 'active' ? 'success' : 'danger';
                return '<span class="badge badge-' . $badge . '">' . ucfirst($status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" class="btn btn-info btn-sm view-btn" data-id="' . $row->id . '">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button type="button" class="btn btn-primary btn-sm edit-btn" data-id="' . $row->id . '">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['action', 'photo', 'status_badge'])
            ->make(true);
    }

    public function getKurir($id)
    {
        $courier = User::where('role', 'kurir')
            ->where('users.id', $id)
            ->join('kurir', 'users.id', '=', 'kurir.user_id')
            ->select([
                'users.id',
                'users.name',
                'users.username',
                'users.email',
                'kurir.telp',
                'kurir.alamat',
                'kurir.plat_nomor',
                'kurir.jenis_kendaraan',
                'kurir.photo',
                'kurir.status'
            ])
            ->first();

        if (!$courier) {
            return response()->json(['error' => 'Courier not found'], 404);
        }

        return response()->json($courier);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'telp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'plat_nomor' => 'nullable|string|max:20',
            'jenis_kendaraan' => 'nullable|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'kurir',
            ]);

            $photoName = null;
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = $user->id . '_' . time() . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('foto-kurir', $photoName, 'public');
            }
            Kurir::create([
                'user_id' => $user->id,
                'telp' => $request->telp,
                'alamat' => $request->alamat,
                'plat_nomor' => $request->plat_nomor,
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'photo' => $photoName,
                'status' => $request->status,
            ]);

            DB::commit();
            return response()->json(['success' => 'Kurir berhasil ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menambahkan kurir: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user || $user->role !== 'kurir') {
            return response()->json(['error' => 'Kurir tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($id)],
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|string|min:6',
            'telp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'plat_nomor' => 'nullable|string|max:20',
            'jenis_kendaraan' => 'nullable|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
                $user->save();
            }

            $courier = Kurir::where('user_id', $id)->first();
            $updateData = [
                'telp' => $request->telp,
                'alamat' => $request->alamat,
                'plat_nomor' => $request->plat_nomor,
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'status' => $request->status,
            ];
            if ($request->hasFile('photo')) {
                if ($courier->photo) {
                    Storage::disk('public')->delete('foto-kurir/' . $courier->photo);
                }
                $photo = $request->file('photo');
                $photoName = $id . '_' . time() . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('foto-kurir', $photoName, 'public');
                $updateData['photo'] = $photoName;
            }

            $courier->update($updateData);

            DB::commit();
            return response()->json(['success' => 'Kurir berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal memperbarui kurir: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user || $user->role !== 'kurir') {
            return response()->json(['error' => 'Kurir tidak ditemukan'], 404);
        }

        try {
            $courier = Kurir::where('user_id', $id)->first();
            if ($courier && $courier->photo) {
                Storage::disk('public')->delete('foto-kurir/' . $courier->photo);
            }

            $user->delete();

            return response()->json(['success' => 'Kurir berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus kurir: ' . $e->getMessage()], 500);
        }
    }
}
