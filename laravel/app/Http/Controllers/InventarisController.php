<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Inventaris;
use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class InventarisController extends Controller
{
    public function index()
    {

        $holding = request()->segment(count(request()->segments()));
        return view('admin.inventaris.index', [
            // return view('karyawan.index', [
            'title' => 'Data Inventaris',
            "data_departemen" => Departemen::all(),
            "data_inventaris" => Inventaris::all(),
            'holding' => $holding,
            'data_user' => User::where('kontrak_kerja', $holding)->get(),
            "data_jabatan" => Jabatan::all(),
            "karyawan_laki" => User::where('gender', 'Laki-Laki')->where('kontrak_kerja', $holding)->count(),
            "karyawan_perempuan" => User::where('gender', 'Perempuan')->where('kontrak_kerja', $holding)->count(),
            "karyawan_office" => User::where('gender', 'Laki-Laki')->where('kontrak_kerja', $holding)->count(),
            "karyawan_shift" => User::where('gender', 'Perempuan')->where('kontrak_kerja', $holding)->count(),
        ]);
    }
    public function datatable(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $table = Inventaris::where('holding_inventaris', $holding)->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('foto_inventaris', function ($row) use ($holding) {
                    if ($row->foto_inventaris == '') {
                        $img = '<img src="https://karyawan.sumberpangan.store/public/admin/assets/img/avatars/1.png" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_inventaris" />';
                    } else {
                        $img = '<img src="https://karyawan.sumberpangan.store/laravel/storage/app/public/foto_inventaris/' . $row->foto_inventaris . '" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_inventaris" />';
                    }
                    return $img;
                })
                ->addColumn('option', function ($row) use ($holding) {
                    $btn = '<button id="btn_edit_inventaris" data-id="' . $row->id_inventaris . '" data-holding="' . $holding . '" data-kategori="' . $row->kategori_inventaris . '" data-nama="' . $row->nama_inventaris . '" data-kode="' . $row->kode_inventaris . '" data-type="' . $row->type_inventaris . '" data-foto="' . $row->foto_inventaris . '" data-sn="' . $row->serial_number_inventaris . '" data-bs-toggle="modal" data-bs-target="#modal_edit_inventaris" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_karyawan" data-id="' . $row->id . '" data-holding="' . $holding . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['option', 'foto_inventaris'])
                ->make(true);
        }
    }
    public function tambahInventarisProses(Request $request)
    {

        // dd($request->all());
        $validatedData = $request->validate([
            'kode_inventaris' => 'required|max:255',
            'nama_inventaris' => 'required|max:255',
            'type_inventaris' => 'required|max:255',
            'serial_number_inventaris' => 'required|max:255',
            'kategori_inventaris' => 'required|max:255',
        ]);
        if ($request['foto_inventaris']) {
            // dd('ok');
            $extension     = $request->file('foto_inventaris')->extension();
            // dd($extension);
            $img_name         = date('y-m-d') . '-' . Uuid::uuid4() . '.' . $extension;
            $path           = Storage::putFileAs('foto_inventaris/', $request->file('foto_inventaris'), $img_name);
        } else {
            $img_name = NULL;
        }
        $holding = request()->segment(count(request()->segments()));
        // dd($validatedData);
        $insert = Inventaris::create(
            [
                'id_inventaris' => Uuid::uuid4('id_inventaris'),
                'holding_inventaris' => $holding,
                'kode_inventaris' => $validatedData['kode_inventaris'],
                'nama_inventaris' => $validatedData['nama_inventaris'],
                'type_inventaris' => $validatedData['type_inventaris'],
                'serial_number_inventaris' => $validatedData['serial_number_inventaris'],
                'kategori_inventaris' => $validatedData['kategori_inventaris'],
                'foto_inventaris' => $img_name,
            ]
        );
        // 

        // Merekam aktivitas pengguna
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'activity' => 'create',
            'description' => 'Menambahkan data Inventaris baru ' . $request->name,
        ]);
        return redirect('/inventaris/' . $holding)->with('success', 'Data Berhasil di Tambahkan');
    }
    public function editInventarisProses(Request $request)
    {

        // dd($request->all());
        $validatedData = $request->validate([
            'kode_inventaris_update' => 'required|max:255',
            'nama_inventaris_update' => 'required|max:255',
            'type_inventaris_update' => 'required|max:255',
            'serial_number_inventaris_update' => 'required|max:255',
            'kategori_inventaris_update' => 'required|max:255',
        ]);
        if ($request['foto_inventaris_update']) {
            if ($request['foto_inventaris_lama']) {
                // dd('ok');
                $path           = Storage::delete('foto_inventaris/', $request->file('foto_inventaris_lama'));
            }
            // dd('ok1');
            // dd('ok');
            $extension     = $request->file('foto_inventaris_update')->extension();
            // dd($extension);
            $img_name         = date('y-m-d') . '-' . Uuid::uuid4() . '.' . $extension;
            $path           = Storage::putFileAs('foto_inventaris/', $request->file('foto_inventaris_update'), $img_name);
        } else {
            $img_name = $request['foto_inventaris_lama'];
        }
        $holding = request()->segment(count(request()->segments()));
        // dd($validatedData);
        $update = Inventaris::where('id_inventaris', $request["id_inventaris"])->update(
            [
                'holding_inventaris' => $holding,
                'kode_inventaris' => $validatedData['kode_inventaris_update'],
                'nama_inventaris' => $validatedData['nama_inventaris_update'],
                'type_inventaris' => $validatedData['type_inventaris_update'],
                'serial_number_inventaris' => $validatedData['serial_number_inventaris_update'],
                'kategori_inventaris' => $validatedData['kategori_inventaris_update'],
                'foto_inventaris' => $img_name,
            ]
        );
        // 

        // Merekam aktivitas pengguna
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'activity' => 'create',
            'description' => 'Menambahkan data Inventaris baru ' . $request->name,
        ]);
        return redirect('/inventaris/' . $holding)->with('success', 'Data Berhasil di Diupdate');
    }
}
