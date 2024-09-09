<?php

namespace App\Http\Controllers;

use App\Imports\BagianImport;
use App\Models\Bagian;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class BagianController extends Controller
{
    public function index()
    {
        $holding = request()->segment(count(request()->segments()));
        // $get = Bagian::with('Divisi')->get();
        // dd($get);
        return view('admin.bagian.index', [
            'title' => 'Master Divisi',
            'holding' => $holding,
            'data_bagian' => Bagian::with('Divisi')->where('holding', $holding)->get(),
            'data_dept' => Departemen::orderBy('nama_departemen', 'asc')->where('holding', $holding)->get(),
            'data_divisi' => Divisi::orderBy('nama_divisi', 'asc')->where('holding', $holding)->get()
        ]);
    }
    public function ImportBagian(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        Excel::import(new BagianImport, $request->file_excel);

        return redirect('/bagian/' . $holding)->with('success', 'Import Bagian Sukses');
    }
    public function get_divisi($id)
    {
        // dd($id);
        $get_divisi = Divisi::where('dept_id', $id)->get();
        // dd($get_divisi);
        echo "<option value=''>Pilih Divisi...</option>";
        foreach ($get_divisi as $divisi) {
            echo "<option value='$divisi->id'>$divisi->nama_divisi</option>";
        }
    }
    public function datatable(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $table =  Bagian::with([
            'Divisi' =>  function ($query) {
                $query->with(['Departemen' => function ($query) {
                    $query->orderBy('nama_departemen', 'ASC');
                }]);
                $query->orderBy('nama_divisi', 'ASC');
            },
        ])->where('holding', $holding)->orderBy('nama_bagian', 'ASC')->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_departemen', function ($row) {
                    if ($row->Divisi == NULL) {
                        $nama_departemen = NULL;
                    } else {
                        $nama_departemen = $row->Divisi->Departemen->nama_departemen;
                    }
                    return $nama_departemen;
                })
                ->addColumn('nama_divisi', function ($row) {
                    if ($row->Divisi == NULL) {
                        $nama_divisi = NULL;
                    } else {
                        $nama_divisi = $row->Divisi->nama_divisi;
                    }
                    return $nama_divisi;
                })
                ->addColumn('jumlah_jabatan', function ($row) use ($holding) {
                    $cek_jabatan = Jabatan::where('bagian_id', $row->id)->where('divisi_id', $row->divisi_id)->where('holding', $holding)->count();
                    if ($cek_jabatan == 0) {
                        $jumlah_jabatan = $cek_jabatan;
                    } else {
                        $jumlah_jabatan = $cek_jabatan . '&nbsp; <button id="btn_lihat_jabatan" data-id="' . $row->id . '" data-holding="' . $holding . '" type="button" class="btn btn-sm btn-outline-primary">
                    <span class="tf-icons mdi mdi-eye-circle-outline me-1"></span>Lihat
                  </button>';
                    }
                    return $jumlah_jabatan;
                })
                ->addColumn('jumlah_karyawan', function ($row) use ($holding) {
                    $cek_karyawan = User::where('bagian_id', $row->id)
                        ->orWhere('bagian1_id', $row->id)
                        ->orWhere('bagian2_id', $row->id)
                        ->orWhere('bagian3_id', $row->id)
                        ->orWhere('bagian4_id', $row->id)
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
                    $btn = '<button id="btn_edit_bagian" data-id="' . $row->id . '" data-dept="' . $row->Dept_id . '" data-divisi="' . $row->divisi_id . '" data-bagian="' . $row->nama_bagian . '" data-holding="' . $holding . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_bagian" data-id="' . $row->id . '" data-holding="' . $holding . '"  data-divisi="' . $row->divisi_id . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';

                    return $btn;
                })
                ->rawColumns(['nama_departemen', 'nama_divisi', 'jumlah_jabatan', 'jumlah_karyawan', 'option'])
                ->make(true);
        }
    }
    public function jabatan_datatable(Request $request, $id)
    {
        $holding = request()->segment(count(request()->segments()));
        $table =  Jabatan::where('bagian_id', $id)
            ->where('holding', $holding)
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('jumlah_karyawan', function ($row) use ($holding) {
                    $karyawan = User::where('jabatan_id', $row->id)
                        ->orWhere('jabatan1_id', $row->id)
                        ->orWhere('jabatan2_id', $row->id)
                        ->orWhere('jabatan3_id', $row->id)
                        ->orWhere('jabatan4_id', $row->id)
                        ->where('kontrak_kerja', $holding)
                        ->where('is_admin', 'user')
                        ->count();
                    return $karyawan;
                })
                ->rawColumns(['jumlah_karyawan'])
                ->make(true);
        }
    }
    public function karyawanjabatan_datatable(Request $request, $id)
    {
        $holding = request()->segment(count(request()->segments()));
        $table =   User::where('bagian_id', $id)
            ->orWhere('bagian1_id', $id)
            ->orWhere('bagian2_id', $id)
            ->orWhere('bagian3_id', $id)
            ->orWhere('bagian4_id', $id)
            ->where('is_admin', 'user')
            ->where('kontrak_kerja', $holding)
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_jabatan', function ($row) use ($holding) {
                    $jabatan = Jabatan::where('holding', $holding)->where('id', $row->jabatan_id)->value('nama_jabatan');
                    return $jabatan;
                })
                ->rawColumns(['nama_jabatan'])
                ->make(true);
        }
    }
    public function create()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('bagian.create', [
            'title' => 'Tambah Data Divisi',
            'holding' => $holding,
            'data_divisi' => Divisi::all(),
        ]);
    }

    public function insert(Request $request)
    {
        // dd($request->all());
        $holding = request()->segment(count(request()->segments()));
        $validatedData = $request->validate([
            'nama_divisi' => 'required|max:255',
            'nama_bagian' => 'required',
        ]);

        Bagian::create(
            [
                'id' => Uuid::uuid4(),
                'holding' => $holding,
                'nama_bagian' => $validatedData['nama_bagian'],
                'divisi_id' => Divisi::where('id', $validatedData['nama_divisi'])->value('id'),
            ]
        );
        return redirect('/bagian/' . $holding)->with('success', 'Data Berhasil di Tambahkan');
    }

    public function edit($id)
    {
        $holding = request()->segment(count(request()->segments()));
        return view('bagian.edit', [
            'title' => 'Edit Data Divisi',
            'holding' => $holding,
            'data_divisi' => Divisi::all(),
            'data_bagian' => Bagian::with('Divisi')->findOrFail($id)
        ]);
    }

    public function update(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $validatedData = $request->validate([
            'nama_divisi_update' => 'required|max:255',
            'nama_bagian_update' => 'required',
        ]);

        Bagian::where('id', $request->id_bagian)->update(
            [
                'holding' => $holding,
                'nama_bagian' => $validatedData['nama_bagian_update'],
                'divisi_id' => Divisi::where('id', $validatedData['nama_divisi_update'])->value('id'),
            ]
        );
        return redirect('/bagian/' . $holding)->with('success', 'Data Berhasil di Update');
    }

    public function delete(Request $request, $id)
    {
        // dd($request->all());
        $holding = request()->segment(count(request()->segments()));
        $cek_jabatan = Jabatan::where('bagian_id', $id)
            ->where('divisi_id', $request->divisi)
            ->where('holding', $request->holding)
            ->count();
        // dd($cek_jabatan);
        if ($cek_jabatan == 0) {
            $cek_karyawan = User::where('jabatan_id', $id)->where('kontrak_kerja', $holding)->count();
            if ($cek_karyawan == 0) {
                $bagian = Bagian::where('id', $id)->delete();
                return response()->json(['status' => 1]);
            } else {
                return response()->json(['status' => 2]);
            }
        } else {
            return response()->json(['status' => 0]);
        }
    }
}
