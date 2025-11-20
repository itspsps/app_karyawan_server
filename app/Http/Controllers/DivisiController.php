<?php

namespace App\Http\Controllers;

use App\Imports\DivisiImport;
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

class DivisiController extends Controller
{
    public function index($holding)
    {
        $get_holding = Holding::where('holding_code', $holding)->first();
        // dd($get_holding);
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
        if ($get_holding == null) {
            Alert::error('Error', 'Holding Tidak Ditemukan', 3000);
            return redirect()->route('dashboard_holding')->with('error', 'Holding Tidak Ditemukan');
        }
        // $get = Divisi::with('Departemen')->get();
        // dd($get);
        return view('admin.divisi.index', [
            'title' => 'Master Divisi',
            'holding' => $get_holding,
            'menus' => $menus,
            'data_divisi' => Divisi::with('Departemen')->where('holding', $get_holding->id)->get(),
            'data_departemen' => Departemen::orderBy('nama_departemen', 'asc')->where('holding', $get_holding->id)->get()
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
        }])
            ->with(['Karyawan' => function ($query) {
                $query->where('status_aktif', 'AKTIF');
            }])
            ->with(['Bagian' => function ($query) use ($holding) {
                $query->where('holding', $holding->id);
            }])
            ->where('holding', $holding->id)
            ->orderBy('nama_divisi', 'ASC')
            ->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_departemen', function ($row) {
                    $nama_departemen = $row->Departemen->nama_departemen;
                    return $nama_departemen;
                })
                ->addColumn('jumlah_bagian', function ($row) use ($holding) {
                    $cek_bagian = $row->Bagian->count();
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
        $table =  Bagian::with(['Karyawan' => function ($query) use ($holding) {
            $query->where('status_aktif', 'AKTIF')
                ->where('kontrak_kerja', $holding->id);
        }])
            ->where('divisi_id', $id)
            ->where('holding', $holding->id)
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('jumlah_karyawan', function ($row) use ($holding) {
                    $karyawan = $row->Karyawan->count();
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
