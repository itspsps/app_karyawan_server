<?php

namespace App\Http\Controllers;

use App\Imports\DepartemenImport;
use App\Models\Bagian;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class DepartemenController extends Controller
{
    public function index()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.departemen.index', [
            'title' => 'Master Departemen',
            'holding' => $holding,
            'data_departemen' => Departemen::all()
        ]);
    }
    public function ImportDepartemen(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        Excel::import(new DepartemenImport, $request->file_excel);

        return redirect('/departemen/' . $holding)->with('success', 'Import Departemen Sukses');
    }
    public function datatable(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $table = Departemen::where('holding', $holding)->orderBy('nama_departemen', 'ASC')->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('jumlah_divisi', function ($row) use ($holding) {
                    $cek_divisi = Divisi::where('dept_id', $row->id)->where('holding', $holding)->count();
                    if ($cek_divisi == 0) {
                        $jumlah_divisi = $cek_divisi;
                    } else {
                        $jumlah_divisi = $cek_divisi . '&nbsp; <button id="btn_lihat_divisi" data-id="' . $row->id . '" data-holding="' . $holding . '" type="button" class="btn btn-sm btn-outline-primary">
                    <span class="tf-icons mdi mdi-eye-circle-outline me-1"></span>Lihat
                  </button>';
                    }
                    return $jumlah_divisi;
                })
                ->addColumn('jumlah_karyawan', function ($row) use ($holding) {
                    $cek_karyawan = Karyawan::where('dept_id', $row->id)
                        ->where('kontrak_kerja', $holding)
                        ->where('status_aktif', 'AKTIF')
                        ->count();
                    if ($cek_karyawan == 0) {
                        $jumlah_karyawan = $cek_karyawan;
                    } else {
                        $jumlah_karyawan = $cek_karyawan . '&nbsp; <button id="btn_lihat_karyawan" data-id="' . $row->id . '" data-holding="' . $holding . '" type="button" class="btn btn-sm btn-outline-info">
                    <span class="tf-icons mdi mdi-eye-circle-outline me-1"></span>Lihat
                  </button>';
                    }
                    return $jumlah_karyawan;
                })
                ->addColumn('option', function ($row) use ($holding) {
                    $user_count = Karyawan::where('dept_id', $row->id)->where('status_aktif', 'AKTIF')->count();
                    $btn = '<button id="btn_edit_dept" data-id="' . $row->id . '" data-dept="' . $row->nama_departemen . '" data-holding="' . $holding . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_dept" data-usercount="' . $user_count . '" data-id="' . $row->id . '" data-holding="' . $holding . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['option', 'jumlah_karyawan', 'jumlah_divisi'])
                ->make(true);
        }
    }
    public function divisi_datatable(Request $request, $id)
    {
        $holding = request()->segment(count(request()->segments()));
        $table =  Divisi::where('dept_id', $id)
            ->where('holding', $holding)
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('jumlah_karyawan', function ($row) use ($holding) {
                    if ($holding == 'sp') {
                        $karyawan = Karyawan::where('divisi_id', $row->id)
                            ->leftJoin('users as b', 'b.karyawan_id', 'karyawans.id')
                            ->where('status_aktif', 'AKTIF')
                            ->orWhere('divisi1_id', $row->id)
                            ->orWhere('divisi2_id', $row->id)
                            ->orWhere('divisi3_id', $row->id)
                            ->orWhere('divisi4_id', $row->id)
                            ->where('b.is_admin', 'user')
                            ->where('karyawans.status_aktif', 'AKTIF')
                            ->count();
                    } else if ($holding == 'sps') {
                        $karyawan = Karyawan::where('divisi_id', $row->id)
                            ->leftJoin('users as b', 'b.karyawan_id', 'karyawans.id')
                            ->where('status_aktif', 'AKTIF')
                            ->orWhere('divisi1_id', $row->id)
                            ->orWhere('divisi2_id', $row->id)
                            ->orWhere('divisi3_id', $row->id)
                            ->orWhere('divisi4_id', $row->id)
                            ->where('b.is_admin', 'user')
                            ->where('karyawans.status_aktif', 'AKTIF')
                            ->count();
                    } else {
                        $karyawan = Karyawan::where('divisi_id', $row->id)
                            ->leftJoin('users as b', 'b.karyawan_id', 'karyawans.id')
                            ->where('status_aktif', 'AKTIF')
                            ->orWhere('divisi1_id', $row->id)
                            ->orWhere('divisi2_id', $row->id)
                            ->orWhere('divisi3_id', $row->id)
                            ->orWhere('divisi4_id', $row->id)
                            ->where('b.is_admin', 'user')
                            ->where('karyawans.status_aktif', 'AKTIF')
                            ->count();
                    }
                    return $karyawan;
                })
                ->rawColumns(['jumlah_karyawan',])
                ->make(true);
        }
    }
    public function karyawandepartemen_datatable(Request $request, $id)
    {
        $holding = request()->segment(count(request()->segments()));
        $table =   Karyawan::where('dept_id', $id)
            ->leftJoin('users as b', 'b.karyawan_id', 'karyawans.id')
            ->where('b.is_admin', 'user')
            ->where('karyawans.status_aktif', 'AKTIF')
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_divisi', function ($row) use ($holding, $id) {
                    $divisi = Divisi::where('holding', $holding)->where('id', $row->divisi_id)->value('nama_divisi');

                    return $divisi;
                })
                ->addColumn('nama_bagian', function ($row) use ($holding, $id) {
                    $bagian = Bagian::where('holding', $holding)->where('id', $row->bagian_id)->value('nama_bagian');

                    return $bagian;
                })
                ->rawColumns(['nama_divisi', 'nama_bagian'])
                ->make(true);
        }
    }
    public function create()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('departemen.create', [
            'holding' => $holding,
            'title' => 'Tambah Data Departemen'
        ]);
    }

    public function insert(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $validatedData = $request->validate([
            'nama_departemen' => 'required|max:255',
        ]);

        Departemen::create(
            [
                'id' => Uuid::uuid4(),
                'holding' => $holding,
                'nama_departemen' => $validatedData['nama_departemen'],
            ]
        );
        return redirect('/departemen/' . $holding)->with('success', 'data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $holding = request()->segment(count(request()->segments()));
        return view('departemen.edit', [
            'title' => 'Edit Data Departemen',
            'holding' => $holding,
            'data_departemen' => Departemen::findOrFail($id)
        ]);
    }

    public function update(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $validatedData = $request->validate([
            'nama_departemen_update' => 'required|max:255',
        ]);

        Departemen::where('id', $request->id_departemen)->update([
            'holding' => $holding,
            'nama_departemen' => $validatedData['nama_departemen_update'],
        ]);
        return redirect('/departemen/' . $holding)->with('success', 'data berhasil diupdate');
    }

    public function delete($id)
    {
        $holding = request()->segment(count(request()->segments()));
        $cek_divisi = Divisi::where('dept_id', $id)->where('holding', $holding)->count();
        if ($cek_divisi == 0) {
            $cek_karyawan = Karyawan::where('dept_id', $id)->where('kontrak_kerja', $holding)->where('status_aktif', 'AKTIF')->count();
            if ($cek_karyawan == 0) {
                $departemen = Departemen::where('id', $id)->delete();
                return response()->json(['status' => 1]);
            } else {
                return response()->json(['status' => 2]);
            }
        } else {
            return response()->json(['status' => 0]);
        }
    }
}
