<?php

namespace App\Http\Controllers;

use App\Exports\KaryawanExport;
use App\Helpers\WebJsonResponse;
use App\Imports\KaryawanImport;
use App\Imports\KaryawanImportUpdate;
use App\Imports\UsersImport;
use App\Imports\UserUpdateImport;
use App\Models\Cuti;
use App\Models\Jabatan;
use App\Models\Lembur;
use App\Models\User;
use App\Models\MappingShift;
use App\Models\ResetCuti;
use App\Models\Shift;
use App\Models\Sip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;
use App\Models\Bagian;
use App\Models\Cities;
use App\Models\City;
use App\Models\Departemen;
use App\Models\District;
use App\Models\Divisi;
use App\Models\Holding;
use App\Models\Karyawan;
use App\Models\KaryawanKeahlian;
use App\Models\KaryawanNonActive;
use App\Models\KaryawanPendidikan;
use App\Models\KaryawanRiwayat;
use App\Models\Lokasi;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Laravolt\Indonesia\IndonesiaService;
use App\Models\Provincies;
use App\Models\Regencies;
use App\Models\RoleUsers;
use App\Models\Site;
use App\Models\UserNonActive;
use App\Models\Village;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\AssignOp\Div;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\DataTables;

class karyawanController extends Controller
{
    public function index($holding)
    {

        $getHolding = Holding::where('holding_code', $holding)->first();
        if ($getHolding == null) {
            Alert::error('Error', 'Holding Tidak Ditemukan');
            return redirect()->route('dashboard/holding');
        }
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
        $departemen = Departemen::orderBy('nama_departemen', 'ASC')->where('holding', $getHolding->id)->get();
        $user = Karyawan::where('kontrak_kerja', $getHolding->id)->where('status_aktif', 'AKTIF')->get();
        $jabatan = Jabatan::orderBy('nama_jabatan', 'ASC')->where('holding', $getHolding->id)->get();
        $karyawan_laki = Karyawan::where('gender', '1')->where('kontrak_kerja', $getHolding->id)->where('status_aktif', 'AKTIF')->count();
        $karyawan_perempuan = Karyawan::where('gender', '2')->where('kontrak_kerja', $getHolding->id)->where('status_aktif', 'AKTIF')->count();
        $karyawan_office = Karyawan::where('kategori', 'Karyawan Bulanan')->where('kontrak_kerja', $getHolding->id)->where('status_aktif', 'AKTIF')->count();
        $karyawan_shift = Karyawan::where('kategori', 'Karyawan Harian')->where('kontrak_kerja', $getHolding->id)->where('status_aktif', 'AKTIF')->count();
        return view('admin.karyawan.index', [
            'holding' => $getHolding,
            'title' => 'Karyawan',
            "data_departemen" => $departemen,
            'data_user' => $user,
            "data_jabatan" => $jabatan,
            "data_lokasi" => Lokasi::orderBy('nama_lokasi', 'ASC')->get(),
            "karyawan_laki" => $karyawan_laki,
            "karyawan_perempuan" => $karyawan_perempuan,
            "karyawan_office" => $karyawan_office,
            "karyawan_shift" => $karyawan_shift,
            'menus' => $menus
        ]);
    }

    public function karyawan_non_aktif()
    {

        $holding = request()->segment(count(request()->segments()));
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
        return view('admin.karyawan.karyawan_non_aktif', [
            // return view('karyawan.index', [
            'title' => 'Karyawan',
            'menus' => $menus,
            "data_departemen" => Departemen::orderBy('nama_departemen', 'ASC')->where('holding', $holding)->get(),
            'holding' => $holding,
            'data_user' => Karyawan::where('kontrak_kerja', $holding)->where('status_aktif', 'NON AKTIF')->get(),
            "data_jabatan" => Jabatan::orderBy('nama_jabatan', 'ASC')->where('holding', $holding)->get(),
            "data_lokasi" => Lokasi::orderBy('lokasi_kantor', 'ASC')->get(),
            "karyawan_laki" => Karyawan::where('gender', "1")->where('kontrak_kerja', $holding)->where('status_aktif', 'NON AKTIF')->count(),
            "karyawan_perempuan" => Karyawan::where('gender', "2")->where('kontrak_kerja', $holding)->where('status_aktif', 'NON AKTIF')->count(),
            "karyawan_office" => Karyawan::where('kategori', 'Karyawan Bulanan')->where('kontrak_kerja', $holding)->where('status_aktif', 'NON AKTIF')->count(),
            "karyawan_shift" => Karyawan::where('kategori', 'Karyawan Harian')->where('kontrak_kerja', $holding)->where('status_aktif', 'NON AKTIF')->count(),
        ]);
    }
    public function karyawan_ingin_bergabung()
    {

        $holding = request()->segment(count(request()->segments()));
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
        return view('admin.karyawan.karyawan_ingin_bergabung', [
            // return view('karyawan.index', [
            'title' => 'Karyawan',
            "data_departemen" => Departemen::orderBy('nama_departemen', 'ASC')->where('holding', $holding)->get(),
            'holding' => $holding,
            'menus' => $menus,
            'data_user' => Karyawan::where('kontrak_kerja', $holding)->where('status_aktif', 'WAITING')->get(),
            "data_jabatan" => Jabatan::orderBy('nama_jabatan', 'ASC')->where('holding', $holding)->get(),
            "data_lokasi" => Lokasi::orderBy('lokasi_kantor', 'ASC')->get(),
            "karyawan_laki" => Karyawan::where('gender', "1")->where('kontrak_kerja', $holding)->where('status_aktif', 'WAITING')->count(),
            "karyawan_perempuan" => Karyawan::where('gender', "2")->where('kontrak_kerja', $holding)->where('status_aktif', 'WAITING')->count(),
            "karyawan_office" => Karyawan::where('kategori', 'Karyawan Bulanan')->where('kontrak_kerja', $holding)->where('status_aktif', 'WAITING')->count(),
            "karyawan_shift" => Karyawan::where('kategori', 'Karyawan Harian')->where('kontrak_kerja', $holding)->where('status_aktif', 'WAITING')->count(),
        ]);
    }
    public function database_karyawan_non_aktif(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $table = Karyawan::with('Departemen')
            ->with('Divisi')
            ->with('Jabatan')
            ->where('kontrak_kerja', $holding)
            ->where('status_aktif', 'NON AKTIF')
            ->where('kategori', 'Karyawan Bulanan')
            ->orderBy('id', 'DESC')
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nomor_identitas_karyawan', function ($row) use ($holding) {

                    $nomor_identitas_karyawan = $row->nomor_identitas_karyawan;

                    return $nomor_identitas_karyawan;
                })
                ->addColumn('name', function ($row) use ($holding) {

                    $name = $row->name;

                    return $name;
                })
                ->addColumn('telepon', function ($row) use ($holding) {

                    $telepon = $row->telepon;

                    return $telepon;
                })
                ->addColumn('tgl_mulai_kontrak', function ($row) use ($holding) {

                    $tgl_mulai_kontrak = Carbon::parse($row->tgl_mulai_kontrak)->isoFormat('DD MMMM YYYY');

                    return $tgl_mulai_kontrak;
                })
                ->addColumn('tgl_selesai_kontrak', function ($row) use ($holding) {

                    $tgl_selesai_kontrak = Carbon::parse($row->tgl_selesai_kontrak)->isoFormat('DD MMMM YYYY');

                    return $tgl_selesai_kontrak;
                })
                ->addColumn('tgl_selesai_kontrak', function ($row) use ($holding) {

                    $tgl_selesai_kontrak = Carbon::parse($row->tgl_selesai_kontrak)->isoFormat('DD MMMM YYYY');

                    return $tgl_selesai_kontrak;
                })
                ->addColumn('penempatan_kerja', function ($row) use ($holding) {

                    $penempatan_kerja = $row->penempatan_kerja;

                    return $penempatan_kerja;
                })
                ->addColumn('kontrak_kerja', function ($row) use ($holding) {

                    if ($row->kontrak_kerja == 'SP') {
                        $kontrak_kerja = 'CV. SUMBER PANGAN';
                    } else if ($row->kontrak_kerja == 'SPS') {
                        $kontrak_kerja = 'PT. SURYA PANGAN SEMESTA';
                    } else {
                        $kontrak_kerja = 'CV. SURYA INTI PANGAN';
                    }

                    return $kontrak_kerja;
                })
                ->addColumn('email', function ($row) use ($holding) {

                    $email = $row->email;

                    return $email;
                })
                ->addColumn('nama_divisi', function ($row) use ($holding) {

                    if ($row->divisi_id == '' || $row->divisi_id == NULL) {
                        $divisi = NULL;
                    } else {
                        $divisi = $row->Divisi->nama_divisi;
                    }
                    return $divisi;
                })
                ->addColumn('nama_jabatan', function ($row) use ($holding) {

                    if ($row->jabatan_id == '' || $row->jabatan_id == NULL) {
                        $jabatan = NULL;
                    } else {
                        $jabatan = $row->Jabatan->nama_jabatan;
                    }
                    return $jabatan;
                })

                ->rawColumns(['nama_jabatan', 'tgl_mulai_kontrak', 'tgl_selesai_kontrak', 'kontrak_kerja', 'penempatan_kerja', 'telepon', 'email', 'nomor_identitas_karyawan', 'nama_divisi', 'name'])
                ->make(true);
        }
    }
    public function database_karyawan_masa_tenggang_kontrak(Request $request)
    {
        $date_30day = Carbon::now()->addDay('30');
        $date_now = Carbon::now()->addDay('-30');
        $date_now1 = Carbon::now();
        $holding = request()->segment(count(request()->segments()));
        $table = Karyawan::with('Divisi')
            ->with('Jabatan')
            ->where('status_aktif', 'AKTIF')
            ->where('kategori', 'Karyawan Bulanan')
            ->where('kontrak_kerja', $holding)
            ->whereBetween('tgl_selesai_kontrak', [$date_now, $date_30day])
            ->orderBy('name', 'asc')
            ->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_divisi', function ($row) use ($holding) {
                    if ($row->Divisi == '' || $row->Divisi == NULL) {
                        $divisi = NULL;
                    } else {
                        $divisi = $row->Divisi->nama_divisi;
                    }
                    return $divisi;
                })
                ->addColumn('nama_jabatan', function ($row) use ($holding) {
                    if ($row->Jabatan == '' || $row->Jabatan == NULL) {
                        $jabatan = NULL;
                    } else {
                        $jabatan = $row->Jabatan->nama_jabatan;
                    }
                    return $jabatan;
                })
                ->addColumn('tgl_kontrak', function ($row) use ($holding) {
                    if ($row->tgl_mulai_kontrak == NULL || $row->tgl_selesai_kontrak == NULL) {
                        $tgl_kontrak = NULL;
                    } else {
                        $tgl_kontrak = Carbon::parse($row->tgl_mulai_kontrak)->isoFormat('DD MMMM YYYY') . '&nbsp;-&nbsp;' . Carbon::parse($row->tgl_selesai_kontrak)->isoFormat('DD MMMM YYYY');
                    }
                    return $tgl_kontrak;
                })
                ->addColumn('status', function ($row) use ($holding) {
                    $date1 = new DateTime();
                    $date2 = new DateTime($row->tgl_selesai_kontrak);
                    $interval = $date1->diff($date2);
                    $date_now1 = Carbon::now();
                    if ($row->tgl_selesai_kontrak <= $date_now1) {
                        $status =  '<span class="badge bg-label-danger"><i class="mdi mdi-close-octagon-outline"></i> Melebihi Masa Kontrak ' . $interval->format('%a') . ' Hari </span>';
                    } else {
                        $status = '<span class="badge bg-label-warning"><i class="mdi mdi-alert-octagon-outline"></i> Kontrak Kurang ' . $interval->format('%a') . ' Hari </span>';
                    }
                    return $status;
                })
                ->addColumn('option', function ($row) use ($holding) {
                    if ($row->Divisi == NULL) {
                        $divisi = NULL;
                    } else {
                        $divisi = $row->Divisi->nama_divisi;
                    }
                    if ($row->Jabatan == NULL) {
                        $jabatan = NULL;
                    } else {
                        $jabatan = $row->Jabatan->nama_jabatan;
                    }
                    if ($row->kontrak_kerja == 'SP') {
                        $kontrak_kerja = 'CV. SUMBER PANGAN';
                    } else if ($row->kontrak_kerja == 'SPS') {
                        $kontrak_kerja = 'PT. SURYA PANGAN SEMESTA';
                    } else {
                        $kontrak_kerja = 'CV. SURYA INTI PANGAN';
                    }

                    $option = '<button id="btn_perbarui_kontrak" data-id="' . $row->id . '" data-nama="' . $row->name . '" data-divisi="' . $divisi . '" data-jabatan="' . $jabatan . '" data-foto="' . $row->foto_karyawan . '" data-tgl_mulai_kontrak="' . $row->tgl_mulai_kontrak . '" data-tgl_selesai_kontrak="' . $row->tgl_selesai_kontrak . '" data-penempatan_kerja="' . $row->penempatan_kerja . '" data-kontrak_kerja="' . $kontrak_kerja . '" type="button" class="btn btn-xs btn-info waves-effect waves-light"><i class="mdi mdi-update"></i>&nbsp;Perbarui</button></td>';

                    return $option;
                })

                ->rawColumns(['nama_jabatan', 'nama_divisi', 'tgl_kontrak', 'option', 'status'])
                ->make(true);
        }
    }
    public function non_aktif_proses(Request $request)
    {
        // $data                       = new KaryawanNonActive();
        // $data->karyawan_id              = $request->id_nonactive;
        // $data->tanggal_nonactive     = $request->date_now;
        // $data->alasan               = $request->alasan_non_aktif;
        // $data->save();

        $update_user                        = Karyawan::where('id', $request->id_nonactive)->first();
        $update_user->status_aktif          = 'NON AKTIF';
        $update_user->tanggal_nonactive     = $request->date_now;
        $update_user->alasan_nonactive      = $request->alasan_non_aktif;
        $update_user->update();

        return redirect()->back()->with('success', 'Data Berhasil di Simpan');
    }
    public function karyawan_masa_tenggang_kontrak()
    {

        $holding = request()->segment(count(request()->segments()));
        $date_30day = Carbon::now()->addDay('30');
        $date_now = Carbon::now()->addDay('-30');
        $date_now1 = Carbon::now();
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
        return view('admin.karyawan.karyawan_masa_tenggang_kontrak', [
            // return view('karyawan.index', [
            'title' => 'Karyawan',
            "data_departemen" => Departemen::orderBy('nama_departemen', 'ASC')->where('holding', $holding)->get(),
            'holding' => $holding,
            'menus' => $menus,
            'data_user' => Karyawan::where('kontrak_kerja', $holding)->where('status_aktif', 'AKTIF')->get(),
            "data_jabatan" => Jabatan::orderBy('nama_jabatan', 'ASC')->where('holding', $holding)->get(),
            "karyawan_laki" => Karyawan::where('gender', "1")->where('kontrak_kerja', $holding)->where('status_aktif', 'AKTIF')->where('kategori', 'Karyawan Bulanan')->whereBetween('tgl_selesai_kontrak', [$date_now, $date_30day])->count(),
            "karyawan_perempuan" => Karyawan::where('gender', "2")->where('kontrak_kerja', $holding)->where('status_aktif', 'AKTIF')->where('kategori', 'Karyawan Bulanan')->whereBetween('tgl_selesai_kontrak', [$date_now, $date_30day])->count(),
            "karyawan_lebih_kontrak" => Karyawan::where('kategori', 'Karyawan Bulanan')->where('kontrak_kerja', $holding)->where('status_aktif', 'AKTIF')->whereBetween('tgl_selesai_kontrak', [$date_now, $date_30day])->where('tgl_selesai_kontrak', '<=', $date_now1)->count(),
            "karyawan_akan_habis_kontrak" => Karyawan::where('kategori', 'Karyawan Bulanan')->where('kontrak_kerja', $holding)->where('status_aktif', 'AKTIF')->whereBetween('tgl_selesai_kontrak', [$date_now, $date_30day])->where('tgl_selesai_kontrak', '>=', $date_now1)->count(),
        ]);
    }
    public function database_karyawan_ingin_bergabung(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $table = UserNonActive::with(['User' => function ($query) use ($holding) {
            $query->with('Divisi');
            $query->with('Jabatan');
            $query->where('kontrak_kerja', $holding);
            $query->where('status_aktif', 'NON AKTIF');
            $query->where('kategori', 'Karyawan Bulanan');
        }])
            ->orderBy('id', 'DESC')
            ->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nomor_identitas_karyawan', function ($row) use ($holding) {
                    if ($row->User == '' || $row->User == NULL) {
                        $nomor_identitas_karyawan = NULL;
                    } else {
                        $nomor_identitas_karyawan = $row->User->nomor_identitas_karyawan;
                    }
                    return $nomor_identitas_karyawan;
                })
                ->addColumn('name', function ($row) use ($holding) {
                    if ($row->User == '' || $row->User == NULL) {
                        $name = NULL;
                    } else {
                        $name = $row->User->name;
                    }
                    return $name;
                })
                ->addColumn('telepon', function ($row) use ($holding) {
                    if ($row->User == '' || $row->User == NULL) {
                        $telepon = NULL;
                    } else {
                        $telepon = $row->User->telepon;
                    }
                    return $telepon;
                })
                ->addColumn('tgl_mulai_kontrak', function ($row) use ($holding) {
                    if ($row->User == '' || $row->User == NULL) {
                        $tgl_mulai_kontrak = NULL;
                    } else {
                        $tgl_mulai_kontrak = $row->User->tgl_mulai_kontrak;
                    }
                    return $tgl_mulai_kontrak;
                })
                ->addColumn('tgl_selesai_kontrak', function ($row) use ($holding) {
                    if ($row->User == '' || $row->User == NULL) {
                        $tgl_selesai_kontrak = NULL;
                    } else {
                        $tgl_selesai_kontrak = $row->User->tgl_selesai_kontrak;
                    }
                    return $tgl_selesai_kontrak;
                })
                ->addColumn('penempatan_kerja', function ($row) use ($holding) {
                    if ($row->User == '' || $row->User == NULL) {
                        $penempatan_kerja = NULL;
                    } else {
                        $penempatan_kerja = $row->User->penempatan_kerja;
                    }
                    return $penempatan_kerja;
                })
                ->addColumn('kontrak_kerja', function ($row) use ($holding) {
                    if ($row->User == '' || $row->User == NULL) {
                        $kontrak_kerja = NULL;
                    } else {
                        if ($row->User->kontrak_kerja == 'SP') {
                            $kontrak_kerja = 'CV. SUMBER PANGAN';
                        } else if ($row->User->kontrak_kerja == 'SPS') {
                            $kontrak_kerja = 'PT. SURYA PANGAN SEMESTA';
                        } else {
                            $kontrak_kerja = 'CV. SURYA INTI PANGAN';
                        }
                    }
                    return $kontrak_kerja;
                })
                ->addColumn('email', function ($row) use ($holding) {
                    if ($row->User == '' || $row->User == NULL) {
                        $email = NULL;
                    } else {
                        $email = $row->User->email;
                    }
                    return $email;
                })
                ->addColumn('nama_divisi', function ($row) use ($holding) {
                    if ($row->User->divisi_id == '' || $row->User->divisi_id == NULL) {
                        $divisi = NULL;
                    } else {
                        $divisi = $row->User->Divisi->nama_divisi;
                    }
                    return $divisi;
                })
                ->addColumn('nama_jabatan', function ($row) use ($holding) {
                    if ($row->User->jabatan_id == '' || $row->User->jabatan_id == NULL) {
                        $jabatan = NULL;
                    } else {
                        $jabatan = $row->User->Jabatan->nama_jabatan;
                    }
                    return $jabatan;
                })

                ->rawColumns(['nama_jabatan', 'tgl_mulai_kontrak', 'tgl_selesai_kontrak', 'kontrak_kerja', 'penempatan_kerja', 'telepon', 'email', 'nomor_identitas_karyawan', 'nama_divisi', 'name'])
                ->make(true);
        }
    }
    public function update_kontrak_proses(Request $request)
    {
        // dd($request->all());
        if ($request->file_kontrak_kerja == '' || $request->file_kontrak_kerja == NULL) {
            return redirect()->back()->with('error', 'Data Harus Terisi Semua', 1500);
        }
        $extension     = $request->file('file_kontrak_kerja')->extension();
        $file_cv_name         = 'KONTAK_KERJA-' . $request->tgl_mulai_kontrak_baru . '-' . $request->tgl_selesai_kontrak_baru . '_' . $request->id_karyawan . '.' . $extension;
        $path           = Storage::putFileAs('file_kontrak_kerja/', $request->file('file_kontrak_kerja'), $file_cv_name);

        $update_user                                = Karyawan::where('id', $request->id_karyawan)->first();
        $update_user->tgl_mulai_kontrak             = $request->tgl_mulai_kontrak_baru;
        $update_user->tgl_selesai_kontrak           = $request->tgl_selesai_kontrak_baru;
        $update_user->lama_kontrak_kerja            = $request->lama_kontrak_baru;
        $update_user->file_kontrak_kerja            = $file_cv_name;
        $update_user->update();

        return redirect()->back()->with('success', 'Data Berhasil di Simpan');
    }
    public function ImportKaryawan(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $query = Excel::import(new KaryawanImport, $request->file_excel);
        if ($query) {
            return redirect()->back()->with('success', 'Import Karyawan Sukses');
        }
    }
    public function ImportUpdateKaryawan(Request $request)
    {
        // ini_set('max_execution_time', 300);
        $holding = request()->segment(count(request()->segments()));
        // dd($query());
        $import = new KaryawanImportUpdate;
        try {
            Excel::import($import, $request->file_excel);
            return redirect()->back()->with('success', 'Import Karyawan Update Sukses');
        } catch (\InvalidArgumentException $th) {
            // dd($th);
            return redirect()->back()->with('error', 'Import Error ' . $th->getMessage() . ' ' . $th->getLine() . ' ' . $th->getFile());
        }
    }
    public function ExportKaryawan(Request $request, $holding)
    {
        $date = date('YmdHis');
        $get_holding = Holding::where('holding_code', $holding)->first();
        if ($get_holding == null) {
            Alert::error('Error', 'Holding Tidak Ditemukan', 4000);
            return redirect()->route('dashboard_holding')->with('error', 'Holding Tidak Ditemukan', 4000);
        }
        return Excel::download(new KaryawanExport($get_holding), 'Data Karyawan_' . $get_holding->holding_category . '_' . $date . '.xlsx');
    }
    public function download_pdf_karyawan(Request $request)
    {
        set_time_limit(6000);
        $cek_holding = request()->segment(count(request()->segments()));
        if ($cek_holding == 'sp') {
            $holding = 'CV. SUMBER PANGAN';
        } else if ($cek_holding == 'sps') {
            $holding = 'PT. SURYA PANGAN SEMESTA';
        } else {
            $holding = 'CV. SURYA INTI PANGAN';
        }
        $date = date('YmdHis');
        // 'user' => Karyawan::leftJoin('departemens as a', 'a.id', 'karyawans.dept_id')
        //     ->leftJoin('divisis as b', 'b.id', 'karyawans.divisi_id')
        //     ->leftJoin('bagians as c', 'c.id', 'karyawans.bagian_id')
        //     ->leftJoin('jabatans as d', 'd.id', 'karyawans.jabatan_id')
        //     ->leftJoin('divisis as e', 'e.id', 'karyawans.divisi1_id')
        //     ->leftJoin('bagians as f', 'f.id', 'karyawans.bagian1_id')
        //     ->leftJoin('jabatans as g', 'g.id', 'karyawans.jabatan1_id')
        //     ->leftJoin('divisis as h', 'h.id', 'karyawans.divisi2_id')
        //     ->leftJoin('bagians as i', 'i.id', 'karyawans.bagian2_id')
        //     ->leftJoin('jabatans as j', 'j.id', 'karyawans.jabatan2_id')
        //     ->leftJoin('divisis as k', 'k.id', 'karyawans.divisi3_id')
        //     ->leftJoin('bagians as l', 'l.id', 'karyawans.bagian3_id')
        //     ->leftJoin('jabatans as m', 'm.id', 'karyawans.jabatan3_id')
        //     ->leftJoin('divisis as n', 'n.id', 'karyawans.divisi4_id')
        //     ->leftJoin('bagians as o', 'o.id', 'karyawans.bagian4_id')
        //     ->leftJoin('jabatans as p', 'p.id', 'karyawans.jabatan4_id')
        //     ->leftJoin('indonesia_provinces as q', 'q.code', 'karyawans.provinsi')
        //     ->leftJoin('indonesia_cities as r', 'r.code', 'karyawans.kabupaten')
        //     ->leftJoin('indonesia_districts as s', 's.code', 'karyawans.kecamatan')
        //     ->leftJoin('indonesia_villages as t', 't.code', 'karyawans.desa')
        //     ->where('karyawans.kontrak_kerja', $cek_holding)
        //     ->where('karyawans.status_aktif', 'AKTIF')
        //     ->select('nomor_identitas_karyawan', 'karyawans.name', 'nik', 'npwp', 'fullname', 'motto', 'email', 'telepon', 'tempat_lahir', 'tgl_lahir', 'gender', 'tgl_join', 'status_nikah', 'q.name as nama_provinsi', 'r.name as nama_kabupaten', 's.name as nama_kecamatan', 't.name as nama_desa', 'rt', 'rw', 'alamat', 'kuota_cuti_tahunan', 'kategori', 'lama_kontrak_kerja', 'tgl_mulai_kontrak', 'tgl_selesai_kontrak', 'kontrak_kerja', 'penempatan_kerja', 'site_job', 'nama_bank', 'nomor_rekening', 'a.nama_departemen', 'b.nama_divisi', 'c.nama_bagian', 'd.nama_jabatan', 'e.nama_divisi as nama_divisi1', 'f.nama_bagian as nama_bagian1', 'g.nama_jabatan as nama_jabatan1', 'h.nama_divisi as nama_divisi2', 'i.nama_bagian as nama_bagian2', 'j.nama_jabatan as nama_jabatan2', 'k.nama_divisi as nama_divisi3', 'l.nama_bagian as nama_bagian3', 'm.nama_jabatan as nama_jabatan3', 'n.nama_divisi as nama_divisi4', 'o.nama_bagian as nama_bagian4', 'p.nama_jabatan as nama_jabatan4')
        //     ->orderBy('name', 'ASC')
        //     // ->take(100)
        //     ->get(),
        $data = [
            'user' => Karyawan::With('Departemen')
                ->With('Divisi')
                ->With('Bagian')
                ->With('Jabatan')
                ->where('karyawans.kontrak_kerja', $cek_holding)
                ->where('karyawans.status_aktif', 'AKTIF')
                ->orderBy('name', 'ASC')
                // ->take(100)
                ->get(),
            'holding' => $holding,
            'cek_holding' => $cek_holding,
        ];
        // dd($data);
        $pdf = PDF::loadView('admin/karyawan/cetak_pdf_karyawan', $data)->setPaper('F4', 'landscape');
        return $pdf->stream('DATA KARYAWAN' . $holding . '_' . $date . 'pdf');
    }
    public function datatable_bulanan($holding)
    {
        $getHolding = Holding::where('holding_code', $holding)->first();
        $table = Karyawan::select(
            'id',
            'nomor_identitas_karyawan',
            'name',
            'telepon',
            'email',
            'kontrak_kerja',
            'tgl_mulai_kontrak',
            'tgl_selesai_kontrak',
            'kontrak_kerja',
            'divisi_id',
            'penempatan_kerja',
            'jabatan_id'
        )
            ->with('Divisi')
            ->with('Jabatan')
            ->with('PenempatanKerja')
            ->with('KontrakKerja')
            ->where('kontrak_kerja', $getHolding->id)
            ->where('status_aktif', 'AKTIF')
            ->where('kategori', 'Karyawan Bulanan')
            ->orderBy('id', 'DESC')
            // ->limit(10)
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_divisi', function ($row) use ($getHolding) {
                    if ($row->divisi_id == '' || $row->divisi_id == NULL) {
                        $divisi = NULL;
                    } else {
                        $divisi = $row->Divisi->nama_divisi;
                    }
                    return $divisi;
                })
                ->addColumn('kontrak_kerja', function ($row) use ($getHolding) {
                    if ($row->KontrakKerja == '') {
                        $kontrak_kerja = $row->kontrak_kerja;
                    } else {
                        $kontrak_kerja = $row->KontrakKerja->holding_name;
                    }
                    return $kontrak_kerja;
                })
                ->addColumn('penempatan_kerja', function ($row) use ($getHolding) {
                    if ($row->PenempatanKerja == '') {
                        $penempatan_kerja = $row->penempatan_kerja;
                    } else {
                        $penempatan_kerja = $row->PenempatanKerja->site_name;
                    }
                    return $penempatan_kerja;
                })
                ->addColumn('nama_jabatan', function ($row) use ($getHolding) {
                    if ($row->jabatan_id == '' || $row->jabatan_id == NULL) {
                        $jabatan = NULL;
                    } else {
                        $jabatan = $row->Jabatan->nama_jabatan;
                    }
                    return $jabatan;
                })
                ->addColumn('option', function ($row) use ($getHolding) {
                    if ($row->Divisi == 'NULL' || $row->Divisi == '') {
                        $divisi = '-';
                    } else {
                        $divisi = $row->Divisi->nama_divisi;
                    }
                    if ($row->Bagian == 'NULL' || $row->Bagian == '') {
                        $bagian = '-';
                    } else {
                        $bagian = $row->Bagian->nama_bagian;
                    }
                    if ($row->Jabatan == 'NULL' || $row->Jabatan == '') {
                        $jabatan = '-';
                    } else {
                        $jabatan = $row->Jabatan->nama_jabatan;
                    }
                    $btn = '<button id="btndetail_karyawan" data-id="' . $row->id . '" data-holding="' . $getHolding->holding_code . '" class="btn btn-icon btn-success waves-effect waves-light"><span class="tf-icons mdi mdi-eye-outline"></span></button>';
                    // $btn = $btn . '<button id="btn_mapping_shift" data-id="' . $row->id . '" data-holding="' . $holding_category . '" type="button" class="btn btn-icon btn-info waves-effect waves-light"><span class="tf-icons mdi mdi-clock-outline"></span></button>';
                    $btn = $btn . '<button id="btn_non_aktif_karyawan" data-status_aktif="' . $row->status_aktif . '" data-foto="' . $row->foto . '" data-id="' . $row->id . '" data-tgl_mulai_kontrak="' . $row->tgl_mulai_kontrak . '" data-tgl_selesai_kontrak="' . $row->tgl_selesai_kontrak . '" data-nama="' . $row->name . '" data-divisi="' . $divisi . '" data-jabatan="' . $jabatan . '" data-bagian="' . $bagian . '"  data-holding="' . $getHolding->id . '" data-penempatan_kerja="' . $row->penempatan_kerja . '" data-kontrak_kerja="' . $row->kontrak_kerja . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-account-multiple-remove-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['nama_jabatan', 'nama_divisi', 'kontrak_kerja', 'penempatan_kerja', 'option'])
                ->make(true);
        }
    }
    public function datatable_harian($holding)
    {
        $getHolding = Holding::where('holding_code', $holding)->first();
        $holding_category = $getHolding->holding_category;
        $table = Karyawan::where('kontrak_kerja', $holding_category)->where('kategori', 'Karyawan Harian')->where('status_aktif', 'AKTIF')->orderBy('id', 'DESC')->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('option', function ($row) use ($holding_category) {
                    $btn = '<button id="btndetail_karyawan" data-id="' . $row->id . '" data-holding="' . $holding_category . '" class="btn btn-icon btn-success waves-effect waves-light"><span class="tf-icons mdi mdi-eye-outline"></span></button>';
                    $btn = $btn . '<button id="btn_mapping_shift" data-id="' . $row->id . '" data-holding="' . $holding_category . '" type="button" class="btn btn-icon btn-info waves-effect waves-light"><span class="tf-icons mdi mdi-clock-outline"></span></button>';
                    $btn = $btn . '<button id="btn_edit_password" data-id="' . $row->id . '" data-holding="' . $holding_category . '" type="button" class="btn btn-icon btn-secondary waves-effect waves-light"><span class="tf-icons mdi mdi-key-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_karyawan" data-id="' . $row->id . '" data-holding="' . $holding_category . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['option'])
                ->make(true);
        }
    }

    public function get_kabupaten($id)
    {
        // dd($id);
        $get_kabupaten = Cities::where('province_code', $id)->orderBy('name', 'ASC')->get();
        // return $get_kabupaten;
        echo "<option value=''>Pilih Kabupaten...</option>";
        foreach ($get_kabupaten as $kabupaten) {
            echo "<option value='$kabupaten->code'>$kabupaten->name</option>";
        }
    }
    public function get_kecamatan($id)
    {
        // dd($id);
        $get_desa = District::where('city_code', $id)->orderBy('name', 'ASC')->get();
        // return $get_desa;
        echo "<option value=''>Pilih Kecamatan...</option>";
        foreach ($get_desa as $desa) {
            echo "<option value='$desa->code'>$desa->name</option>";
        }
    }
    public function get_desa($id)
    {
        // dd($id);
        $get_kecamatan = Village::where('district_code', $id)->orderBy('name', 'ASC')->get();
        // return $get_kecamatan;
        echo "<option value=''>Pilih Desa...</option>";
        foreach ($get_kecamatan as $kecamatan) {
            echo "<option value='$kecamatan->code'>$kecamatan->name</option>";
        }
    }
    public function get_atasan(Request $request)
    {
        if ($request->holding == 'sp') {
            $kontrak = 'SP';
        } else if ($request->holding == 'sps') {
            $kontrak = 'SPS';
        } else {
            $kontrak = 'SIP';
        }
        // dd($holding);
        $get_user = Karyawan::where('id', $request->id_karyawan)->first();
        $get_level = Jabatan::Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')->where('jabatans.id', $request->id)->first();
        // dd($get_level->level_jabatan);
        if ($get_level->level_jabatan <= 4) {
            $get_atasan = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
                ->Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                // ->where('karyawans.penempatan_kerja', $get_user->penempatan_kerja)
                ->where('karyawans.status_aktif', 'AKTIF')
                ->where('karyawans.dept_id', $get_user->dept_id)
                ->where('level_jabatans.level_jabatan', '<', $get_level->level_jabatan)
                ->select('karyawans.*', 'jabatans.nama_jabatan', 'bagians.nama_bagian')
                ->orderBy('karyawans.name', 'ASC')
                ->get();
        } else {
            $get_atasan = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
                ->Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                ->where('karyawans.status_aktif', 'AKTIF')
                // ->where('karyawans.penempatan_kerja', $get_user->penempatan_kerja)
                // ->where('divisis.id', $request->id_divisi)
                ->where('level_jabatans.level_jabatan', '<', $get_level->level_jabatan)
                ->select('karyawans.*', 'jabatans.nama_jabatan', 'bagians.nama_bagian')
                ->orderBy('karyawans.name', 'ASC')
                ->get();
        }
        echo "<option value=''>Pilih Atasan...</option>";
        foreach ($get_atasan as $atasan) {
            echo "<option value='$atasan->id'>$atasan->name ($atasan->nama_jabatan | $atasan->nama_bagian)</option>";
        }
    }
    public function get_atasan2(Request $request)
    {
        // dd($request->all());
        if ($request->holding == 'sp') {
            $kontrak = 'SP';
        } else if ($request->holding == 'sps') {
            $kontrak = 'SPS';
        } else {
            $kontrak = 'SIP';
        }
        $get_user = Karyawan::where('id', $request->id_karyawan)->first();
        $get_level = Jabatan::Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')->where('jabatans.id', $request->id)->first();
        // dd($get_level);
        if ($get_level == NULL || $get_level == '') {
            $get_atasan = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
                ->Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                ->where('karyawans.status_aktif', 'AKTIF')
                ->where('karyawans.penempatan_kerja', $get_user->penempatan_kerja)
                ->where('karyawans.dept_id', $get_user->dept_id)
                ->where('level_jabatans.level_jabatan', '<', 2)
                ->select('karyawans.*', 'jabatans.nama_jabatan', 'bagians.nama_bagian')
                ->get();
        } else {
            if ($get_level->level_jabatan <= 4) {
                $get_atasan = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
                    ->Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                    ->where('karyawans.status_aktif', 'AKTIF')
                    ->where('karyawans.penempatan_kerja', $get_user->penempatan_kerja)
                    ->where('karyawans.dept_id', $get_user->dept_id)
                    ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                    ->where('level_jabatans.level_jabatan', '<', $get_level->level_jabatan)
                    ->select('karyawans.*', 'jabatans.nama_jabatan', 'bagians.nama_bagian')
                    ->get();
            } else {
                $get_atasan = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
                    ->Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                    ->Join('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                    ->where('karyawans.status_aktif', 'AKTIF')
                    ->where('karyawans.penempatan_kerja', $get_user->penempatan_kerja)
                    ->where('karyawans.dept_id', $get_user->dept_id)
                    // ->where('divisis.id', $request->id_divisi)
                    ->where('level_jabatans.level_jabatan', '<', $get_level->level_jabatan)
                    ->select('karyawans.*', 'jabatans.nama_jabatan', 'bagians.nama_bagian')
                    ->get();
            }
        }
        echo "<option value=''>Pilih Atasan...</option>";
        foreach ($get_atasan as $atasan) {
            echo "<option value='$atasan->id'>$atasan->name ($atasan->nama_jabatan | $atasan->nama_bagian)</option>";
        }
    }
    public function tambahKaryawan($holding)
    {
        $getHolding = Holding::where('holding_code', $holding)->first();
        $getHoldingall = Holding::get();
        // dd($getHoldingall);
        $holding_category = $getHolding->holding_category;
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
        return view('admin.karyawan.tambah_karyawan', [
            'title' => 'Karyawan',
            "data_departemen" => Departemen::orderBy('nama_departemen', 'ASC')->where('holding', $getHolding->id)->get(),
            'holding' => $getHolding,
            'holdingAll' => $getHoldingall,
            'menus' => $menus,
            "data_jabatan" => Jabatan::orderBy('nama_jabatan', 'ASC')->where('holding', $getHolding->id)->get(),
            "data_provinsi" => Provincies::orderBy('name', 'ASC')->get(),
            "data_lokasi" => Lokasi::orderBy('nama_lokasi', 'ASC')->get(),
            "karyawan_laki" => Karyawan::where('gender', 'Laki-Laki')->where('kontrak_kerja', $getHolding->id)->where('status_aktif', 'AKTIF')->count(),
            "karyawan_perempuan" => Karyawan::where('gender', 'Perempuan')->where('kontrak_kerja', $getHolding->id)->where('status_aktif', 'AKTIF')->count(),
            "karyawan_office" => Karyawan::where('kategori', 'Karyawan Bulanan')->where('kontrak_kerja', $getHolding->id)->where('status_aktif', 'AKTIF')->count(),
            "karyawan_shift" => Karyawan::where('kategori', 'Karyawan Harian')->where('kontrak_kerja', $getHolding->id)->where('status_aktif', 'AKTIF')->count(),
        ]);
    }

    public function tambahKaryawanProses(Request $request, $id)
    {
        // dd($request->all());
        $holding = Holding::where('id', $id)->first();
        $rules = [
            'no_karyawan' => 'required|max:14|min:13',
            'no_karyawan' => 'unique:karyawans,nomor_identitas_karyawan,' . $request->no_karyawan,
            'name' => 'required|max:255',
            // 'tgl_lahir' => 'required|max:255',
            // 'tgl_join' => 'required',
        ];

        $customMessages = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute tidak boleh sama',
            'email' => ':attribute format salah',
            'min' => ':attribute Kurang',
            'max' => ':attribute Melampaui Batas Maksimal',

        ];
        $validasi = Validator::make($request->all(), $rules, $customMessages);
        if ($validasi->fails()) {
            $errors = $validasi->errors()->first();
            // dd($errors);
            Alert::error('Gagal', $errors);
            return back()->withInput();
        }

        // $no_karyawan = $holding->holding_number . date('ym', strtotime($request['tgl_join'])) . date('dmy', strtotime($request['tgl_lahir']));
        // dd($no_karyawan);

        // dd($validatedData['provinsi_domisili']);
        $id_karyawan = UUID::uuid4();
        Karyawan::create(
            [
                'id'                                => $id_karyawan,
                'nomor_identitas_karyawan'          => $request->no_karyawan,
                'name'                              => $request->name,
                // 'tgl_lahir'                         => $request->tgl_lahir,
                // 'tgl_join'                          => $request->tgl_join,
                'kontrak_kerja'                     => $id,
                'status_aktif'                      => 'AKTIF',
                'kategori'                          => $request->kategori,
                'created_at'                        => date('Y-m-d H:i:s'),
            ]
        );
        //

        // Merekam aktivitas pengguna
        // ActivityLog::create([
        //     'user_id' => Auth::user()->id,
        //     'activity' => 'create',
        //     'description' => 'Menambahkan data karyawan baru ' . $request->name,
        // ]);
        return redirect('/karyawan/detail/' . $id_karyawan . '/' . $holding->holding_code)->with('success', 'data berhasil ditambahkan');
    }
    public function detail($id, $holding)
    {
        $getHolding = Holding::where('holding_code', $holding)->first();

        $getHoldingall = Holding::get();
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
        $karyawan = Karyawan::with('KontrakKerja')
            ->with([
                'karyawanKesehatan' => function ($query) {
                    $query->orderBy('id_kesehatan', 'ASC');
                },
            ])
            ->with([
                'karyawanKesehatanPengobatan' => function ($query) {
                    $query;
                },
            ])
            ->with([
                'karyawanKesehatanRS' => function ($query) {
                    $query;
                },
            ])
            ->with([
                'karyawanKesehatanKecelakaan' => function ($query) {
                    $query;
                },
            ])
            ->find($id);
        if ($karyawan == NULL) {
            return redirect()->back()->with('error', 'Karyawan Tidak Ada', 1500);
        } else {
            // dd($karyawan);
            return view('admin.karyawan.detail_karyawan', [
                // return view('karyawan.editkaryawan', [
                'title' => 'Detail Karyawan',
                'holding' => $getHolding,
                'menus' => $menus,
                'holdingAll' => $getHoldingall,
                'karyawan' => $karyawan,
                "data_lokasi" => Site::whereNotIn('site_status', ['DEPO'])->orderBy('site_name', 'ASC')->get(),
                "data_lokasi1" => Site::orderBy('site_name', 'ASC')->get(),
                "data_provinsi" => Provincies::orderBy('name', 'ASC')->get(),
            ]);
        }
    }
    public function button_keahlian($id)
    {
        $data_keahlian = KaryawanKeahlian::select()->where('id_karyawan', $id)->count();
        return response()->json([
            'code' => 200,
            // 'data' => $get_data,
            'data_keahlian' => $data_keahlian,
            // 'message' => 'Data Berhasil Diupdate'
        ]);
    }

    public function keahlian_datatable($id)
    {
        $keahlian = KaryawanKeahlian::where('id_karyawan', $id)->get();
        return DataTables::of($keahlian)
            ->addColumn('aksi', function ($row) {
                $btn = '<a href="javascript:void(0)" class="btn_edit_keahlian" data-id_keahlian="' . $row->id_keahlian . '" data-keahlian="' . $row->keahlian . '" data-file_keahlian="' . $row->file_keahlian . '" data-file_url="' . asset('storage/file_keahlian/' . $row->file_keahlian) . '"><i class="mdi mdi-pencil"></i></a>';
                $btn = $btn . ' <a href="javascript:void(0)" id="btn_delete_keahlian" data-id="' . $row->id_keahlian . '"><i class="mdi mdi-delete text-danger"></i></a>';
                return $btn;
            })
            ->addColumn('file', function ($row) {
                if ($row->file_keahlian == null) {
                    return 'Tidak Ada';
                } else {
                    return '<a href="' . asset('storage/file_keahlian/' . $row->file_keahlian) . '" target="_blank" class="btn btn-sm btn-info"><i class="mdi mdi-eye"></i> Lihat</a>';
                }
            })
            ->rawColumns(['aksi', 'file'])
            ->make(true);
    }
    public function add_keahlian(Request $request)
    {
        try {
            // dd($request->all());
            $validatedData = $request->validate(
                [
                    'id_karyawan' => 'required',
                    'nama_keahlian' => 'required',
                    'file_keahlian' => 'nullable|max:5048', // hanya PDF max 5MB
                ],
                [
                    'id_karyawan.required' => 'ID Karyawan wajib diisi',
                    'nama_keahlian.required' => 'Nama Keahlian wajib diisi',
                    'file_keahlian.max' => 'File Keahlian maksimal 5MB',
                ]
            );
            $path = null;
            $filename = null;
            if ($request->hasFile('file_keahlian')) {
                $path = $request->file('file_keahlian')->store('file_keahlian', 'public');
                $filename = basename($path);
            }
            KaryawanKeahlian::create(
                [
                    'id_keahlian' => UUID::uuid4(),
                    'id_karyawan' => $validatedData['id_karyawan'],
                    'keahlian' => $validatedData['nama_keahlian'],
                    'file_keahlian' => $filename,
                    'created_at' => now(),
                ]
            );
            $data_keahlian = KaryawanKeahlian::select()->where('id_karyawan', $request->id_karyawan)->count();
            return response()->json([
                'code' => 200,
                'data_keahlian' => $data_keahlian,
                'message' => 'Data berhasil ditambahkan'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'code' => 402,
                'message' => $e->errors()
            ]);
        }
    }
    public function update_keahlian(Request $request)
    {
        try {
            // dd($request->all());
            $validatedData = $request->validate(
                [
                    'id_karyawan' => 'required',
                    'nama_keahlian' => 'required',
                    'file_keahlian' => 'nullable|max:5048', // hanya PDF max 5MB
                ],
                [
                    'id_karyawan.required' => 'ID Karyawan wajib diisi',
                    'nama_keahlian.required' => 'Nama Keahlian wajib diisi',
                    'file_keahlian.max' => 'File Keahlian maksimal 5MB',
                ]
            );
            $path = null;
            if ($request->hasFile('file_keahlian')) {
                $path = $request->file('file_keahlian')->store('file_keahlian', 'public');
                if ($path) {
                    $filename = basename($path);
                }
            }
            $delete_old = storage_path('app/public/file_keahlian/' . $request->file_keahlian_old);
            if (file_exists($delete_old)) {
                unlink($delete_old);
            }
            KaryawanKeahlian::where('id_keahlian', $request->id_keahlian)->update(
                [
                    'id_karyawan' => $validatedData['id_karyawan'],
                    'keahlian' => $validatedData['nama_keahlian'],
                    'file_keahlian' => $filename ?? null,
                    'created_at' => now(),
                ]
            );
            return response()->json([
                'code' => 200,
                'message' => 'Data berhasil diperbarui'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'code' => 402,
                'message' => $e->errors()
            ]);
        }
    }
    public function delete_keahlian(Request $request, $id)
    {
        $get = KaryawanKeahlian::where('id_keahlian', $request->id_keahlian);
        if ($get->exists()) {
            $cek_old_file = $get->first();
            $get->delete();
            $data_keahlian = KaryawanKeahlian::select()->where('id_karyawan', $id)->count();
            if ($cek_old_file->file_keahlian) {
                $delete_old = storage_path('app/public/file_keahlian/' . $cek_old_file->file_keahlian);
                if (file_exists($delete_old)) {
                    unlink($delete_old);
                }
            }
            return response()->json([
                'code' => 200,
                'data_keahlian' => $data_keahlian,
                'message' => 'Data berhasil Dihapus'
            ]);
        } else {
            return response()->json([
                'code' => 402,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function editBank(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_bank' => 'required',
                'nama_pemilik_rekening' => 'required',
                'nomor_rekening' => 'required',
                // 'nomor_referensi' => 'numeric',
            ],
            [
                'required' => ':attribute Tidak boleh kosong!',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }
        try {
            Karyawan::where('id', $id)->update(
                [
                    'nama_bank'                             => $request->nama_bank,
                    'nama_pemilik_rekening'                 => $request->nama_pemilik_rekening,
                    'nomor_rekening'                        => $request->nomor_rekening,
                    'updated_at'                            => date('Y-m-d H:i:s')
                ]
            );
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data2' => $get_data2,
                'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function editPajak(Request $request, $id)
    {
        // dd($request->all());
        if ($request->status_npwp == 'on') {
            $nama_pemilik_npwp = 'required';
            $npwp = 'required';
        } else if ($request->status_npwp == 'off') {
            $nama_pemilik_npwp = 'nullable';
            $npwp = 'nullable';
        } else {
            $nama_pemilik_npwp = 'nullable';
            $npwp = 'nullable';
        }
        $rules = [
            'nama_pemilik_npwp' => $nama_pemilik_npwp,
            'npwp' => $npwp . '|max:16',
            'unique:karyawans,npwp,' . $request->npwp,
            'ptkp' => 'required',
        ];


        $customMessages = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute tidak boleh sama',
            'email' => ':attribute format salah',
            'min' => ':attribute Kurang',
            'max' => ':attribute Melebihi Batas Maksimal'
        ];
        $validasi = Validator::make($request->all(), $rules, $customMessages);
        // dd($validasi->errors());

        if ($validasi->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validasi->errors()
            ]);
        }
        try {
            Karyawan::where('id', $id)->update(
                [
                    'status_npwp'         => $request->status_npwp,
                    'ptkp'                => $request->ptkp,
                    'nama_pemilik_npwp'   => $request->nama_pemilik_npwp,
                    'npwp'                => $request->npwp,
                    'updated_at'          => date('Y-m-d H:i:s')

                ]
            );
            // ActivityLog::create([
            //     'user_id' => Auth::user()->id,
            //     'activity' => 'update',
            //     'description' => 'Mengubah data karyawan ' . $request->name,
            // ]);
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data2' => $get_data2,
                'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function ijazah_update(Request $request, $id)
    {
        // dd($request->all());
        if ($request->old_ijazah == NULL) {
            $ijazah_val = 'required|mimes:pdf|max:5000';
        } elseif ($request->old_ijazah != NULL) {
            $ijazah_val = 'nullable';
        }
        if ($request->old_transkrip_nilai == NULL) {
            $transkrip_nilai_val = 'required|mimes:pdf|max:5000';
        } elseif ($request->old_transkrip_nilai != NULL) {
            $transkrip_nilai_val = 'nullable';
        }
        $validator = Validator::make(
            $request->all(),
            [
                'ijazah' => $ijazah_val,
                'ipk' => 'required|max:4',
                'transkrip_nilai' => $transkrip_nilai_val,
            ],
            [
                'required' => ':attribute tidak boleh kosong!',
                'max' => ':attribute Melebihi ketentuan!',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()

            ]);
        }
        //memasukkan data
        try {
            if ($request->ijazah != null) {
                if ($request->old_ijazah != null) {
                    if (Storage::disk('ijazah')->exists($request->old_ijazah)) {
                        Storage::disk('ijazah')->delete($request->old_ijazah);
                    }
                }
                $ijazah = $request->file('ijazah')->store('ijazah');
                $ijazah_save = basename($ijazah);
            } else {
                $ijazah_save = $request->old_ijazah;
            }
            if ($request->transkrip_nilai != null) {
                if ($request->old_transkrip_nilai != null) {
                    if (Storage::disk('transkrip_nilai')->exists($request->old_transkrip_nilai)) {
                        Storage::disk('transkrip_nilai')->delete($request->old_transkrip_nilai);
                    }
                }
                $transkrip_nilai = $request->file('transkrip_nilai')->store('transkrip_nilai');
                $transkrip_nilai_save = basename($transkrip_nilai);
            } else {
                $transkrip_nilai_save = $request->old_transkrip_nilai;
            }
            $datadiri = Karyawan::where('id', $id)->first();
            $datadiri->ijazah = $ijazah_save;
            $datadiri->transkrip_nilai = $transkrip_nilai_save;
            $datadiri->ipk = $request->ipk;
            $datadiri->updated_at = date('Y-m-d H:i:s');
            $datadiri->save();
            // $data_user = Karyawan::select()
            //     ->where('users_career_id', Auth::user()->id)
            //     ->with([
            //         'ToUser' => function ($query) {
            //             $query;
            //         }
            //     ])->first();
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data_user' => $data_user,
                'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function editBPJS(Request $request, $id)
    {
        if ($request->bpjs_kesehatan == 'on') {
            $nama_pemilik_bpjs_kesehatan = 'required';
            $no_bpjs_kesehatan = 'required';
            $kelas_bpjs = 'required';
        } else if ($request->bpjs_kesehatan == 'off') {
            $nama_pemilik_bpjs_kesehatan = 'nullable';
            $no_bpjs_kesehatan = 'nullable';
            $kelas_bpjs = 'nullable';
        } else {
            $nama_pemilik_bpjs_kesehatan = 'nullable';
            $no_bpjs_kesehatan = 'nullable';
            $kelas_bpjs = 'nullable';
        }
        if ($request->bpjs_ketenagakerjaan == 'on') {
            $nama_pemilik_bpjs_ketenagakerjaan = 'required';
            $no_bpjs_ketenagakerjaan = 'required';
        } else if ($request->bpjs_ketenagakerjaan == 'off') {
            $nama_pemilik_bpjs_ketenagakerjaan = 'nullable';
            $no_bpjs_ketenagakerjaan = 'nullable';
        } else {
            $nama_pemilik_bpjs_ketenagakerjaan = 'nullable';
            $no_bpjs_ketenagakerjaan = 'nullable';
        }

        $rules = [

            'bpjs_ketenagakerjaan' => 'required',
            'no_bpjs_ketenagakerjaan' => $no_bpjs_ketenagakerjaan . '|max:16',
            'nama_pemilik_bpjs_ketenagakerjaan' => $nama_pemilik_bpjs_ketenagakerjaan,
            'unique:karyawans,bpjs_ketenagakerjaan,' . $request->bpjs_ketenagakerjaan,
            'bpjs_kesehatan' => 'required',
            'nama_pemilik_bpjs_kesehatan' => $nama_pemilik_bpjs_kesehatan,
            'no_bpjs_kesehatan' => $no_bpjs_kesehatan . '|max:16',
            'unique:karyawans,no_bpjs_kesehatan,' . $request->no_bpjs_kesehatan,
            'bpjs_pensiun' => 'required|max:16',
            'kelas_bpjs' => $kelas_bpjs,

        ];
        $customMessages = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute tidak boleh sama',
            'email' => ':attribute format salah',
            'min' => ':attribute Kurang',
            'max' => ':attribute Melebihi Batas Maksimal'
        ];
        $validasi = Validator::make($request->all(), $rules, $customMessages);
        // dd($validasi->errors());
        if ($validasi->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validasi->errors()
            ]);
        }
        try {
            Karyawan::where('id', $id)->update(
                [
                    'bpjs_ketenagakerjaan'                  => $request->bpjs_ketenagakerjaan,
                    'no_bpjs_ketenagakerjaan'               => $request->no_bpjs_ketenagakerjaan,
                    'nama_pemilik_bpjs_ketenagakerjaan'     => $request->nama_pemilik_bpjs_ketenagakerjaan,
                    'bpjs_pensiun'                          => $request->bpjs_pensiun,
                    'bpjs_kesehatan'                        => $request->bpjs_kesehatan,
                    'nama_pemilik_bpjs_kesehatan'           => $request->nama_pemilik_bpjs_kesehatan,
                    'no_bpjs_kesehatan'                     => $request->no_bpjs_kesehatan,
                    'kelas_bpjs'                            => $request->kelas_bpjs,
                    'updated_at'                            => date('Y-m-d H:i:s')

                ]
            );
            // ActivityLog::create([
            //     'user_id' => Auth::user()->id,
            //     'activity' => 'update',
            //     'description' => 'Mengubah data karyawan ' . $request->name,
            // ]);
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data2' => $get_data2,
                'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function editProfil(Request $request, $id)
    {
        // dd($request->all());

        if ($request->status_nomor == "tidak") {
            $status_nomor = 'required';
            $wa = $request->nomor_wa;
        } else if ($request->status_nomor == "ya") {
            $wa = $request->telepon;
            $status_nomor = 'nullable';
            // dd($wa);
        } else {
            $status_nomor = 'required';
        }

        if ($request->pilihan_alamat_domisili == "ya") {
            $provinsi_domisili = 'nullable';
            $kabupaten_domisili = 'nullable';
            $kecamatan_domisili = 'nullable';
            $desa_domisili = 'nullable';
            $rt_domisili = 'nullable|max:255';
            $rw_domisili = 'nullable|max:255';
            $alamat_domisili = 'nullable|max:255';
        } else if ($request->pilihan_alamat_domisili == "tidak") {
            $provinsi_domisili = 'required';
            $kabupaten_domisili = 'required';
            $kecamatan_domisili = 'required';
            $desa_domisili = 'required';
            $rt_domisili = 'required|max:255';
            $rw_domisili = 'required|max:255';
            $alamat_domisili = 'required|max:255';
        } else if ($request->pilihan_alamat_domisili == NULL) {
            $provinsi_domisili = 'nullable';
            $kabupaten_domisili = 'nullable';
            $kecamatan_domisili = 'nullable';
            $desa_domisili = 'nullable';
            $rt_domisili = 'nullable|max:255';
            $rw_domisili = 'nullable|max:255';
            $alamat_domisili = 'nullable|max:255';
        }
        $rules = [
            'name' => 'required|max:255',
            'nik' => 'required|max:16',
            'unique:karyawans,nik,' . $request->nik,
            'email' => 'max:255|nullable',
            'email:rfc,dns',
            'email_address',
            'unique:karyawans,email,' . $request->email,
            'telepon' => 'max:13',
            'nomor_wa' => 'max:13|' . $status_nomor . '|min:11',
            'nullable',
            'min:11',
            'status_nomor' => 'required',
            'tempat_lahir' => 'required|max:255',
            'tgl_lahir' => 'required|max:255',
            // 'golongan_darah' => 'required|max:255',
            'agama' => 'required|max:255',
            'gender' => 'required',
            'status_nikah' => 'required',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'desa' => 'required',
            'rt' => 'required|max:255',
            'rw' => 'required|max:255',
            'alamat' => 'required|max:255',
            'pilihan_alamat_domisili' => 'required|max:11',
            'provinsi_domisili' => $provinsi_domisili,
            'kabupaten_domisili' => $kabupaten_domisili,
            'kecamatan_domisili' => $kecamatan_domisili,
            'desa_domisili' => $desa_domisili,
            'rt_domisili' => $rt_domisili,
            'rw_domisili' => $rw_domisili,
            'alamat_domisili' => $alamat_domisili,

        ];

        $customMessages = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute tidak boleh sama',
            'email' => ':attribute format salah',
            'min' => ':attribute Kurang',
            'max' => ':attribute Melebihi Batas Maksimal'
        ];
        $validasi = Validator::make($request->all(), $rules, $customMessages);
        // dd($validasi->errors());
        if ($validasi->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validasi->errors()
            ]);
        }
        try {
            if ($request->foto_karyawan != null) {
                if ($request->foto_karyawan_old != null) {
                    if (Storage::disk('foto_karyawan')->exists($request->foto_karyawan_old)) {
                        Storage::disk('foto_karyawan')->delete($request->foto_karyawan_old);
                    }
                }
                $foto_karyawan = $request->file('foto_karyawan')->store('foto_karyawan');
                $foto_karyawan_save = basename($foto_karyawan);
            } else {
                $foto_karyawan_save = $request->foto_karyawan_old;
            }
            if ($request->ktp != null) {
                if ($request->ktp_old != null) {
                    if (Storage::disk('ktp')->exists($request->ktp_old)) {
                        Storage::disk('ktp')->delete($request->ktp_old);
                    }
                }
                $ktp = $request->file('ktp')->store('ktp');
                $ktp_save = basename($ktp);
            } else {
                $ktp_save = $request->ktp_old;
            }
            Karyawan::where('id', $id)->update(
                [
                    'foto_karyawan'                         => $foto_karyawan_save,
                    'nik'                                   => $request->nik,
                    'name'                                  => $request->name,
                    'email'                                 => $request->email,
                    'telepon'                               => $request->telepon,
                    'nomor_wa'                              => $wa,
                    'status_nomor'                          => $request->status_nomor,
                    'tempat_lahir'                          => $request->tempat_lahir,
                    'tgl_lahir'                             => $request->tgl_lahir,
                    'agama'                                 => $request->agama,
                    'gender'                                => $request->gender,
                    'status_nikah'                          => $request->status_nikah,
                    'jumlah_anak'                           => $request->jumlah_anak,
                    'ktp'                                   => $ktp_save,
                    'provinsi'                              => $request->provinsi,
                    'kabupaten'                             => $request->kabupaten,
                    'kecamatan'                             => $request->kecamatan,
                    'desa'                                  => $request->desa,
                    'rt'                                    => $request->rt,
                    'rw'                                    => $request->rw,
                    'alamat'                                => $request->alamat,
                    'status_alamat'                         => $request->pilihan_alamat_domisili,
                    'provinsi_domisili'                     => $request->provinsi_domisili,
                    'kabupaten_domisili'                    => $request->kabupaten_domisili,
                    'kecamatan_domisili'                    => $request->kecamatan_domisili,
                    'desa_domisili'                         => $request->desa_domisili,
                    'rt_domisili'                           => $request->rt_domisili,
                    'rw_domisili'                           => $request->rw_domisili,
                    'alamat_domisili'                       => $request->alamat_domisili,
                    'updated_at'                            => date('Y-m-d H:i:s')
                ]
            );
            // ActivityLog::create([
            //     'user_id' => Auth::user()->id,
            //     'activity' => 'update',
            //     'description' => 'Mengubah data karyawan ' . $request->name,
            // ]);
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data2' => $get_data2,
                'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }



        $request->session()->flash('success', 'data berhasil diupdate');
        return redirect()->back();
    }
    public function editKaryawanProses(Request $request, $id)
    {
        if ($request->status_nomor == "tidak") {
            $status_nomor = 'required';
        } else if ($request->status_nomor == "ya") {
            $status_nomor = 'nullable';
        } else {
            $status_nomor = 'required';
        }
        if ($request['kategori'] == 'Karyawan Harian') {
            $rules = [
                'name' => 'required|max:255',
                'nik' => 'required|nullable|max:255',
                'unique:karyawans,nik,' . $request->nik,
                'email' => 'max:255|nullable',
                'unique:karyawans,email,' . $request->email,
                'telepon' => 'max:13|nullable|min:11',
                'status_nomor' => 'required',
                'nomor_wa' => 'max:13|' . $status_nomor . '|min:11',
                'tempat_lahir' => 'required|max:255',
                'tgl_lahir' => 'required|max:255',
                'golongan_darah' => 'nullable|max:255',
                'agama' => 'required|max:255',
                'gender' => 'required',
                'tgl_join' => 'required|max:255',
                'status_nikah' => 'required',
                'strata_pendidikan' => 'required|max:255',
                'instansi_pendidikan' => 'required|max:255',
                'jurusan_akademik' => 'nullable',
                'is_admin' => 'required',
                'alamat' => 'required|max:255',
                'kuota_cuti' => 'required|max:11',
                'penempatan_kerja' => 'required|max:255',
                'provinsi' => 'required',
                'kabupaten' => 'required',
                'kecamatan' => 'required',
                'desa' => 'required',
                'rt' => 'required|max:255',
                'rw' => 'required|max:255',
                'npwp' => 'max:255|nullable',
                'unique:karyawans',
            ];
        } else if ($request['kategori'] == 'Karyawan Bulanan') {
            $lokasi_kerja = Lokasi::where('lokasi_kantor', $request->site_job)->value('kategori_kantor');
            if ($lokasi_kerja == 'all') {
                $kategori_jabatan = 'required';
            } else {
                $kategori_jabatan = 'nullable';
            }
            if ($request->pilihan_alamat_domisili == "ya") {
                $provinsi_domisili = 'nullable';
                $kabupaten_domisili = 'nullable';
                $kecamatan_domisili = 'nullable';
                $desa_domisili = 'nullable';
                $rt_domisili = 'nullable|max:255';
                $rw_domisili = 'nullable|max:255';
                $alamat_domisili = 'nullable|max:255';
            } else if ($request->pilihan_alamat_domisili == "tidak") {
                $provinsi_domisili = 'required';
                $kabupaten_domisili = 'required';
                $kecamatan_domisili = 'required';
                $desa_domisili = 'required';
                $rt_domisili = 'required|max:255';
                $rw_domisili = 'required|max:255';
                $alamat_domisili = 'required|max:255';
            } else if ($request->pilihan_alamat_domisili == NULL) {
                $provinsi_domisili = 'nullable';
                $kabupaten_domisili = 'nullable';
                $kecamatan_domisili = 'nullable';
                $desa_domisili = 'nullable';
                $rt_domisili = 'nullable|max:255';
                $rw_domisili = 'nullable|max:255';
                $alamat_domisili = 'nullable|max:255';
            }
            $rules = [
                'name' => 'required|max:255',
                'nik' => 'required|max:16',
                'unique:karyawans,nik,' . $request->nik,
                'email' => 'max:255|nullable',
                'email:rfc,dns',
                'email_address',
                'unique:karyawans,email,' . $request->email,
                'telepon' => 'max:13',
                'nullable',
                'min:11',
                'status_nomor' => 'required',
                'nomor_wa' => 'max:13|' . $status_nomor . '|min:11',
                'tempat_lahir' => 'required|max:255',
                'tgl_lahir' => 'required|max:255',
                'golongan_darah' => 'required|max:255',
                'agama' => 'required|max:255',
                'gender' => 'required',
                'status_nikah' => 'required',
                'strata_pendidikan' => 'required|max:255',
                'instansi_pendidikan' => 'required|max:255',
                'jurusan_akademik' => 'nullable',
                'kategori' => 'required',
                'kontrak_kerja' => 'required|max:255',
                'kuota_cuti' => 'required|max:11',
                'provinsi' => 'required',
                'kabupaten' => 'required',
                'kecamatan' => 'required',
                'desa' => 'required',
                'rt' => 'required|max:255',
                'rw' => 'required|max:255',
                'alamat' => 'required|max:255',
                'pilihan_alamat_domisili' => 'required|max:11',
                'provinsi_domisili' => $provinsi_domisili,
                'kabupaten_domisili' => $kabupaten_domisili,
                'kecamatan_domisili' => $kecamatan_domisili,
                'desa_domisili' => $desa_domisili,
                'rt_domisili' => $rt_domisili,
                'rw_domisili' => $rw_domisili,
                'alamat_domisili' => $alamat_domisili,
                'site_job' => 'required',
                'penempatan_kerja' => 'required|max:255',
                'kategori_jabatan' => $kategori_jabatan . '|max:255',
                'departemen_id' => 'required|max:255',
                'divisi_id' => 'required|max:255',
                'bagian_id' => 'required|max:255',
                'jabatan_id' => 'required|max:255',
                'departemen1_id' => 'nullable|max:255',
                'divisi1_id' => 'nullable|max:255',
                'bagian1_id' => 'nullable|max:255',
                'jabatan1_id' => 'nullable|max:255',
                'departemen2_id' => 'nullable|max:255',
                'divisi2_id' => 'nullable|max:255',
                'bagian2_id' => 'nullable|max:255',
                'jabatan2_id' => 'nullable|max:255',
                'departemen3_id' => 'nullable|max:255',
                'divisi3_id' => 'nullable|max:255',
                'bagian3_id' => 'nullable|max:255',
                'jabatan3_id' => 'nullable|max:255',
                'departemen4_id' => 'nullable|max:255',
                'divisi4_id' => 'nullable|max:255',
                'bagian4_id' => 'nullable|max:255',
                'jabatan4_id' => 'nullable|max:255',
                'status_npwp' => 'required',
                'ptkp' => 'required',
                'file_cv' => 'max:255',
            ];
        } else {
            $rules = [
                'name' => 'required|max:255',
                'nik' => 'required|max:16',
                'unique:karyawans,nik,' . $request->nik,
                'email' => 'max:255',
                'nullable',
                'email:rfc,dns',
                'email_address',
                'unique:karyawans,email,' . $request->email,
                'telepon' => 'max:13',
                'nullable',
                'min:11',
                'status_nomor' => 'required',
                'nomor_wa' => 'max:13|' . $status_nomor . '|min:11',
                'pilihan_alamat_domisili' => 'required|max:11',
                'tempat_lahir' => 'required|max:255',
                'tgl_lahir' => 'required|max:255',
                'golongan_darah' => 'required|max:255',
                'agama' => 'required|max:255',
                'gender' => 'required',
                'status_nikah' => 'required',
                'strata_pendidikan' => 'required|max:255',
                'instansi_pendidikan' => 'required|max:255',
                'jurusan_akademik' => 'nullable',
                'kategori' => 'required',
                'kontrak_kerja' => 'required|max:255',
                // 'lama_kontrak_kerja' => 'max:255',
                // 'tgl_mulai_kontrak' => 'required',
                // 'max:25',
                // 'tgl_selesai_kontrak' => 'max:25',
                'kuota_cuti' => 'required|max:11',
                'provinsi' => 'required',
                'kabupaten' => 'required',
                'kecamatan' => 'required',
                'desa' => 'required',
                'rt' => 'required|max:255',
                'rw' => 'required|max:255',
                'alamat' => 'required|max:255',
                'site_job' => 'required',
                'penempatan_kerja' => 'required|max:255',
                'departemen_id' => 'required|max:255',
                'divisi_id' => 'required|max:255',
                'bagian_id' => 'required|max:255',
                'jabatan_id' => 'required|max:255',
                'file_cv' => 'max:255',
                'kategori_jabatan' => 'max:255'
            ];
        }


        $customMessages = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute tidak boleh sama',
            'email' => ':attribute format salah',
            'min' => ':attribute Kurang',
            'max' => ':attribute Melebihi Batas Maksimal'
        ];
        $validasi = Validator::make($request->all(), $rules, $customMessages);
        // dd($validasi->errors());
        if ($validasi->fails()) {
            $errors = $validasi->errors()->first();
            // dd($errors);
            Alert::error('Gagal', $errors);
            return back()->withErrors($validasi)->withInput();
        }
        $validatedData = $request->validate($rules, $customMessages);
        $site_job = $validatedData['site_job'];
        // dd($request["addmore"]['4']["jabatan_id"]);


        if ($request['foto_karyawan']) {
            // dd('ok');
            if ($request->foto_karyawan_lama) {
                Storage::delete('foto_karyawan/', $request->foto_karyawan_lama);
            }
            $extension     = $request->file('foto_karyawan')->extension();
            // dd($extension);
            $img_name         = date('y-m-d') . '-' . Uuid::uuid4() . '.' . $extension;
            $path           = Storage::putFileAs('public/foto_karyawan/', $request->file('foto_karyawan'), $img_name);
        } else {
            if ($request->foto_karyawan_lama) {
                $img_name = $request->foto_karyawan_lama;
            } else {
                $img_name = NULL;
            }
        }
        if ($request['file_cv']) {
            // dd('o    ');
            $file_path = 'https://hrd.sumberpangan.store:4430/storage/app/public/file_cv/' . $request->file_cv_lama;
            // dd($file_path);
            if (File::exists($file_path)) {
                // dd('ok1');
                unlink($file_path);
            } else {
                // dd('ok');
            }
            $extension     = $request->file('file_cv')->extension();
            $file_cv_name         = 'CV-' . date('y-m-d') . '-' . Uuid::uuid4() . '.' . $extension;
            $path           = Storage::putFileAs('file_cv/', $request->file('file_cv'), $file_cv_name);
        } else {
            if ($request->file_cv_lama) {
                $file_cv_name = $request->file_cv_lama;
            } else {
                $file_cv_name = NULL;
            }
        }
        if ($validatedData['pilihan_alamat_domisili'] == "tidak") {
            $provinsi = Provincies::where('code', $validatedData['provinsi'])->value('code');
            $provinsi1 = Provincies::where('code', $validatedData['provinsi_domisili'])->value('code');
            $kabupaten = Cities::where('code', $validatedData['kabupaten'])->value('code');
            $kabupaten1 = Cities::where('code', $validatedData['kabupaten_domisili'])->value('code');
            $kecamatan = District::where('code', $validatedData['kecamatan'])->value('code');
            $kecamatan1 = District::where('code', $validatedData['kecamatan_domisili'])->value('code');
            $desa = Village::where('code', $validatedData['desa'])->value('code');
            $desa1 = Village::where('code', $validatedData['desa_domisili'])->value('code');
            $detail_alamat = Provincies::where('code', $provinsi)->value('name') . ' , ' . Cities::where('code', $kabupaten)->value('name') . ' , ' . District::where('code', $kecamatan)->value('name') . ' , ' . Village::where('code', $desa)->value('name') . ' , RT. ' . $validatedData['rt'] . ' , RW. ' . $validatedData['rw'] . ' , ' . $validatedData['alamat'];
            $detail_alamat1 = Provincies::where('code', $provinsi1)->value('name') . ' , ' . Cities::where('code', $kabupaten1)->value('name') . ' , ' . District::where('code', $kecamatan1)->value('name') . ' , ' . Village::where('code', $desa1)->value('name') . ' , RT. ' . $validatedData['rt_domisili'] . ' , RW. ' . $validatedData['rw_domisili'] . ' , ' . $validatedData['alamat_domisili'];
        } else {
            $provinsi = Provincies::where('code', $validatedData['provinsi'])->value('code');
            $provinsi1 = $provinsi;
            $kabupaten = Cities::where('code', $validatedData['kabupaten'])->value('code');
            $kabupaten1 = $kabupaten;
            $kecamatan = District::where('code', $validatedData['kecamatan'])->value('code');
            $kecamatan1 = $kecamatan;
            $desa = Village::where('code', $validatedData['desa'])->value('code');
            $desa1 = $desa;
            $detail_alamat = Provincies::where('code', $provinsi)->value('name') . ' , ' . Cities::where('code', $kabupaten)->value('name') . ' , ' . District::where('code', $kecamatan)->value('name') . ' , ' . Village::where('code', $desa)->value('name') . ' , RT. ' . $validatedData['rt'] . ' , RW. ' . $validatedData['rw'] . ' , ' . $validatedData['alamat'];
            $detail_alamat1 = $detail_alamat;
        }
        // dd($validatedData);
        $holding = request()->segment(count(request()->segments()));
        Karyawan::where('id', $id)->update(
            [
                'name'                                  => $validatedData['name'],
                'nik'                                   => $validatedData['nik'],
                'nama_pemilik_npwp'                     => $validatedData['nama_pemilik_npwp'],
                'npwp'                                  => $validatedData['npwp'],
                'agama'                                 => $validatedData['agama'],
                'golongan_darah'                        => $validatedData['golongan_darah'],
                'status_nomor'                          => $validatedData['status_nomor'],
                'foto_karyawan'                         => $img_name,
                'file_cv'                               => $file_cv_name,
                'email'                                 => $validatedData['email'],
                'telepon'                               => $validatedData['telepon'],
                'tempat_lahir'                          => $validatedData['tempat_lahir'],
                'tgl_lahir'                             => $validatedData['tgl_lahir'],
                'gender'                                => $validatedData['gender'],
                'status_nikah'                          => $validatedData['status_nikah'],
                'strata_pendidikan'                     => $validatedData['strata_pendidikan'],
                'instansi_pendidikan'                   => $validatedData['instansi_pendidikan'],
                'jurusan_akademik'                      => $validatedData['jurusan_akademik'],
                'kuota_cuti_tahunan'                    => $validatedData['kuota_cuti'],
                'site_job'                              => $site_job,
                'kategori'                              => $validatedData['kategori'],
                'kategori_jabatan'                      => $validatedData['kategori_jabatan'],
                'penempatan_kerja'                      => $validatedData['penempatan_kerja'],
                'provinsi'                              => $provinsi,
                'kabupaten'                             => $kabupaten,
                'kecamatan'                             => $kecamatan,
                'desa'                                  => $desa,
                'rt'                                    => $validatedData['rt'],
                'rw'                                    => $validatedData['rw'],
                'detail_alamat'                         => Provincies::where('code', $provinsi)->value('name') . ' , ' . Cities::where('code', $kabupaten)->value('name') . ' , ' . District::where('code', $kecamatan)->value('name') . ' , ' . Village::where('code', $desa)->value('name') . ' , RT. ' . $validatedData['rt'] . ' , RW. ' . $validatedData['rw'] . ' , ' . $validatedData['alamat'],
                'alamat'                                => $validatedData['alamat'],
                'status_alamat'                         => $validatedData['pilihan_alamat_domisili'],
                'provinsi_domisili'                     => $provinsi1,
                'kabupaten_domisili'                    => $kabupaten1,
                'kecamatan_domisili'                    => $kecamatan1,
                'desa_domisili'                         => $desa1,
                'rt_domisili'                           => $validatedData['rt_domisili'],
                'rw_domisili'                           => $validatedData['rw_domisili'],
                'alamat_domisili'                       => $validatedData['alamat_domisili'],
                'detail_alamat_domisili'                => Provincies::where('code', $provinsi1)->value('name') . ' , ' . Cities::where('code', $kabupaten1)->value('name') . ' , ' . District::where('code', $kecamatan1)->value('name') . ' , ' . Village::where('code', $desa1)->value('name') . ' , RT. ' . $validatedData['rt_domisili'] . ' , RW. ' . $validatedData['rw_domisili'] . ' , ' . $validatedData['alamat_domisili'],
                'dept_id'                               => Departemen::where('id', $validatedData["departemen_id"])->value('id'),
                'divisi_id'                             => Divisi::where('id', $validatedData["divisi_id"])->value('id'),
                'bagian_id'                             => Bagian::where('id', $validatedData["bagian_id"])->value('id'),
                'jabatan_id'                            => Jabatan::where('id', $validatedData["jabatan_id"])->value('id'),
                'dept1_id'                              => Departemen::where('id', $validatedData["departemen1_id"])->value('id'),
                'divisi1_id'                            => Divisi::where('id', $validatedData["divisi1_id"])->value('id'),
                'bagian1_id'                            => Bagian::where('id', $validatedData["bagian1_id"])->value('id'),
                'jabatan1_id'                           => Jabatan::where('id', $validatedData["jabatan1_id"])->value('id'),
                'dept2_id'                              => Departemen::where('id', $validatedData["departemen2_id"])->value('id'),
                'divisi2_id'                            => Divisi::where('id', $validatedData["divisi2_id"])->value('id'),
                'bagian2_id'                            => Bagian::where('id', $validatedData["bagian2_id"])->value('id'),
                'jabatan2_id'                           => Jabatan::where('id', $validatedData["jabatan2_id"])->value('id'),
                'dept3_id'                              => Departemen::where('id', $validatedData["departemen3_id"])->value('id'),
                'divisi3_id'                            => Divisi::where('id', $validatedData["divisi3_id"])->value('id'),
                'bagian3_id'                            => Bagian::where('id', $validatedData["bagian3_id"])->value('id'),
                'jabatan3_id'                           => Jabatan::where('id', $validatedData["jabatan3_id"])->value('id'),
                'dept4_id'                              => Departemen::where('id', $validatedData["departemen4_id"])->value('id'),
                'divisi4_id'                            => Divisi::where('id', $validatedData["divisi4_id"])->value('id'),
                'bagian4_id'                            => Bagian::where('id', $validatedData["bagian4_id"])->value('id'),
                'jabatan4_id'                           => Jabatan::where('id', $validatedData["jabatan4_id"])->value('id'),
            ]
        );
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'update',
            'description' => 'Mengubah data karyawan ' . $request->name,
        ]);

        $request->session()->flash('success', 'data berhasil diupdate');
        return redirect()->back();
    }

    public function deleteKaryawan($id)
    {
        $holding = request()->segment(count(request()->segments()));
        $delete = Karyawan::find($id);
        $deleteShift = MappingShift::where('user_id', $id);
        $deleteLembur = Lembur::where('user_id', $id);
        $deleteCuti = Cuti::where('user_id', $id);
        $deleteSip = Sip::where('user_id', $id);
        Storage::delete($delete->foto_karyawan);
        $delete->delete();
        $deleteShift->delete();
        $deleteLembur->delete();
        $deleteCuti->delete();
        $deleteSip->delete();
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'delete',
            'description' => 'Menghapus data karyawan ' . $delete->name,
        ]);
        return redirect()->back()->with('success', 'Data Berhasil di Delete');
    }


    public function shift($id)
    {
        $holding = request()->segment(count(request()->segments()));
        $user_check = Karyawan::where('id', $id)->first();
        if ($user_check->kategori == 'Karyawan Bulanan') {
            if ($user_check->dept_id == NULL || $user_check->divisi_id == NULL || $user_check->jabatan_id == NULL) {
                return redirect()->back()->with('error', 'Jabatan Karyawan Kosong');
            }
        }
        $oke = MappingShift::with('Shift')->where('user_id', $id)->orderBy('id', 'desc')->limit(100)->get();
        // dd($oke);
        $user = Karyawan::with('Jabatan')
            ->with('Divisi')
            ->where('kontrak_kerja', $holding)
            ->where('karyawans.id', $id)
            ->first();
        $jabatan = Jabatan::join('karyawans', function ($join) {
            $join->on('jabatans.id', '=', 'karyawans.jabatan_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan1_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan2_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan3_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan4_id');
        })->where('karyawans.id', $id)->get();
        $divisi = Divisi::join('karyawans', function ($join) {
            $join->on('divisis.id', '=', 'karyawans.divisi_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi1_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi2_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi3_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi4_id');
        })->where('karyawans.id', $id)->get();
        $no = 1;
        $no1 = 1;
        // dd($jabatan);
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
        return view('admin.karyawan.mappingshift', [
            'title' => 'Mapping Shift',
            'karyawan' => $user,
            'holding' => $holding,
            'shift_karyawan' => MappingShift::where('user_id', $id)->orderBy('created_at', 'desc')->limit(100)->get(),
            'shift' => Shift::all(),
            'menus' => $menus,
            'jabatan_karyawan' => $jabatan,
            'divisi_karyawan' => $divisi,
            'no' => $no,
            'no1' => $no1,
        ]);
    }
    public function mapping_shift_datatable(Request $request, $id)
    {
        // dd($request->start_date);
        $holding = request()->segment(count(request()->segments()));
        // dd('ok');
        if (request()->ajax()) {
            if (!empty($request->start_date)) {
                $table = MappingShift::join('shifts', 'mapping_shifts.shift_id', 'shifts.id')
                    ->where('mapping_shifts.user_id', $id)
                    ->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date])
                    ->select('mapping_shifts.*', 'shifts.nama_shift', 'shifts.jam_masuk', 'shifts.jam_keluar')
                    ->orderBy('tanggal_masuk', 'ASC')
                    // ->limit(100)
                    ->get();
                return DataTables::of($table)
                    ->addColumn('option', function ($row) use ($holding) {
                        $btn = '<button id="btn_edit_mapping_shift" type="button" data-id="' . $row->id . '" data-shift="' . $row->shift_id . '"  data-userid="' . $row->user_id . '" data-keterangan="' . $row->status_absen . '" data-tanggal="' . $row->tanggal_masuk . '" data-holding="' . $holding . '" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                        $btn = $btn . '<button id="btn_delete_mapping_shift" data-id="' . $row->id . '" data-holding="' . $holding . '" type="button" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                        return $btn;
                    })
                    ->rawColumns(['option'])
                    ->make(true);
            }
        }
    }
    public function get_departemen(Request $request)
    {
        // dd($request->holding);
        if ($request->holding == 'sp') {
            $holding_1 = 'CV. SUMBER PANGAN';
        } else if ($request->holding == 'sps') {
            $holding_1 = 'PT. SURYA PANGAN SEMESTA';
        } else if ($request->holding == 'sip') {
            $holding_1 = 'CV. SURYA INTI PANGAN';
        } else {
            $holding_1 = NULL;
        }
        $holding = $holding_1;
        $departemen      = Departemen::where('holding', $request->holding)->orderBy('nama_departemen', 'ASC')->get();
        // dd($departemen);
        echo "<option value=''>Pilih Departemen...</option>";
        echo "<optgroup label='Daftar Departemen $holding'>";
        foreach ($departemen as $departemen) {
            echo "<option value='$departemen->id'>$departemen->nama_departemen</option>";
        }
        echo "</optgroup>";
    }
    public function get_divisi($id_departemen)
    {
        // dd($id_departemen);
        $departemen    = Departemen::where('id', $id_departemen)->first();
        if ($departemen) {
            $holding_1 = Holding::where('id', $departemen->holding)->first()->nama_holding;
        } else {
            $holding_1 = NULL;
        }

        // dd($holding);
        $divisi      = Divisi::where('dept_id', $departemen->id)->orderBy('nama_divisi', 'ASC')->get();
        echo "<option value=''>Pilih Divisi...</option>";
        echo "<optgroup label='Daftar Divisi $holding_1'>";
        foreach ($divisi as $divisi) {
            echo "<option value='$divisi->id'>$divisi->nama_divisi</option>";
        }
        echo "</optgroup>";
    }
    public function get_bagian($id_divisi)
    {
        $divisi    = Divisi::where('id', $id_divisi)->first();
        if ($divisi) {
            $holding_1 = Holding::where('id', $divisi->holding)->first()->nama_holding;
        } else {
            $holding_1 = NULL;
        }
        $bagian      = Bagian::where('divisi_id', $divisi->id)->orderBy('nama_bagian', 'ASC')->get();
        echo "<option value=''>Pilih Bagian...</option>";
        echo "<optgroup label='Daftar Bagian $holding_1'>";
        foreach ($bagian as $bagian) {
            echo "<option value='$bagian->id'>$bagian->nama_bagian</option>";
        }
        echo "</optgroup>";
    }
    public function get_jabatan($id_bagian)
    {
        $bagian    = Bagian::where('id', $id_bagian)->first();
        if ($bagian) {
            $holding_1 = Holding::where('id', $bagian->holding)->first()->nama_holding;
        } else {
            $holding_1 = NULL;
        }
        $jabatan      = Jabatan::where('bagian_id', $bagian->id)->orderBy('nama_jabatan', 'ASC')->get();
        echo "<option value=''>Pilih Jabatan...</option>";
        echo "<optgroup label='Daftar Jabatan $holding_1'>";
        foreach ($jabatan as $jabatan) {
            echo "<option value='$jabatan->id'>$jabatan->nama_jabatan</option>";
        }
        echo "</optgroup>";
    }
    public function prosesTambahShift(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        if ($request["tanggal_mulai"] == null) {
            $request["tanggal_mulai"] = $request["tanggal_akhir"];
        } else {
            $request["tanggal_mulai"] = $request["tanggal_mulai"];
        }

        if ($request["tanggal_akhir"] == null) {
            $request["tanggal_akhir"] = $request["tanggal_mulai"];
        } else {
            $request["tanggal_akhir"] = $request["tanggal_akhir"];
        }
        $rules = [
            'shift_id' => 'required',
            'max:255',
            'tanggal_mulai' => 'required',
            'max:16',
            'tanggal_akhir' => 'required',
            'max:16',
        ];

        $customMessages = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute tidak boleh sama',
            'email' => ':attribute format salah',
            'min' => ':attribute Kurang',
            'max' => ':attribute Melebihi Batas Maksimal'
        ];
        $validasi = Validator::make($request->all(), $rules, $customMessages);
        // dd($request->all());
        if ($validasi->fails()) {
            $errors = $validasi->errors()->first();
            // dd($errors);
            Alert::error('Gagal', $errors);
            return back()->withInput();
        }
        $begin = new \DateTime($request["tanggal_mulai"]);
        $end = new \DateTime($request["tanggal_akhir"]);
        $end = $end->modify('+1 day');

        $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
        $daterange = new \DatePeriod($begin, $interval, $end);

        // dd($request->all());
        foreach ($daterange as $date) {
            $tanggal_masuk = $date->format("Y-m-d");
            $tanggal_pulang = $date->format("Y-m-d");
            $malam = $date->modify('+1 day');
            $tanggal_pulang_malam = $malam->format("Y-m-d");
            // dd($tanggal_pulang_malam);

            $week = Carbon::parse($date)->dayOfWeek;
            if ($week == 1) {
                $request["status_absen"] = "LIBUR";
            } else {
                $request["status_absen"] = NULL;
            }
            $cek_date_same = MappingShift::where('tanggal_masuk', $tanggal_masuk)->where('tanggal_pulang', $tanggal_pulang)->where('user_id', $request->user_id)->where('shift_id', $request->shift_id)->count();
            if ($cek_date_same != 0) {
                return redirect()->back()->with('error', 'Data ada yang sama', 5000);
            }
            $request["tanggal_masuk"] = $tanggal_masuk;
            $nama_shift = Shift::where('id', $request['shift_id'])->value('nama_shift');
            if ($nama_shift == 'Malam') {
                $request["tanggal_pulang"] = $tanggal_pulang_malam;
            } else {
                $request["tanggal_pulang"] = $tanggal_pulang;
            }
            // dd($request["tanggal_pulang"]);

            $validatedData = $request->validate([
                'user_id' => 'required',
                'shift_id' => 'required',
                'tanggal_masuk' => 'required',
                'tanggal_pulang' => 'required',
            ]);

            $insert = new MappingShift();
            $insert->user_id = Karyawan::where('id', $validatedData['user_id'])->value('id');
            $insert->nik_karyawan = Karyawan::where('id', $validatedData['user_id'])->value('nomor_identitas_karyawan');
            $insert->nama_karyawan = Karyawan::where('id', $validatedData['user_id'])->value('name');
            $insert->shift_id = Shift::where('id', $validatedData['shift_id'])->value('id');
            $insert->nama_shift = Shift::where('id', $validatedData['shift_id'])->value('nama_shift');
            $insert->tanggal_masuk = $validatedData['tanggal_masuk'];
            $insert->tanggal_pulang = $validatedData['tanggal_pulang'];
            $insert->status_absen = $request['status_absen'];
            $insert->save();
        }
        // dd($week);
        // dd($week);
        $holding = request()->segment(count(request()->segments()));
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'object_id' => $insert->id,
            'kategory_activity' => 'MAPPING SHIFT',
            'activity' => 'TAMBAH MAPPING SHIFT ',
            'description' => 'Menambahkan Jadwal shift karyawan ' . $insert->nama_karyawan . ' Shift ' . $insert->nama_shift,
            'read_status' => 0
        ]);
        return redirect()->back()->with('success', 'data berhasil ditambahkan');
    }

    public function deleteShift(Request $request, $id)
    {
        $holding = request()->segment(count(request()->segments()));
        $delete_mapping = MappingShift::where('id', $id)->first();
        $delete = MappingShift::find($id);
        $delete->delete();
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'kategory_activity' => 'MAPPING SHIFT',
            'activity' => 'HAPUS MAPPING SHIFT ',
            'description' => 'Menghapus Jadwal shift karyawan ' . $delete_mapping->nama_karyawan . ' Shift ' . $delete_mapping->nama_shift,
            'read_status' => 0
        ]);
        return redirect()->back()->with('success', 'Data Berhasil di Delete');
    }

    public function editShift($id)
    {
        $holding = request()->segment(count(request()->segments()));
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
        return view('karyawan.editshift', [
            'title' => 'Edit Shift',
            'menu' => $menus,
            'shift_karyawan' => MappingShift::find($id),
            'holding' => $holding,
            'shift' => Shift::all()
        ]);
    }

    public function prosesEditShift(Request $request)
    {
        // dd('ok');
        date_default_timezone_set('Asia/Jakarta');
        $nama_shift = Shift::where('id', $request['shift_id_update'])->value('nama_shift');
        if ($nama_shift == 'Libur') {
            $request["status_absen"] = "Libur";
        } else if ($nama_shift == 'Malam') {
            $tanggal_pulang = date('Y-m-d', strtotime('+1 days', strtotime($request['tanggal_update'])));
            // dd($tanggal_pulang);
            $request["status_absen"] = NULL;
            $request["tanggal_masuk"] = $request['tanggal_update'];
            $request["tanggal_pulang"] = $tanggal_pulang;
        } else {
            $request["tanggal_masuk"] = $request['tanggal_update'];
            $request["tanggal_pulang"] = $request['tanggal_update'];
            $request["status_absen"] = NULL;
        }
        $rules = [
            'shift_id_update' => 'required',
            'max:255',
            'tanggal_update' => 'required',
            'max:16',
            'keterangan_update' => 'required',
            'max:16',
        ];

        $customMessages = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute tidak boleh sama',
            'email' => ':attribute format salah',
            'min' => ':attribute Kurang',
            'max' => ':attribute Melebihi Batas Maksimal'
        ];
        $validasi = Validator::make($request->all(), $rules, $customMessages);
        // dd($request->all());
        if ($validasi->fails()) {
            $errors = $validasi->errors()->first();
            // dd($errors);
            Alert::error('Gagal', $errors);
            return back()->withInput();
        }
        $validatedData = $request->validate([
            'shift_id_update' => 'required',
            'tanggal_masuk' => 'required',
            'tanggal_pulang' => 'required',
        ]);

        MappingShift::where('id', $request["id_shift"])->update([
            'user_id' => $request['user_id'],
            'shift_id' => Shift::where('id', $validatedData['shift_id_update'])->value('id'),
            'nama_shift' => Shift::where('id', $validatedData['shift_id_update'])->value('nama_shift'),
            'tanggal_masuk' => $validatedData['tanggal_masuk'],
            'tanggal_pulang' => $validatedData['tanggal_pulang'],
        ]);
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'update',
            'description' => 'Mengubah shift karyawan ' . $user_karyawan->name,
        ]);
        $holding = request()->segment(count(request()->segments()));
        return redirect()->back()->with('success', 'data berhasil diupdate');
    }

    public function myProfile()
    {
        return view('karyawan.myprofile', [
            'title' => 'My Profile'
        ]);
    }

    public function myProfileUpdate(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:255',
            'foto_karyawan' => 'image|file|max:10240',
            'telepon' => 'required',
            'password' => 'required',
            'tgl_lahir' => 'required',
            'gender' => 'required',
            'tgl_join' => 'required',
            'status_nikah' => 'required',
            'alamat' => 'required'
        ];


        $userId = Karyawan::find($id);

        if ($request->email != $userId->email) {
            $rules['email'] = 'required|email:dns|unique:karyawans';
        }

        if ($request->username != $userId->username) {
            $rules['username'] = 'required|max:255|unique:karyawans';
        }

        $validatedData = $request->validate($rules);

        if ($request->file('foto_karyawan')) {
            if ($request->foto_karyawan_lama) {
                Storage::delete($request->foto_karyawan_lama);
            }
            $validatedData['foto_karyawan'] = $request->file('foto_karyawan')->store('foto_karyawan');
        }


        Karyawan::where('id', $id)->update($validatedData);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'update',
            'description' => 'Mengubah profile karyawan ' . $request->name,
        ]);
        $request->session()->flash('success', 'data berhasil diupdate');
        return redirect('/my-profile');
    }

    public function editPassMyProfile()
    {
        return view('karyawan.editpassmyprofile', [
            'title' => 'Ganti Password'
        ]);
    }

    public function editPassMyProfileProses(Request $request, $id)
    {
        $validatedData = $request->validate([
            'password' => 'required|min:6|max:255|confirmed',
        ]);

        $validatedData['password'] = Hash::make($request->password);

        Karyawan::where('id', $id)->update($validatedData);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'update',
            'description' => 'Mengubah password karyawan ' . $request->name,
        ]);
        $request->session()->flash('success', 'Password Berhasil di Update');
        return redirect('/my-profile');
    }

    public function resetCuti()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.karyawan.reset_cuti', [
            'title' => 'Master Data Reset Cuti',
            'holding' => $holding,
            'data_cuti' => ResetCuti::first()
        ]);
    }

    public function resetCutiProses(Request $request, $id)
    {
        $holding = request()->segment(count(request()->segments()));
        $validatedData = $request->validate([
            'cuti_dadakan' => 'required',
            'cuti_bersama' => 'required',
            'cuti_menikah' => 'required',
            'cuti_diluar_tanggungan' => 'required',
            'cuti_khusus' => 'required',
            'cuti_melahirkan' => 'required',
            'izin_telat' => 'required',
            'izin_pulang_cepat' => 'required'
        ]);

        ResetCuti::where('id', $id)->update($validatedData);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'update',
            'description' => 'Mengubah master data reset cuti',
        ]);
        return redirect()->back()->with('success', 'Master Cuti Berhasil Diupdate');
    }
}
