<?php

namespace App\Http\Controllers;

use App\Imports\DivisiImport;
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

class DivisiController extends Controller
{
    public function index($holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        // $get = Divisi::with('Departemen')->get();
        // dd($get);
        return view('admin.divisi.index', [
            'title' => 'Master Divisi',
            'holding' => $holding,
            'data_divisi' => Divisi::with('Departemen')->where('holding', $holding->id)->get(),
            'data_departemen' => Departemen::orderBy('nama_departemen', 'asc')->where('holding', $holding->id)->get()
        ]);
    }
    public function ImportDivisi(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        Excel::import(new DivisiImport, $request->file_excel);

        return redirect('/divisi/' . $holding)->with('success', 'Import Divisi Sukses');
    }
    public function datatable(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $table =  Divisi::with(['Departemen' => function ($query) {
            $query->orderBy('nama_departemen', 'ASC');
        }])->where('holding', $holding->id)->orderBy('nama_divisi', 'ASC')->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_departemen', function ($row) {
                    $nama_departemen = $row->Departemen->nama_departemen;
                    return $nama_departemen;
                })
                ->addColumn('jumlah_bagian', function ($row) use ($holding) {
                    $cek_bagian = Bagian::where('divisi_id', $row->id)->where('holding', $holding->id)->count();
                    if ($cek_bagian == 0) {
                        $jumlah_bagian = $cek_bagian;
                    } else {
                        $jumlah_bagian = $cek_bagian . '&nbsp; <button id="btn_lihat_bagian" data-id="' . $row->id . '" data-holding="' . $holding->holding_code . '" type="button" class="btn btn-sm btn-outline-primary">
                    <span class="tf-icons mdi mdi-eye-circle-outline me-1"></span>Lihat
                  </button>';
                    }
                    return $jumlah_bagian;
                })
                ->addColumn('jumlah_karyawan', function ($row) use ($holding) {
                    $cek_karyawan = Karyawan::where('kontrak_kerja', $holding->id)
                        ->where('status_aktif', 'AKTIF')
                        ->where('divisi_id', $row->id)
                        ->orWhere('divisi1_id', $row->id)
                        ->orWhere('divisi2_id', $row->id)
                        ->orWhere('divisi3_id', $row->id)
                        ->orWhere('divisi4_id', $row->id)
                        ->count();
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
                    $btn = '<button id="btn_edit_divisi" data-id="' . $row->id . '" data-dept="' . $row->dept_id . '" data-divisi="' . $row->nama_divisi . '" data-holding="' . $holding->holding_code . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_divisi" data-id="' . $row->id . '" data-holding="' . $holding->holding_code . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['nama_departemen', 'jumlah_bagian', 'jumlah_karyawan', 'option'])
                ->make(true);
        }
    }
    public function bagian_datatable($id, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $table =  Bagian::where('divisi_id', $id)
            ->where('holding', $holding->id)
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('jumlah_karyawan', function ($row) use ($holding) {
                    $karyawan = Karyawan::where('bagian_id', $row->id)
                        ->leftJoin('users as b', 'b.karyawan_id', 'karyawans.id')
                        ->orWhere('bagian1_id', $row->id)
                        ->orWhere('bagian2_id', $row->id)
                        ->orWhere('bagian3_id', $row->id)
                        ->orWhere('bagian4_id', $row->id)
                        ->where('kontrak_kerja', $holding)
                        ->where('status_aktif', 'AKTIF')
                        ->where('b.is_admin', 'user')
                        ->count();
                    return $karyawan;
                })
                ->rawColumns(['jumlah_karyawan'])
                ->make(true);
        }
    }
    public function karyawandivisi_datatable($id, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $table =   Karyawan::where('divisi_id', $id)
            ->leftJoin('users as b', 'b.karyawan_id', 'karyawans.id')
            ->where('b.is_admin', 'user')
            ->where('kontrak_kerja', $holding->id)
            ->where('status_aktif', 'AKTIF')
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_bagian', function ($row) use ($holding) {
                    $bagian = Bagian::where('holding', $holding->id)->where('id', $row->bagian_id)->value('nama_bagian');

                    return $bagian;
                })
                ->addColumn('nama_jabatan', function ($row) use ($holding, $id) {
                    $jabatan = Jabatan::where('holding', $holding->id)->where('id', $row->jabatan_id)->value('nama_jabatan');

                    return $jabatan;
                })
                ->rawColumns(['nama_jabatan', 'nama_bagian'])
                ->make(true);
        }
    }
    public function create($holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        return view('divisi.create', [
            'title' => 'Tambah Data Divisi',
            'holding' => $holding,
            'data_departemen' => Departemen::all(),
        ]);
    }

    public function insert(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $validatedData = $request->validate([
            'nama_divisi' => 'required|max:255',
            'nama_departemen' => 'required',
        ]);
        try {
            Divisi::create(
                [
                    'id' => Uuid::uuid4(),
                    'holding' => $holding->id,
                    'nama_divisi' => $validatedData['nama_divisi'],
                    'dept_id' => Departemen::where('id', $validatedData['nama_departemen'])->value('id'),
                ]
            );
            Alert::success('Sukses', 'data berhasil ditambahkan');
            return redirect('/divisi/' . $holding->holding_code)->with('success', 'data berhasil ditambahkan');
        } catch (QueryException $e) {
            Alert::error('Error', $e->getMessage());
            return redirect('/divisi/' . $holding->holding_code)->with('Error', $e->getMessage()); // bisa disembunyikan di production

        }
    }


    public function edit($id, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        return view('divisi.edit', [
            'title' => 'Edit Data Divisi',
            'holding' => $holding,
            'data_departemen' => Departemen::all(),
            'data_divisi' => Divisi::with('Departemen')->findOrFail($id)
        ]);
    }

    public function update(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $validatedData = $request->validate([
            'nama_divisi_update' => 'required|max:255',
            'nama_departemen_update' => 'required',
        ]);
        try {
            Divisi::where('id', $request->id_divisi)->update(
                [
                    'holding' => $holding->id,
                    'nama_divisi' => $validatedData['nama_divisi_update'],
                    'dept_id' => Departemen::where('id', $validatedData['nama_departemen_update'])->value('id'),
                ]
            );

            Alert::success('Sukses', 'Data Berhasil Diupdate');
            return redirect('/divisi/' . $holding->holding_code)->with('success', 'Data Berhasil Diupdate');
        } catch (QueryException $e) {
            Alert::error('Error', $e->getMessage());
            return redirect('/divisi/' . $holding->holding_code)->with('Error', $e->getMessage()); // bisa disembunyikan di production

        }
    }

    public function delete($id, $holding)
    {

        $holding = Holding::where('holding_code', $holding)->first();
        $cek_bagian = Bagian::where('divisi_id', $id)->where('holding', $holding->id)->count();
        if ($cek_bagian == 0) {
            $cek_karyawan = Karyawan::where('bagian_id', $id)->where('kontrak_kerja', $holding->id)->where('status_aktif', 'AKTIF')->count();
            if ($cek_karyawan == 0) {
                try {
                    $divisi = Divisi::where('id', $id)->delete();
                } catch (QueryException $e) {
                    Alert::error('Error', $e->getMessage());
                    return response()->json(['status' => 0]);
                }

                return response()->json(['status' => 1]);
            } else {
                return response()->json(['status' => 2]);
            }
        } else {
            return response()->json(['status' => 0]);
        }
    }
}
