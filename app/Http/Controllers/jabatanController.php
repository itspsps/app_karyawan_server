<?php

namespace App\Http\Controllers;

use App\Imports\JabatanImport;
use App\Models\Bagian;
use App\Models\Divisi;
use App\Models\Holding;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\LevelJabatan;
use App\Models\Menu;
use App\Models\RoleUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class jabatanController extends Controller
{
    public function index($holding)
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
        $data_divisi = Divisi::with(['Departemen' => function ($query) {
            $query->orderBy('nama_departemen', 'ASC');
        }])->with(['Jabatan' => function ($query) use ($holding) {
            $query->where('holding', $holding->id);
        }])->where('holding', $holding->id)
            ->orderBy('nama_divisi', 'ASC')
            ->get();
        // dd($data_divisi);
        $data_bagian = Bagian::orderBy('nama_bagian', 'ASC')
            ->where('holding', $holding->id)
            ->get();
        $data_jabatan = Jabatan::with('Bagian')
            ->with('LevelJabatan')
            ->where('holding', $holding->id)->get();

        $level = LevelJabatan::orderBy('level_jabatan', 'ASC')->get();
        // dd($holding);
        return view('admin.jabatan.index', [
            'title' => 'Master Jabatan',
            'holding' => $holding,
            'menus' => $menus,
            'data_jabatan' => $data_jabatan,
            'data_divisi' => $data_divisi,
            'data_bagian' => $data_bagian,
            'get_level' => $level,
        ]);
    }
    public function get_jabatan($id)
    {
        // dd($id);
        $get_jabatan = Jabatan::where('bagian_id', $id)->get();
        echo "<option value=''>Pilih Jabatan...</option>";
        foreach ($get_jabatan as $jabatan) {
            echo "<option value='$jabatan->id'>$jabatan->nama_jabatan</option>";
        }
    }
    public function ImportJabatan(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        Excel::import(new JabatanImport, $request->file_excel);

        return redirect('/jabatan/' . $holding)->with('success', 'Import Jabatan Sukses');
    }
    public function detail_jabatan($id, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $data_divisi = Divisi::with(['Departemen' => function ($query) {
            $query->orderBy('nama_departemen', 'ASC');
        }])->with('Jabatan')
            ->where('holding', $holding->id)->get();
        $divisi = Divisi::with(['Departemen' => function ($query) use ($holding) {
            $query->where('holding', $holding->id);
            $query->orderBy('nama_departemen', 'ASC');
        }])->where('id', $id)
            ->where('holding', $holding->id)
            ->first();

        $data_jabatan = Divisi::with(['Jabatan' => function ($query) use ($id) {
            $query->where('id', $id);
            $query->orderBy('nama_jabatan', 'ASC');
        }])->with(['Departemen' => function ($query) {
            $query->orderBy('nama_departemen', 'ASC');
        }])->where('holding', $holding->id)
            ->get();
        $data_bagian = Bagian::Join('divisis', 'divisis.id', 'bagians.divisi_id')
            ->where('divisis.id', $id)
            ->orderBy('nama_bagian', 'ASC')
            ->where('bagians.holding', $holding->id)
            ->select('bagians.*', 'divisis.nama_divisi')
            ->get();
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
        $level = LevelJabatan::orderBy('level_jabatan', 'ASC')->get();
        return view('admin.jabatan.detail_jabatan', [
            'title' => 'Master Jabatan',
            'holding' => $holding,
            'menus' => $menus,
            'data_divisi' => $data_divisi,
            'divisi' => $divisi,
            'data_jabatan' => $data_jabatan,
            'data_bagian' => $data_bagian,
            'get_level' => $level,
        ]);
    }
    public function datatable(Request $request, $id, $holding)
    {
        // dd($id);
        $holding = Holding::where('holding_code', $holding)->first();
        $table =  Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
            ->where('jabatans.divisi_id', $id)
            ->where('jabatans.holding', $holding->id)
            ->orderBy('jabatans.nama_jabatan', 'ASC')
            ->select('jabatans.*', 'bagians.nama_bagian', 'divisis.nama_divisi', 'divisis.dept_id')
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('jabatan_atasan', function ($row) use ($holding) {
                    $get_atasan = Jabatan::With('Bagian')->where('holding', $holding->id)->where('id', $row->atasan_id)->first();
                    if ($get_atasan == NULL || $get_atasan == '') {
                        $atasan = NULL;
                    } else {
                        if ($get_atasan->Bagian == NULL) {

                            $atasan  = $get_atasan->nama_jabatan;
                        } else {
                            $atasan = $get_atasan->nama_jabatan . ' (' . $get_atasan->Bagian->nama_bagian . ')';
                        }
                    }
                    return $atasan;
                })
                ->addColumn('jumlah_karyawan', function ($row) use ($holding) {
                    $karyawan = Karyawan::leftJoin('users as b', 'b.karyawan_id', 'karyawans.id')
                        ->where('status_aktif', 'AKTIF')
                        ->where('jabatan_id', $row->id)
                        ->orWhere('jabatan1_id', $row->id)
                        ->orWhere('jabatan2_id', $row->id)
                        // ->orWhere('jabatan3_id', $row->id)
                        // ->orWhere('jabatan4_id', $row->id)
                        ->where('b.is_admin', 'user')
                        ->count();
                    if ($karyawan == 0) {
                        $karyawan = $karyawan;
                    } else {
                        $karyawan = $karyawan . '&nbsp; <button id="btn_lihat_karyawan" data-id="' . $row->id . '" data-holding="' . $holding->holding_code . '" type="button" class="btn btn-sm btn-outline-primary">
                    <span class="tf-icons mdi mdi-eye-circle-outline me-1"></span>Lihat
                  </button>';
                    }
                    return $karyawan;
                })
                ->addColumn('jumlah_bawahan', function ($row) use ($holding) {
                    $bawahan = Jabatan::where('atasan_id', $row->id)
                        ->orWhere('atasan2_id', $row->id)
                        ->where('holding', $holding->id)
                        ->count();
                    if ($bawahan == 0) {
                        $bawahan = $bawahan;
                    } else {
                        $bawahan = $bawahan . '&nbsp; <button id="btn_lihat_bawahan" data-id="' . $row->id . '" data-holding="' . $holding->holding_code . '" type="button" class="btn btn-sm btn-outline-info">
                            <span class="tf-icons mdi mdi-eye-circle-outline me-1"></span>Lihat
                          </button>';
                    }
                    return $bawahan;
                })
                ->addColumn('lintas_departemen', function ($row) {
                    if ($row->lintas_departemen == NULL) {
                        $lintas_departemen = '<span class="badge bg-label-secondary">OFF</span>';
                    } else {
                        $lintas_departemen = '<span class="badge bg-label-success">ON</span>';
                    }
                    return $lintas_departemen;
                })
                ->addColumn('option', function ($row) use ($holding) {
                    $btn = '<button id="btn_edit_jabatan" data-atasan="' . $row->atasan_id . '" data-lintas="' . $row->lintas_departemen . '" data-id="' . $row->id . '" data-jabatan="' . $row->nama_jabatan . '" data-departemen="' . $row->dept_id . '" data-divisi="' . $row->nama_divisi . '" data-bagian="' . $row->bagian_id . '" data-level="' . LevelJabatan::where('id', $row->level_id)->value('level_jabatan') . '" data-holding="' . $holding->holding_code . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_jabatan" data-id="' . $row->id . '" data-holding="' . $holding->holding_code . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['jabatan_atasan', 'jumlah_bawahan', 'lintas_departemen', 'nama_bagian', 'jumlah_karyawan', 'option'])
                ->make(true);
        }
    }
    public function bawahan_datatable($id, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $table =  Jabatan::Join('bagians', 'bagians.id', 'jabatans.bagian_id')
            ->where('jabatans.atasan_id', $id)
            ->orWhere('jabatans.atasan2_id', $id)
            ->where('jabatans.holding', $holding->id)
            ->select('jabatans.*', 'bagians.nama_bagian')
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('jabatan_atasan', function ($row) use ($holding) {
                    $get_atasan = Jabatan::With('Bagian')->where('holding', $holding->id)->where('id', $row->atasan_id)->first();
                    if ($get_atasan == NULL || $get_atasan == '') {
                        $atasan = NULL;
                    } else {
                        if ($get_atasan->Bagian == NULL) {

                            $atasan  = $get_atasan->nama_jabatan;
                        } else {
                            $atasan = $get_atasan->nama_jabatan . ' (' . $get_atasan->Bagian->nama_bagian . ')';
                        }
                    }
                    return $atasan;
                })
                ->addColumn('jumlah_karyawan', function ($row) use ($holding) {
                    $karyawan = Karyawan::leftJoin('users as b', 'b.karyawan_id', 'karyawans.id')
                        ->where('status_aktif', 'AKTIF')
                        ->where('jabatan_id', $row->id)
                        ->orWhere('jabatan1_id', $row->id)
                        ->orWhere('jabatan2_id', $row->id)
                        // ->orWhere('jabatan3_id', $row->id)
                        // ->orWhere('jabatan4_id', $row->id)
                        ->where('b.is_admin', 'user')
                        ->count();

                    return $karyawan;
                })
                ->addColumn('lintas_departemen', function ($row) {
                    if ($row->lintas_departemen == NULL) {
                        $lintas_departemen = '<span class="badge bg-label-secondary">OFF</span>';
                    } else {
                        $lintas_departemen = '<span class="badge bg-label-success">ON</span>';
                    }
                    return $lintas_departemen;
                })
                ->rawColumns(['lintas_departemen', 'jumlah_karyawan',])
                ->make(true);
        }
    }
    public function karyawan_datatable(Request $request, $id, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $table =   Karyawan::leftJoin('users as b', 'b.karyawan_id', 'karyawans.id')
            ->where('status_aktif', 'AKTIF')
            ->where('jabatan_id', $id)
            ->orWhere('jabatan1_id', $id)
            ->orWhere('jabatan2_id', $id)
            // ->orWhere('jabatan3_id', $id)
            // ->orWhere('jabatan4_id', $id)
            ->where('b.is_admin', 'user')
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_jabatan', function ($row) use ($holding, $id) {
                    $jabatan = Jabatan::where('holding', $holding->id)->where('id', $id)->value('nama_jabatan');

                    return $jabatan;
                })
                ->addColumn('nama_bagian', function ($row) use ($holding, $id) {
                    // $bagian = NULL;
                    $bagian = Jabatan::With('Bagian')->where('holding', $holding->id)->where('id', $id)->first();
                    if ($bagian->Bagian == NULL) {
                        $bagian = NULL;
                    } else {
                        $bagian = $bagian->Bagian->nama_bagian;
                    }
                    return $bagian;
                })
                ->rawColumns(['nama_jabatan', 'nama_bagian',])
                ->make(true);
        }
    }
    public function get_bagian($id)
    {
        // dd($id);
        $get_bagian = Bagian::where('divisi_id', $id)->get();
        // dd($get_bagian);
        echo "<option value=''>Pilih Bagian...</option>";
        foreach ($get_bagian as $bagian) {
            echo "<option value='$bagian->id'>$bagian->nama_bagian</option>";
        }
    }
    public function get_atasan(Request $request)
    {
        // dd($request->all());
        $dept = Divisi::where('nama_divisi', $request->id_divisi)->where('holding', $request->holding)->first();
        // dd($dept);
        if ($request->level == 0) {
            $get_atasan = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                ->where('level_jabatans.level_jabatan', '=', $request->level)
                ->where('jabatans.nama_jabatan', '=', 'DIREKTUR UTAMA')
                ->where('jabatans.holding', '=', $request->holding)
                ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                ->orderBy('jabatans.nama_jabatan', 'ASC')
                ->get();
            $get_atasan_lintas = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                ->where('level_jabatans.level_jabatan', '=', $request->level)
                ->where('jabatans.nama_jabatan', '=', 'DIREKTUR UTAMA')
                ->where('jabatans.holding', '=', $request->holding)
                ->where('jabatans.lintas_departemen', 'on')
                ->orderBy('jabatans.nama_jabatan', 'ASC')
                ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                ->get();
        } else if ($request->level <= 4) {
            // dd('ok');
            $getatasan = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                ->where('divisis.dept_id', $dept->dept_id)
                ->where('level_jabatans.level_jabatan', '<', $request->level)
                ->where('jabatans.holding', '=', $request->holding)
                ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                ->orderBy('jabatans.nama_jabatan', 'ASC')
                ->first();
            if ($getatasan == '' || $getatasan == NULL) {
                $get_atasan = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                    ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                    ->where('level_jabatans.level_jabatan', '<', $request->level)
                    ->where('jabatans.lintas_departemen', NULL)
                    ->where('jabatans.holding', '=', $request->holding)
                    ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                    ->orderBy('jabatans.nama_jabatan', 'ASC')
                    ->get();
                $get_atasan_lintas = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                    ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                    ->where('jabatans.holding', '=', $request->holding)
                    ->where('level_jabatans.level_jabatan', '<', $request->level)
                    ->where('jabatans.lintas_departemen', 'on')
                    ->orderBy('jabatans.nama_jabatan', 'ASC')
                    ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                    ->get();
            } else {
                $get_atasan = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                    ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                    ->where('divisis.dept_id', $dept->dept_id)
                    ->where('jabatans.holding', '=', $request->holding)
                    ->where('jabatans.lintas_departemen', NULL)
                    ->where('level_jabatans.level_jabatan', '<', $request->level)
                    ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                    ->orderBy('jabatans.nama_jabatan', 'ASC')
                    ->get();
                $get_atasan_lintas = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                    ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                    ->where('jabatans.holding', '=', $request->holding)
                    ->where('level_jabatans.level_jabatan', '<', $request->level)
                    ->where('jabatans.lintas_departemen', 'on')
                    ->orderBy('jabatans.nama_jabatan', 'ASC')
                    ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                    ->get();
            }
        } else {
            // dd($request->holding);
            $get_atasan = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                ->where('divisis.id', $dept->id)
                ->where('divisis.holding', '=', $request->holding)
                ->where('jabatans.holding', '=', $request->holding)
                ->where('jabatans.lintas_departemen', NULL)
                ->where('level_jabatans.level_jabatan', '<', $request->level)
                ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                ->orderBy('jabatans.nama_jabatan', 'ASC')
                ->get();
            // dd($get_atasan);
            $get_atasan_lintas = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                ->where('jabatans.holding', '=', $request->holding)
                ->where('level_jabatans.level_jabatan', '<', $request->level)
                ->where('jabatans.lintas_departemen', 'on')
                ->orderBy('jabatans.nama_jabatan', 'ASC')
                ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                ->get();
        }
        echo "<option value=''>Pilih Jabatan Atasan...</option>";
        if (count($get_atasan_lintas) == 0) {
        } else {
            echo "<optgroup label='Lintas Departemen'>";
            foreach ($get_atasan_lintas as $atasan1) {
                echo "<option value='$atasan1->id'>$atasan1->nama_jabatan | $atasan1->nama_bagian</option>";
            }
            echo "</optgroup>";
        }
        if (count($get_atasan) == 0) {
        } else {
            echo "<optgroup label='Atasan'>";
            foreach ($get_atasan as $atasan) {
                echo "<option value='$atasan->id'>$atasan->nama_jabatan | $atasan->nama_bagian</option>";
            }
            echo "</optgroup>";
        }
    }
    public function get_atasan_edit(Request $request)
    {
        // dd($request->all());
        $dept = Divisi::where('nama_divisi', $request->id_divisi)->where('holding', $request->holding)->first();
        if ($request->level == 0) {
            $get_atasan = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                ->where('level_jabatans.level_jabatan', '=', $request->level)
                ->where('jabatans.id', '!=', $request->id)
                ->where('jabatans.nama_jabatan', '=', 'DIREKTUR UTAMA')
                ->where('jabatans.holding', '=', $request->holding)
                ->orderBy('jabatans.nama_jabatan', 'ASC')
                ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                ->get();
            $get_atasan_lintas = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                ->where('level_jabatans.level_jabatan', '=', $request->level)
                ->where('jabatans.id', '!=', $request->id)
                ->where('jabatans.nama_jabatan', '=', 'DIREKTUR UTAMA')
                ->where('jabatans.holding', '=', $request->holding)
                ->where('jabatans.lintas_departemen', 'on')
                ->orderBy('jabatans.nama_jabatan', 'ASC')
                ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                ->get();
        } else if ($request->level <= 4) {
            $getatasan = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                ->where('divisis.dept_id', $dept->dept_id)
                ->where('level_jabatans.level_jabatan', '<', $request->level)
                ->where('jabatans.holding', '=', $request->holding)
                ->orderBy('jabatans.nama_jabatan', 'ASC')
                ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                ->first();
            if ($getatasan == '' || $getatasan == NULL) {
                $get_atasan = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                    ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                    ->where('jabatans.holding', '=', $request->holding)
                    ->where('jabatans.lintas_departemen', NULL)
                    ->where('level_jabatans.level_jabatan', '<', $request->level)
                    ->orderBy('jabatans.nama_jabatan', 'ASC')
                    ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                    ->get();
                $get_atasan_lintas = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                    ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                    ->where('jabatans.holding', '=', $request->holding)
                    ->where('level_jabatans.level_jabatan', '<', $request->level)
                    ->where('jabatans.lintas_departemen', 'on')
                    ->orderBy('jabatans.nama_jabatan', 'ASC')
                    ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                    ->get();
            } else {
                $get_atasan = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                    ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                    ->where('divisis.id', $dept->id)
                    ->where('jabatans.holding', '=', $request->holding)
                    ->where('jabatans.lintas_departemen', NULL)
                    ->where('level_jabatans.level_jabatan', '<', $request->level)
                    ->orderBy('jabatans.nama_jabatan', 'ASC')
                    ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                    ->get();
                $get_atasan_lintas = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                    ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                    ->where('jabatans.holding', '=', $request->holding)
                    ->where('level_jabatans.level_jabatan', '<', $request->level)
                    ->where('jabatans.lintas_departemen', 'on')
                    ->orderBy('jabatans.nama_jabatan', 'ASC')
                    ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                    ->get();
            }
        } else {
            // dd('ok');
            $get_atasan = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                ->where('divisis.dept_id', $dept->dept_id)
                // ->where('divisis.id', $request->id_divisi)
                ->where('jabatans.holding', '=', $request->holding)
                ->where('level_jabatans.level_jabatan', '<', $request->level)
                ->where('jabatans.lintas_departemen', NULL)
                ->orderBy('jabatans.nama_jabatan', 'ASC')
                ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                ->get();
            $get_atasan_lintas = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                ->where('jabatans.holding', '=', $request->holding)
                ->where('level_jabatans.level_jabatan', '<', $request->level)
                ->where('jabatans.lintas_departemen', 'on')
                ->orderBy('jabatans.nama_jabatan', 'ASC')
                ->select('jabatans.*', 'divisis.nama_divisi', 'level_jabatans.level_jabatan', 'bagians.nama_bagian')
                ->get();
        }
        echo "<option value=''>Pilih Jabatan Atasan...</option>";
        if (count($get_atasan_lintas) == 0) {
        } else {
            echo "<optgroup label='Lintas Departemen'>";
            foreach ($get_atasan_lintas as $atasan1) {
                if ($atasan1->id == $request->atasan) {
                    echo "<option value='$atasan1->id' selected >$atasan1->nama_jabatan | $atasan1->nama_bagian</option>";
                } else {
                    echo "<option value='$atasan1->id'>$atasan1->nama_jabatan | $atasan1->nama_bagian</option>";
                }
            }
            echo "</optgroup>";
        }
        if (count($get_atasan) == 0) {
        } else {
            echo "<optgroup label='Atasan'>";
            foreach ($get_atasan as $atasan) {
                if ($atasan->id == $request->atasan) {
                    echo "<option value='$atasan->id' selected >$atasan->nama_jabatan | $atasan->nama_bagian</option>";
                } else {
                    echo "<option value='$atasan->id'>$atasan->nama_jabatan | $atasan->nama_bagian</option>";
                }
            }
            echo "</optgroup>";
        }
    }
    public function create()
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
        return view('jabatan.create', [
            'title' => 'Tambah Data Jabatan',
            'holding' => $holding,
            'menus' => $menus,
            'get_bagian' => Bagian::get(),
            'get_level' => LevelJabatan::orderBy('level_jabatan', 'ASC')->get(),
        ]);
    }

    public function insert(Request $request, $holding)
    {
        // dd($request->all());
        $holding = Holding::where('holding_code', $holding)->first();
        $validatedData = $request->validate([
            'nama_divisi' => 'required',
            'nama_jabatan' => 'required|max:255',
            'level_jabatan' => 'required',
        ]);

        Jabatan::create(
            [
                'holding' => $holding->id,
                'divisi_id' => Divisi::where('nama_divisi', $validatedData['nama_divisi'])->where('holding', $holding->id)->value('id'),
                'bagian_id' => Bagian::where('id', $request['nama_bagian'])->where('holding', $holding->id)->value('id'),
                'nama_jabatan' => $validatedData['nama_jabatan'],
                'atasan_id' => $request['nama_jabatan_atasan'],
                'lintas_departemen' => $request['lintas_departemen'],
                'level_id' => LevelJabatan::where('level_jabatan', $validatedData['level_jabatan'])->value('id'),
            ]
        );
        return redirect()->back()->with('success', 'data berhasil ditambahkan');
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
        return view('jabatan.edit', [
            'title' => 'Edit Data Jabatan',
            'get_bagian' => Bagian::get(),
            'holding' => $holding,
            'menus' => $menus,
            'get_level' => LevelJabatan::get(),
            'data_jabatan' => Jabatan::with('Bagian')->with('LevelJabatan')->findOrFail($id)
        ]);
    }

    public function update(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        // dd($request->all());
        if ($request['nama_jabatan_atasan_update'] == NULL || $request['nama_jabatan_atasan_update'] == '') {
            // dd('ok');
            $get_atasan = NULL;
            $get_atasan2 = NULL;
        } else {
            $cek_atasan = Jabatan::where('id', $request['nama_jabatan_atasan_update'])->first();
            // dd($request['nama_jabatan_atasan_update'], $get_atasan->atasan_id);
            if ($cek_atasan == NULL || $cek_atasan == '') {
                $get_atasan = Jabatan::where('id', $request['nama_jabatan_atasan_update'])->where('holding', $holding->id)->value('id');
                $get_atasan2 = NULL;
            } else {
                $get_atasan = Jabatan::where('id', $request['nama_jabatan_atasan_update'])->where('holding', $holding->id)->value('id');
                $get_atasan2 = Jabatan::where('id', $cek_atasan->atasan_id)->value('id');
                // dd($get_atasan2);
            }
        }
        $validatedData = $request->validate([
            'nama_divisi_update' => 'required',
            'nama_bagian_update' => 'required',
            'nama_jabatan_update' => 'required|max:255',
            'level_jabatan_update' => 'required',
        ]);
        // dd($validatedData);
        Jabatan::where('id', $request->id_jabatan)->update(
            [
                'holding' => $holding->id,
                'bagian_id' => Bagian::where('id', $validatedData['nama_bagian_update'])->where('holding', $holding->id)->value('id'),
                'nama_jabatan' => $validatedData['nama_jabatan_update'],
                'lintas_departemen' => $request['lintas_departemen_update'],
                'level_id' => LevelJabatan::where('level_jabatan', $validatedData['level_jabatan_update'])->value('id'),
                'atasan_id' => $get_atasan,
                'atasan2_id' => $get_atasan2,
            ]
        );
        return redirect()->back()->with('success', 'data berhasil diupdate');
    }

    public function delete(Request $request, $id)
    {
        // dd($request->all());
        if ($request->holding == 'sp') {
            $cek_karyawan_jabatan = Karyawan::where('jabatan_id', $id)
                ->leftJoin('users as b', 'b.karyawan_id', 'karyawans.id')
                ->orWhere('jabatan1_id', $id)
                ->orWhere('jabatan2_id', $id)
                ->orWhere('jabatan3_id', $id)
                ->orWhere('jabatan4_id', $id)
                ->where('status_aktif', 'AKTIF')
                ->where('b.is_admin', 'user')
                ->count();
        } else if ($request->holding == 'sps') {
            $cek_karyawan_jabatan = Karyawan::where('jabatan_id', $id)
                ->leftJoin('users as b', 'b.karyawan_id', 'karyawans.id')
                ->orWhere('jabatan1_id', $id)
                ->orWhere('jabatan2_id', $id)
                ->orWhere('jabatan3_id', $id)
                ->orWhere('jabatan4_id', $id)
                ->where('status_aktif', 'AKTIF')
                ->where('b.is_admin', 'user')
                ->count();
        } else {
            $cek_karyawan_jabatan = Karyawan::where('jabatan_id', $id)
                ->leftJoin('users as b', 'b.karyawan_id', 'karyawans.id')
                ->orWhere('jabatan1_id', $id)
                ->orWhere('jabatan2_id', $id)
                ->orWhere('jabatan3_id', $id)
                ->orWhere('jabatan4_id', $id)
                ->where('status_aktif', 'AKTIF')
                ->where('b.is_admin', 'user')
                ->count();
        }
        if ($cek_karyawan_jabatan == 0) {
            $jabatan = Jabatan::where('id', $id)->delete();
            return response()->json(['status' => 1]);
        } else {
            return response()->json(['status' => 0]);
        }
        // return redirect('/jabatan/' . $holding)->with('success', 'Data Berhasil di Delete');
    }
}
