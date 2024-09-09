<?php

namespace App\Http\Controllers;

use App\Imports\DivisiImport;
use App\Models\Bagian;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class DivisiController extends Controller
{
    public function index()
    {
        $holding = request()->segment(count(request()->segments()));
        // $get = Divisi::with('Departemen')->get();
        // dd($get);
        return view('admin.divisi.index', [
            'title' => 'Master Divisi',
            'holding' => $holding,
            'data_divisi' => Divisi::with('Departemen')->where('holding', $holding)->get(),
            'data_departemen' => Departemen::orderBy('nama_departemen', 'asc')->where('holding', $holding)->get()
        ]);
    }
    public function ImportDivisi(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        Excel::import(new DivisiImport, $request->file_excel);

        return redirect('/divisi/' . $holding)->with('success', 'Import Divisi Sukses');
    }
    public function datatable(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $table =  Divisi::with(['Departemen' => function ($query) {
            $query->orderBy('nama_departemen', 'ASC');
        }])->where('holding', $holding)->orderBy('nama_divisi', 'ASC')->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_departemen', function ($row) {
                    $nama_departemen = $row->Departemen->nama_departemen;
                    return $nama_departemen;
                })
                ->addColumn('jumlah_bagian', function ($row) use ($holding) {
                    $cek_bagian = Bagian::where('divisi_id', $row->id)->where('holding', $holding)->count();
                    if ($cek_bagian == 0) {
                        $jumlah_bagian = $cek_bagian;
                    } else {
                        $jumlah_bagian = $cek_bagian . '&nbsp; <button id="btn_lihat_bagian" data-id="' . $row->id . '" data-holding="' . $holding . '" type="button" class="btn btn-sm btn-outline-primary">
                    <span class="tf-icons mdi mdi-eye-circle-outline me-1"></span>Lihat
                  </button>';
                    }
                    return $jumlah_bagian;
                })
                ->addColumn('jumlah_karyawan', function ($row) use ($holding) {
                    $cek_karyawan = User::where('divisi_id', $row->id)
                        ->orWhere('divisi1_id', $row->id)
                        ->orWhere('divisi2_id', $row->id)
                        ->orWhere('divisi3_id', $row->id)
                        ->orWhere('divisi4_id', $row->id)
                        ->where('kontrak_kerja', $holding)
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
                    $btn = '<button id="btn_edit_divisi" data-id="' . $row->id . '" data-dept="' . $row->dept_id . '" data-divisi="' . $row->nama_divisi . '" data-holding="' . $holding . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_divisi" data-id="' . $row->id . '" data-holding="' . $holding . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['nama_departemen', 'jumlah_bagian', 'jumlah_karyawan', 'option'])
                ->make(true);
        }
    }
    public function bagian_datatable(Request $request, $id)
    {
        $holding = request()->segment(count(request()->segments()));
        $table =  Bagian::where('divisi_id', $id)
            ->where('holding', $holding)
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('jumlah_karyawan', function ($row) use ($holding) {
                    $karyawan = User::where('bagian_id', $row->id)
                        ->orWhere('bagian1_id', $row->id)
                        ->orWhere('bagian2_id', $row->id)
                        ->orWhere('bagian3_id', $row->id)
                        ->orWhere('bagian4_id', $row->id)
                        ->where('kontrak_kerja', $holding)
                        ->where('is_admin', 'user')
                        ->count();
                    return $karyawan;
                })
                ->rawColumns(['jumlah_karyawan'])
                ->make(true);
        }
    }
    public function karyawandivisi_datatable(Request $request, $id)
    {
        $holding = request()->segment(count(request()->segments()));
        $table =   User::where('divisi_id', $id)
            ->where('is_admin', 'user')
            ->where('kontrak_kerja', $holding)
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_bagian', function ($row) use ($holding) {
                    $bagian = Bagian::where('holding', $holding)->where('id', $row->bagian_id)->value('nama_bagian');

                    return $bagian;
                })
                ->addColumn('nama_jabatan', function ($row) use ($holding, $id) {
                    $jabatan = Jabatan::where('holding', $holding)->where('id', $row->jabatan_id)->value('nama_jabatan');

                    return $jabatan;
                })
                ->rawColumns(['nama_jabatan', 'nama_bagian'])
                ->make(true);
        }
    }
    public function create()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('divisi.create', [
            'title' => 'Tambah Data Divisi',
            'holding' => $holding,
            'data_departemen' => Departemen::all(),
        ]);
    }

    public function insert(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $validatedData = $request->validate([
            'nama_divisi' => 'required|max:255',
            'nama_departemen' => 'required',
        ]);

        Divisi::create(
            [
                'id' => Uuid::uuid4(),
                'holding' => $holding,
                'nama_divisi' => $validatedData['nama_divisi'],
                'dept_id' => Departemen::where('id', $validatedData['nama_departemen'])->value('id'),
            ]
        );
        return redirect('/divisi/' . $holding)->with('success', 'Data Berhasil di Tambahkan');
    }

    public function edit($id)
    {
        $holding = request()->segment(count(request()->segments()));
        return view('divisi.edit', [
            'title' => 'Edit Data Divisi',
            'holding' => $holding,
            'data_departemen' => Departemen::all(),
            'data_divisi' => Divisi::with('Departemen')->findOrFail($id)
        ]);
    }

    public function update(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $validatedData = $request->validate([
            'nama_divisi_update' => 'required|max:255',
            'nama_departemen_update' => 'required',
        ]);

        Divisi::where('id', $request->id_divisi)->update(
            [
                'holding' => $holding,
                'nama_divisi' => $validatedData['nama_divisi_update'],
                'dept_id' => Departemen::where('id', $validatedData['nama_departemen_update'])->value('id'),
            ]
        );
        return redirect('/divisi/' . $holding)->with('success', 'Data Berhasil di Update');
    }

    public function delete($id)
    {

        $holding = request()->segment(count(request()->segments()));
        $cek_bagian = Bagian::where('divisi_id', $id)->where('holding', $holding)->count();
        if ($cek_bagian == 0) {
            $cek_karyawan = User::where('bagian_id', $id)->where('kontrak_kerja', $holding)->count();
            if ($cek_karyawan == 0) {
                $divisi = Divisi::where('id', $id)->delete();
                return response()->json(['status' => 1]);
            } else {
                return response()->json(['status' => 2]);
            }
        } else {
            return response()->json(['status' => 0]);
        }
    }
}
