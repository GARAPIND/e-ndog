<?php

namespace App\Http\Controllers\Admin\Tampilan;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CarouselController extends Controller
{
    public function index()
    {
        return view('admin.tampilan.carousel');
    }

    public function get_data()
    {
        $data = Carousel::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("foto", function ($row) {
                if ($row->foto) {
                    $url = asset('storage/carousel/' . $row->foto);
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
        $carousel = Carousel::find($id);

        $data = [
            'id' => encrypt($carousel->id),
            'judul' => $carousel->judul,
            'deskripsi' => $carousel->deskripsi,
        ];

        return response()->json($data);
    }

    public function tambah_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
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
                $path = storage_path('app/public/carousel/');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $file->storeAs('public/carousel/', $fileName);
            }

            Carousel::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'foto' => $fileName,
            ]);

            DB::commit();
            return response()->json(['success' => 'Berhasil menambah data carousel']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function edit_data(Request $request)
    {
        $id = decrypt($request->id);
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();
        try {
            $carousel = Carousel::find($id);
            $carousel->judul = $request->judul;
            $carousel->deskripsi = $request->deskripsi;
            $carousel->save();
            if ($request->hasFile('foto')) {
                if ($carousel->foto) {
                    $oldPath = storage_path('app/public/carousel/' . $carousel->foto);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $file = $request->file('foto');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $path = storage_path('app/public/carousel/');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $file->storeAs('public/carousel/', $fileName);
                $carousel->foto = $fileName;
                $carousel->save();
            }

            DB::commit();
            return response()->json(['success' => 'Berhasil memperbarui data carousel']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }

    public function hapus_data(Request $request)
    {
        $id = decrypt($request->id);

        try {
            $carousel = Carousel::where('id', $id)->first();
            if ($carousel && $carousel->foto) {
                $filePath = storage_path('app/public/carousel/' . $carousel->foto);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            Carousel::destroy($id);

            return response()->json(['success' => 'Berhasil menghapus data carousel']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data, silakan coba lagi.', 'message' => $e->getMessage()]);
        }
    }
}
