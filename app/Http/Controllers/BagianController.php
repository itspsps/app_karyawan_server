<?php

namespace App\Http\Controllers;

use App\Imports\BagianImport;
use App\Models\Bagian;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Holding;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Menu;
use App\Models\RoleUsers;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class BagianController extends Controller
{
    public function index($holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        // $get = Bagian::with('Divisi')->get();
        // dd($get);
        $get_role = RoleUsers::where('role_user_id', Auth::user()->id)->pluck('role_menu_id')->toArray();
        // dd($get_role);
        if (count($get_role) == 0) {
            $roleId = null;
        } else {
            $roleId = $get_role;
        }
        if ($roleId == null) {
            $menus = collect();
        } else {
            $menus = Menu::whereIn('id', function ($query) use ($roleId) {
                $query->select('menu_id')
                    ->from('role_menus')
                    ->whereIn('role_id', $roleId);
            })
                ->whereNull('parent_id') // menu utama
                ->with('children')
                ->where('kategori', 'web')      // load submenunya
                ->orderBy('sort_order')
                ->get();
        }
        return view('admin.bagian.index', [
            'title' => 'Master Divisi',
            'holding' => $holding,
            'menus' => $menus,
            'data_bagian' => Bagian::with('Divisi')->where('holding', $holding->id)->get(),
            'data_dept' => Departemen::orderBy('nama_departemen', 'asc')->where('holding', $holding->id)->get(),
            'data_divisi' => Divisi::orderBy('nama_divisi', 'asc')->where('holding', $holding->id)->get()
        ]);
    }
    public function ImportBagian(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        Excel::import(new BagianImport, $request->file_excel);

        return redirect('/bagian/' . $holding->holding_code)->with('success', 'Import Bagian Sukses');
    }
    public function get_divisi($id, $holding)
    {
        // dd($id);
        $id_holding = Holding::where('holding_code', $holding)->value('id');
        $get_divisi = Divisi::where('dept_id', $id)->where('holding', $id_holding)->get();
        // dd($get_divisi);
        echo "<option value=''>Pilih Divisi...</option>";
        foreach ($get_divisi as $divisi) {
            echo "<option value='$divisi->id'>$divisi->nama_divisi</option>";
        }
    }
    public function datatable(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $table =  Bagian::with([
            'Divisi' =>  function ($query) {
                $query->with(['Departemen' => function ($query) {
                    $query->orderBy('nama_departemen', 'ASC');
                }]);
                $query->orderBy('nama_divisi', 'ASC');
            },
        ])
            ->where('holding', $holding->id)
            ->orderBy('nama_bagian', 'ASC')
            ->get();
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
                    $cek_jabatan = Jabatan::where('bagian_id', $row->id)
                        ->where('divisi_id', $row->divisi_id)
                        ->where('holding', $holding->id)
                        ->count();
                    if ($cek_jabatan == 0) {
                        $jumlah_jabatan = $cek_jabatan;
                    } else {
                        $jumlah_jabatan = $cek_jabatan . '&nbsp; <button id="btn_lihat_jabatan" data-id="' . $row->id . '" data-holding="' . $holding->holding_code . '" type="button" class="btn btn-sm btn-outline-primary">
                    <span class="tf-icons mdi mdi-eye-circle-outline me-1"></span>Lihat
                  </button>';
                    }
                    return $jumlah_jabatan;
                })
                ->addColumn('jumlah_karyawan', function ($row) use ($holding) {
                    $cek_karyawan = Karyawan::where('status_aktif', 'AKTIF')
                        ->where('bagian_id', $row->id)
                        ->orWhere('bagian1_id', $row->id)
                        ->orWhere('bagian2_id', $row->id)
                        ->orWhere('bagian3_id', $row->id)
                        ->orWhere('bagian4_id', $row->id)
                        ->where('kontrak_kerja', $holding->id)
                        ->where('status_aktif', 'AKTIF')
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
                    $btn = '<button id="btn_edit_bagian" data-id="' . $row->id . '" data-dept="' . $row->Divisi->Departemen->id . '" data-divisi="' . $row->divisi_id . '" data-bagian="' . $row->nama_bagian . '" data-holding="' . $holding->holding_code . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_bagian" data-id="' . $row->id . '" data-holding="' . $holding->holding_code . '"  data-divisi="' . $row->divisi_id . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';

                    return $btn;
                })
                ->rawColumns(['nama_departemen', 'nama_divisi', 'jumlah_jabatan', 'jumlah_karyawan', 'option'])
                ->make(true);
        }
    }
    public function jabatan_datatable($id, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $table =  Jabatan::where('bagian_id', $id)
            ->where('holding', $holding->id)
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('jumlah_karyawan', function ($row) use ($holding) {
                    $karyawan = Karyawan::leftJoin('users as b', 'b.karyawan_id', 'karyawans.id')
                        ->where('status_aktif', 'AKTIF')
                        ->where('jabatan_id', $row->id)
                        ->orWhere('jabatan1_id', $row->id)
                        ->orWhere('jabatan2_id', $row->id)
                        ->orWhere('jabatan3_id', $row->id)
                        ->orWhere('jabatan4_id', $row->id)
                        ->where('kontrak_kerja', $holding->id)
                        ->where('b.is_admin', 'user')
                        ->count();
                    return $karyawan;
                })
                ->rawColumns(['jumlah_karyawan'])
                ->make(true);
        }
    }
    public function karyawanjabatan_datatable($id, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $table =   Karyawan::leftJoin('users as b', 'b.karyawan_id', 'karyawans.id')
            ->where('status_aktif', 'AKTIF')
            ->where('bagian_id', $id)
            ->orWhere('bagian1_id', $id)
            ->orWhere('bagian2_id', $id)
            ->orWhere('bagian3_id', $id)
            ->orWhere('bagian4_id', $id)
            ->where('b.is_admin', 'user')
            ->where('kontrak_kerja', $holding->id)
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_jabatan', function ($row) use ($holding) {
                    $jabatan = Jabatan::where('holding', $holding->id)->where('id', $row->jabatan_id)->value('nama_jabatan');
                    return $jabatan;
                })
                ->rawColumns(['nama_jabatan'])
                ->make(true);
        }
    }
    public function create($holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $get_role = RoleUsers::where('role_user_id', Auth::user()->id)->pluck('role_menu_id')->toArray();
        // dd($get_role);
        if (count($get_role) == 0) {
            $roleId = null;
        } else {
            $roleId = $get_role;
        }
        if ($roleId == null) {
            $menus = collect();
        } else {
            $menus = Menu::whereIn('id', function ($query) use ($roleId) {
                $query->select('menu_id')
                    ->from('role_menus')
                    ->whereIn('role_id', $roleId);
            })
                ->whereNull('parent_id') // menu utama
                ->with('children')
                ->where('kategori', 'web')      // load submenunya
                ->orderBy('sort_order')
                ->get();
        }
        return view('bagian.create', [
            'title' => 'Tambah Data Divisi',
            'holding' => $holding,
            'data_divisi' => Divisi::all(),
        ]);
    }

    public function insert(Request $request, $holding)
    {
        // dd($request->all());
        $holding = Holding::where('holding_code', $holding)->first();
        $validatedData = $request->validate([
            'nama_divisi' => 'required|max:255',
            'nama_bagian' => 'required',
        ]);
        try {
            Bagian::create(
                [
                    'id' => Uuid::uuid4(),
                    'holding' => $holding->id,
                    'nama_bagian' => $validatedData['nama_bagian'],
                    'divisi_id' => Divisi::where('id', $validatedData['nama_divisi'])->value('id'),
                ]
            );
            return redirect('/bagian/' . $holding->holding_code)->with('success', 'Data Berhasil di Tambahkan');
        } catch (QueryException $e) {
            Alert::error('Error', $e->getMessage());
            return redirect('/bagian/' . $holding->holding_code)->with('Error', $e->getMessage()); // bisa disembunyikan di production

        }
    }

    public function edit($id, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $get_role = RoleUsers::where('role_user_id', Auth::user()->id)->pluck('role_menu_id')->toArray();
        // dd($get_role);
        if (count($get_role) == 0) {
            $roleId = null;
        } else {
            $roleId = $get_role;
        }
        if ($roleId == null) {
            $menus = collect();
        } else {
            $menus = Menu::whereIn('id', function ($query) use ($roleId) {
                $query->select('menu_id')
                    ->from('role_menus')
                    ->whereIn('role_id', $roleId);
            })
                ->whereNull('parent_id') // menu utama
                ->with('children')
                ->where('kategori', 'web')      // load submenunya
                ->orderBy('sort_order')
                ->get();
        }
        return view('bagian.edit', [
            'title' => 'Edit Data Divisi',
            'holding' => $holding,
            'data_divisi' => Divisi::all(),
            'data_bagian' => Bagian::with('Divisi')->findOrFail($id)
        ]);
    }

    public function update(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $validatedData = $request->validate([
            'nama_divisi_update' => 'required|max:255',
            'nama_bagian_update' => 'required',
        ]);

        Bagian::where('id', $request->id_bagian)->update(
            [
                'holding' => $holding->id,
                'nama_bagian' => $validatedData['nama_bagian_update'],
                'divisi_id' => Divisi::where('id', $validatedData['nama_divisi_update'])->value('id'),
            ]
        );
        return redirect('/bagian/' . $holding->holding_code)->with('success', 'data berhasil diupdate');
    }

    public function get_bagian($id)
    {
        // dd($id);
        $get_bagian = Bagian::where('divisi_id', $id)->get();
        echo "<option value=''>Pilih Bagian...</option>";
        foreach ($get_bagian as $bagian) {
            echo "<option value='$bagian->id'>$bagian->nama_bagian</option>";
        }
    }

    public function delete(Request $request, $id, $holding)
    {
        // dd($request->all());
        $holding = Holding::where('holding_code', $holding)->first();
        $cek_jabatan = Jabatan::where('bagian_id', $id)
            ->where('divisi_id', $request->divisi)
            ->where('holding', $request->holding)
            ->count();
        // dd($cek_jabatan);
        if ($cek_jabatan == 0) {
            $cek_karyawan = Karyawan::where('jabatan_id', $id)->where('kontrak_kerja', $holding->id)->where('status_aktif', 'AKTIF')->count();
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
