<?php

namespace App\Http\Controllers;

use App\Imports\DepartemenImport;
use App\Models\Bagian;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Holding;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class DepartemenController extends Controller
{
    public function index($holding)
    {
        $getHolding = Holding::where('holding_code', $holding)->first();
        if ($getHolding == null) {
            Alert::error('Error', 'Holding Tidak Ditemukan', 3000);
            return redirect()->route('dashboard_holding')->with('error', 'Holding Tidak Ditemukan');
        }
        return view('admin.departemen.index', [
            'title' => 'Master Departemen',
            'holding' => $getHolding,
            'data_departemen' => Departemen::all()
        ]);
    }
    public function ImportDepartemen(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        Excel::import(new DepartemenImport, $request->file_excel);

        return redirect('/departemen/' . $holding)->with('success', 'Import Departemen Sukses');
    }
    public function datatable(Request $request, $holding)
    {
        $holding  = Holding::where('holding_code', $holding)->first();
        $table = Departemen::with(['Karyawan' => function ($query) use ($holding) {
            $query->where('status_aktif', 'AKTIF')
                ->where('kontrak_kerja', $holding->id);
        }])->where('holding', $holding->id)
            ->orderBy('nama_departemen', 'ASC')->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('jumlah_divisi', function ($row) use ($holding) {
                    $cek_divisi = Divisi::where('dept_id', $row->id)->where('holding', $holding->id)->count();
                    if ($cek_divisi == 0) {
                        $jumlah_divisi = $cek_divisi;
                    } else {
                        $jumlah_divisi = $cek_divisi . '&nbsp; <button id="btn_lihat_divisi" data-id="' . $row->id . '" data-holding="' . $holding->holding_code . '" type="button" class="btn btn-sm btn-outline-primary">
                    <span class="tf-icons mdi mdi-eye-circle-outline me-1"></span>Lihat
                  </button>';
                    }
                    return $jumlah_divisi;
                })
                ->addColumn('jumlah_karyawan', function ($row) use ($holding) {
                    $cek_karyawan = $row->Karyawan->count();
                    if ($cek_karyawan == 0) {
                        $jumlah_karyawan = $cek_karyawan;
                    } else {
                        $jumlah_karyawan = $cek_karyawan . '&nbsp; <button id="btn_lihat_karyawan" data-id="' . $row->id . '" data-holding="' . $holding->holding_code . '" type="button" class="btn btn-sm btn-outline-info">
                    <span class="tf-icons mdi mdi-eye-circle-outline me-1"></span>Lihat
                  </button>';
                    }
                    return $jumlah_karyawan;
                })
                ->addColumn('option', function ($row) use ($holding) {
                    $user_count = $row->Karyawan->count();
                    $btn = '<button id="btn_edit_dept" data-id="' . $row->id . '" data-dept="' . $row->nama_departemen . '" data-holding="' . $holding->holding_code . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_dept" data-usercount="' . $user_count . '" data-id="' . $row->id . '" data-holding="' . $holding->holding_code . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['option', 'jumlah_karyawan', 'jumlah_divisi'])
                ->make(true);
        }
    }
    public function divisi_datatable($id, $holding)
    {
        // dd($holding);
        $holding = Holding::where('holding_code', $holding)->first();
        $table =  Divisi::with(['Karyawan' => function ($query) use ($holding) {
            $query->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $holding->id);
        }])->where('dept_id', $id)
            ->where('holding', $holding->id)
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('jumlah_karyawan', function ($row) {
                    $karyawan = $row->Karyawan->count();
                    return $karyawan;
                })
                ->rawColumns(['jumlah_karyawan',])
                ->make(true);
        }
    }
    public function karyawandepartemen_datatable($id, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $table =   Karyawan::where('dept_id', $id)
            ->leftJoin('users as b', 'b.karyawan_id', 'karyawans.id')
            ->where('b.is_admin', 'user')
            ->where('karyawans.status_aktif', 'AKTIF')
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_divisi', function ($row) use ($holding, $id) {
                    $divisi = Divisi::where('holding', $holding->id)->where('id', $row->divisi_id)->value('nama_divisi');

                    return $divisi;
                })
                ->addColumn('nama_bagian', function ($row) use ($holding, $id) {
                    $bagian = Bagian::where('holding', $holding->id)->where('id', $row->bagian_id)->value('nama_bagian');

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

    public function insert(Request $request, $holding)
    {
        $holding  = Holding::where('holding_code', $holding)->first();
        $validatedData = $request->validate([
            'nama_departemen' => 'required|max:255',
        ]);
        try {
            Departemen::create(
                [
                    'id' => Uuid::uuid4(),
                    'holding' => $holding->id,
                    'nama_departemen' => $validatedData['nama_departemen'],
                ]
            );
            return redirect('/departemen/' . $holding->holding_code)->with('success', 'data berhasil ditambahkan');
        } catch (QueryException $e) {
            return redirect('/departemen/' . $holding->holding_code)->with('Error', $e->getMessage()); // bisa disembunyikan di production

        }
    }

    public function edit($id, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        return view('departemen.edit', [
            'title' => 'Edit Data Departemen',
            'holding' => $holding,
            'data_departemen' => Departemen::findOrFail($id)
        ]);
    }

    public function update(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $validatedData = $request->validate([
            'nama_departemen_update' => 'required|max:255',
        ]);

        Departemen::where('id', $request->id_departemen)->update([
            'holding' => $holding->id,
            'nama_departemen' => $validatedData['nama_departemen_update'],
        ]);
        return redirect('/departemen/' . $holding->holding_code)->with('success', 'data berhasil diupdate');
    }

    public function delete($id, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $cek_divisi = Divisi::where('dept_id', $id)->where('holding', $holding->id)->count();
        if ($cek_divisi == 0) {
            $cek_karyawan = Karyawan::where('dept_id', $id)->where('kontrak_kerja', $holding->id)->where('status_aktif', 'AKTIF')->count();
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
