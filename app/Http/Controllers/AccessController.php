<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Holding;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Lokasi;
use App\Models\Menu;
use App\Models\Provincies;
use App\Models\Role;
use App\Models\RoleUsers;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class AccessController extends Controller
{
    public function index($holding)
    {
        $get_holding = Holding::where('holding_code', $holding)->first();
        $roleId = Auth::user();
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
        return view('admin.access.index', [
            // return view('karyawan.index', [
            'title' => 'Karyawan',
            'menus' => $menus,
            "data_departemen" => Departemen::all(),
            'holding' => $get_holding,
            'data_user' => Karyawan::where('kontrak_kerja', $get_holding->id)->get(),
            "data_departemen" => Departemen::all(),
            "data_jabatan" => Jabatan::all(),
            "karyawan_laki" => Karyawan::where('gender', "1")->where('kontrak_kerja', $get_holding->id)->count(),
            "karyawan_perempuan" => Karyawan::where('gender', "2")->where('kontrak_kerja', $get_holding->id)->count(),
            "karyawan_office" => Karyawan::where('gender', "1")->where('kontrak_kerja', $get_holding->id)->count(),
            "karyawan_shift" => Karyawan::where('gender', "2")->where('kontrak_kerja', $get_holding->id)->count(),
        ]);
    }
    public function datatable(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $table = User::with(['roleUsers' => function ($query) {
            $query->with('roleMenu');
        }])
            // ->with(['Karyawan' => function ($query) use ($holding) {
            //     $query->with('Departemen', 'Divisi', 'Jabatan')
            //         ->whereIn('kontrak_kerja', [$holding->id, NULL])
            //         ->orderBy('name', 'ASC');
            // }])
            // ->whereHas('Karyawan', function ($query) use ($holding) {
            //     $query->whereIn('kontrak_kerja', [$holding->id, NULL])
            //         ->orderBy('name', 'ASC');
            // })
            ->where('is_admin', 'admin')
            // ->limit(10)
            // ->where('username', 'cerdasbadrus')
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('name', function ($row) use ($holding) {
                    if ($row->Karyawan != null) {
                        $name = $row->Karyawan->name;
                    } else {
                        $name = '-';
                    }
                    $row->name = $name;
                    return $name;
                })
                ->addColumn('departemen', function ($row) use ($holding) {
                    if ($row->Karyawan != null) {
                        if ($row->Karyawan->Departemen == null) {
                            $dept = '-';
                        } else {
                            $dept = $row->Karyawan->Departemen->nama_departemen;
                        }
                    } else {
                        $dept = '-';
                    }
                    $row->departemen = $dept;
                    return $dept;
                })
                ->addColumn('divisi', function ($row) use ($holding) {
                    if ($row->Karyawan != null) {
                        if ($row->Karyawan->Divisi == null) {
                            $divisi = '-';
                        } else {
                            $divisi = $row->Karyawan->Divisi->nama_divisi;
                        }
                    } else {
                        $divisi = '-';
                    }
                    $row->divisi = $divisi;
                    return $divisi;
                })
                ->addColumn('kontrak_kerja', function ($row) use ($holding) {
                    if ($row->Karyawan != null) {
                        if ($row->Karyawan->kontrak_kerja == null) {
                            $kontrak_kerja = '-';
                        } else {
                            $holding = Holding::where('id', $row->Karyawan->kontrak_kerja)->first();
                            $kontrak_kerja = $holding->holding_name;
                        }
                    } else {
                        $kontrak_kerja = '-';
                    }
                    $row->kontrak_kerja = $kontrak_kerja;
                    return $kontrak_kerja;
                })
                ->addColumn('jabatan', function ($row) use ($holding) {
                    if ($row->Karyawan != null) {
                        if ($row->Karyawan->Jabatan == null) {
                            $jabatan = '-';
                        } else {
                            $jabatan = $row->Karyawan->Jabatan->nama_jabatan;
                        }
                    } else {
                        $jabatan = '-';
                    }
                    $row->jabatan = $jabatan;
                    return $jabatan;
                })
                ->addColumn('access', function ($row) use ($holding) {
                    if ($row->roleUsers->isEmpty()) {
                        return '-';
                    }

                    $html = '<ul style="padding-left: 15px; margin:0;">';

                    foreach ($row->roleUsers as $ru) {
                        $role = $ru->roleMenu->role_name ?? '-';
                        $html .= "<li>{$role}</li>";
                    }

                    $html .= '</ul>';

                    return $html;
                })
                ->addColumn('option', function ($row) use ($holding) {
                    $btn = '<button  data-id="' . $row->id . '" data-idkaryawan="' . $row->karyawan_id . '" data-jabatan="' . $row->jabatan . '" data-divisi="' . $row->divisi . '" data-departemen="' . $row->departemen . '" data data-holding="' . $holding->id . '" data-kontrak="' . $row->kontrak_kerja . '" data-access="' . $row->access . '" data-name="' . $row->name . '" class="btn_add_access_karyawan btn btn-icon btn-primary waves-effect waves-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Access"><span class="tf-icons mdi mdi-plus-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_karyawan" data-id="' . $row->id . '" data-holding="' . $holding->id . '" class="btn btn-icon btn-danger waves-effect waves-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Access"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['option', 'name', 'departemen', 'divisi', 'jabatan', 'access', 'kontrak_kerja'])
                ->make(true);
        }
    }
    public function role_access_datatable($id, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $table = Role::with(['menus' => function ($q) {
            $q->orderBy('sort_order', 'ASC');
        }])
            ->with(['roleUsers' => function ($q) {
                $q->with('Users');
            }])

            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_akses', function ($row) use ($holding) {
                    if ($row->role_name != null) {
                        $role_name = $row->role_name;
                    } else {
                        $role_name = '-';
                    }
                    return $role_name;
                })
                ->addColumn('deskripsi', function ($row) use ($holding) {
                    if ($row->role_description != null) {
                        $role_description = $row->role_description;
                    } else {
                        $role_description = '-';
                    }
                    return $role_description;
                })
                ->addColumn('list_menu', function ($row) use ($holding) {
                    if ($row->menus != null) {
                        $list_menu = '<li>' . implode('</li><li>', $row->menus->pluck('name')->toArray()) . '</li>';
                    } else {
                        $list_menu = '-';
                    }
                    return $list_menu;
                })
                ->addColumn('option', function ($row) use ($id) {
                    // 1. Definisikan ID unik
                    $uniqueId = 'menu_id_' . $row->id;
                    if ($row->roleUsers->isEmpty()) {
                        $btn = ' <div class="form-check mb-2"><input class="form-check-input" type="checkbox" id="' . $uniqueId . '" name="menu_id[]" value="' . $row->id . '"><label class="form-check-label" for="' . $uniqueId . '">Pilih</label></div>';
                    } else {
                        $get = $row->roleUsers->where('role_user_id', $id)->first();
                        if ($get == null) {
                            $btn = ' <div class="form-check mb-2"><input class="form-check-input" type="checkbox" id="' . $uniqueId . '" name="menu_id[]" value="' . $row->id . '"><label class="form-check-label" for="' . $uniqueId . '">Pilih</label></div>';
                        } else {
                            $btn = ' <div class="form-check mb-2"><input class="form-check-input" checked type="checkbox" id="' . $uniqueId . '" name="menu_id[]" value="' . $row->id . '"><label class="form-check-label" for="' . $uniqueId . '">Pilih</label></div>';
                        }
                    }
                    // 2. Gunakan ID unik untuk 'id' checkbox dan 'for' label
                    return $btn;
                })
                ->rawColumns(['nama_akses', 'option', 'deskripsi', 'list_menu'])
                ->make(true);
        }
    }
    public function add_access($id, $holding)
    {

        if ($holding == NULL) {
            return response()->json(
                [
                    'message' => 'Data Tidak Ditemukan',
                    'code' => 404
                ]
            );
        }
        try {
            $karyawan = Karyawan::with('Jabatan')
                ->with('Divisi')
                ->with('Divisi1')
                ->with('Divisi2')
                ->with('Jabatan1')
                ->with('Jabatan2')
                ->with('Departemen')
                ->where('kontrak_kerja', $holding)
                ->where('id', $id)
                ->first();
            $user = User::where('karyawan_id', $id)->first();
            $kontrak_kerja = Holding::where('id', $holding)->first();
            return response()->json([
                'code' => 200,
                'user' => $user,
                'karyawan' => $karyawan,
                'kontrak_kerja' => $kontrak_kerja
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'code' => 404
                ]
            );
        }
    }
    public function access_save_add(Request $request, $holding)
    {
        // dd($request->all());
        $holding = Holding::where('holding_code', $holding)->first();
        $user = User::where('id', $request->id)->first();
        // dd($request->menu_id);
        try {
            RoleUsers::where('role_user_id', $user->id)->delete();
            foreach ($request->menu as $menu_id) {
                RoleUsers::create([
                    'role_menu_id' => $menu_id,
                    'role_user_id' => $user->id,
                ]);
            }
            return response()->json(
                [
                    'message' => 'Sukses Menambahkan Akses',
                    'code' => 200
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'code' => 404
                ]
            );
        }
    }
}
