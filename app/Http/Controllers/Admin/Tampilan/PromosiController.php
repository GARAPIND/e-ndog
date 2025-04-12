<?php

namespace App\Http\Controllers\Admin\Tampilan;

use App\Http\Controllers\Controller;
use App\Models\Promosi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PromosiController extends Controller
{
    public function index()
    {
        return view('admin.tampilan.promosi');
    }

    public function get_data()
    {
        $data = Promosi::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("foto", function ($row) {
                if ($row->foto) {
                    $url = asset('storage/promosi/' . $row->foto);
                    return '<img src="' . $url . '" alt="' . $row->name . '" class="img-thumbnail" style="max-width: 100px;">';
                }
                return 'Tidak Ada Foto';
            })
            ->addColumn("aksi", function ($row) {
                $id = encrypt($row->id);
                $button = "<button class='btn btn-warning btn-sm' data-toggle='modal'
                                data-target='#modal' onclick='submit(\"$id\")'>
                                <i class='mdi mdi-account-edit'></i>Edit
                            </button>
                            <button class='btn btn-danger btn-sm' data-toggle='modal'
                                data-target='#deleteModal' onclick='btn_delete(\"$id\")'>
                                <i class='mdi mdi-account-minus'></i>Delete
                            </button>";

                return $button;
            })
            ->rawColumns(['aksi', 'foto'])
            ->make(true);
    }

    public function get_data_id(Request $request)
    {
        $id = decrypt($request->id);
        $promosi = Promosi::find($id);

        $data = [
            'id' => encrypt($promosi->id),
            'judul' => $promosi->judul,
            'sub_judul' => $promosi->sub_judul,
        ];

        return response()->json($data);
    }

    public function tambah_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:20',
            'sub_judul' => 'required|string|max:20',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();
        try {

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $path = storage_path('app/public/promosi/');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $file->storeAs('public/promosi/', $fileName);
            }

            Promosi::create([
                'judul' => $request->judul,
                'sub_judul' => $request->sub_judul,
                'foto' => $fileName,
            ]);

            DB::commit();
            return response()->json(['success' => 'Berhasil menambah data promosi']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function edit_data(Request $request)
    {
        $id = decrypt($request->id);
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:20',
            'sub_judul' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();
        try {
            $promosi = Promosi::find($id);
            $promosi->judul = $request->judul;
            $promosi->sub_judul = $request->sub_judul;
            $promosi->save();
            if ($request->hasFile('foto')) {
                if ($promosi->foto) {
                    $oldPath = storage_path('app/public/promosi/' . $promosi->foto);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $file = $request->file('foto');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $path = storage_path('app/public/promosi/');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $file->storeAs('public/promosi/', $fileName);
                $promosi->foto = $fileName;
                $promosi->save();
            }

            DB::commit();
            return response()->json(['success' => 'Berhasil memperbarui data promosi']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function hapus_data(Request $request)
    {
        $id = decrypt($request->id);

        try {
            $promosi = Promosi::where('id', $id)->first();
            if ($promosi && $promosi->foto) {
                $filePath = storage_path('app/public/promosi/' . $promosi->foto);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            Promosi::destroy($id);

            return response()->json(['success' => 'Berhasil menghapus data promosi']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }
}
