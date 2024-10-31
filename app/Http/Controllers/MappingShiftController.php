<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\MappingShift;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MappingShiftController extends Controller
{
    function mapping_shift_index(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        date_default_timezone_set('Asia/Jakarta');

        // $bulan = date('m');
        // $tahun = date('Y');
        // $hari_per_bulan = cal_days_in_month(CAL_GREGORIAN,$bulan,$tahun);
        $tanggal_mulai = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $title = "Rekap Data Absensi Tanggal " . date('Y-m-01') . " s/d " . date('Y-m-d');

        $user = Karyawan::with('Cuti')->with('Izin')->where('status_aktif', 'AKTIF')->get();
        // dd($user->Cuti->nama_cuti);

        if ($request["mulai"] && $request["akhir"]) {
            $tanggal_mulai = $request["mulai"];
            $tanggal_akhir = $request["akhir"];
            $title = "Rekap Data Absensi Tanggal " . $tanggal_mulai . " s/d " . $tanggal_akhir;
        }
        $departemen = Departemen::where('holding', $holding)->get();
        // dd($user);

        return view('admin.karyawan.karyawan_mappingshift', [
            'tanggal_mulai' => $tanggal_mulai,
            'departemen' => $departemen,
            'holding' => $holding,
            'tanggal_akhir' => $tanggal_akhir,
            'user' => $user
        ]);
    }
    function index()
    {
        $shift = Shift::whereNotIn('nama_shift', ['Office'])->get();
        $user = DB::table('users')->join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
            ->join('departemens', 'departemens.id', '=', 'users.dept_id')
            ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
            ->where('users.id', Auth()->user()->id)->first();


        $user_shift = User::with(['MappingShift' => function ($query) {
            $query->with('Koordinator');
        }])->where('penempatan_kerja', Auth::user()->penempatan_kerja)
            ->where('kategori', 'Karyawan Harian')->get();
        // dd($koordinator);
        // dd($user_shift);
        // dd(Auth::user()->kontrak_kerja);
        return view('users.mapping_shift.index', [
            'user' => $user,
            'shift' => $shift,
            'user_shift' => $user_shift
        ]);
    }
    public function get_divisi(Request $request)
    {
        // dd($request->all());
        $id_departemen    = $request->departemen_filter;

        $divisi      = Divisi::where('dept_id', $id_departemen)->where('holding', $request->holding)->orderBy('nama_divisi', 'ASC')->get();
        echo "<option value=''>Pilih Divisi...</option>";
        foreach ($divisi as $divisi) {
            echo "<option value='$divisi->id'>$divisi->nama_divisi</option>";
        }
    }
    public function get_bagian(Request $request)
    {
        $id_divisi    = $request->divisi_filter;

        $bagian      = Bagian::where('divisi_id', $id_divisi)->where('holding', $request->holding)->orderBy('nama_bagian', 'ASC')->get();
        echo "<option value=''>Pilih Bagian...</option>";
        foreach ($bagian as $bagian) {
            echo "<option value='$bagian->id'>$bagian->nama_bagian</option>";
        }
    }
    public function get_jabatan(Request $request)
    {
        $id_bagian    = $request->bagian_filter;
        $jabatan      = Jabatan::where('bagian_id', $id_bagian)->where('holding', $request->holding)->orderBy('nama_jabatan', 'ASC')->get();
        echo "<option value=''>Pilih Jabatan...</option>";
        foreach ($jabatan as $jabatan) {
            echo "<option value='$jabatan->id'>$jabatan->nama_jabatan</option>";
        }
    }
    public function prosesAddMappingShift(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');


        // dd($request->all());

        $begin = new \DateTime($request["tanggal_mulai"]);
        $end = new \DateTime($request["tanggal_akhir"]);
        $end = $end->modify('+1 day');

        $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
        $daterange = new \DatePeriod($begin, $interval, $end);


        foreach ($daterange as $date) {
            $tanggal = $date->format("Y-m-d");

            if ($request["shift"] == '3ac53e9a-84d6-445e-9b48-fdb8a6b02cb2') {
                $request["status_absen"] = "Libur";
            } else {
                $request["status_absen"] = "Tidak Masuk";
            }

            $request["tanggal"] = $tanggal;

            $validatedData = $request->validate([
                'id_user' => 'required',
                'shift' => 'required',
                'tanggal' => 'required',
                'lokasi_bekerja' => 'required',
                'koordinator' => 'required',
                'status_absen' => 'required',
            ]);

            MappingShift::insert([
                'user_id' => User::where('id', $validatedData['id_user'])->value('id'),
                'shift_id' => Shift::where('id', $validatedData['shift'])->value('id'),
                'koordinator_id' => User::where('id', $validatedData['koordinator'])->value('id'),
                'lokasi_bekerja' => $validatedData['lokasi_bekerja'],
                'tanggal' => $validatedData['tanggal'],
                'status_absen' => $validatedData['status_absen'],
            ]);
        }
        $request->session()->flash('mappingshiftsuccess');
        return redirect('/mapping_shift/dashboard/');
    }
    public function prosesEditMappingShift(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');


        // dd($request->all());

        if ($request["shift_update"] == '3ac53e9a-84d6-445e-9b48-fdb8a6b02cb2') {
            $request["status_absen"] = "Libur";
        }


        $validatedData = $request->validate([
            'shift_update' => 'required',
            'tanggal_update' => 'required',
        ]);

        MappingShift::where('id', $request['id_mapping'])->update([
            'shift_id' => Shift::where('id', $validatedData['shift_update'])->value('id'),
            'tanggal' => $validatedData['tanggal_update'],
            'status_absen' => $request['status_absen'],
        ]);
        $request->session()->flash('mappingshiftupdatesuccess');
        return redirect('/mapping_shift/dashboard/');
    }
    public function mapping_shift_datatable(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        // dd($request->all());
        // $now = Carbon::parse($request->filter_month)->startOfMonth();
        // dd($now);
        if (request()->ajax()) {
            if (!empty($request->departemen_filter)) {
                $date1 = Carbon::now()->startOfWeek();
                $date2 = Carbon::now()->endOfWeek();
                dd($date1->addDays(1), $date2);
                if (!empty($request->divisi_filter)) {
                    if (!empty($request->bagian_filter)) {
                        if (!empty($request->jabatan_filter)) {
                            $table = User::with('Mappingshift')
                                ->where('dept_id', $request->departemen_filter)
                                ->where('divisi_id', $request->divisi_filter)
                                ->where('bagian_id', $request->bagian_filter)
                                ->where('jabatan_id', $request->jabatan_filter)
                                ->where('kontrak_kerja', $holding)
                                ->where('kategori', 'Karyawan Bulanan')
                                ->where('status_aktif', 'AKTIF')
                                ->get();
                        } else {
                            $table = User::with('Mappingshift')
                                ->where('dept_id', $request->departemen_filter)
                                ->where('divisi_id', $request->divisi_filter)
                                ->where('bagian_id', $request->bagian_filter)
                                ->where('kontrak_kerja', $holding)
                                ->where('kategori', 'Karyawan Bulanan')
                                ->where('status_aktif', 'AKTIF')
                                ->get();
                        }
                    } else {
                        $table = User::with('Mappingshift')
                            ->where('dept_id', $request->departemen_filter)
                            ->where('divisi_id', $request->divisi_filter)
                            ->where('kontrak_kerja', $holding)
                            ->where('kategori', 'Karyawan Bulanan')
                            ->where('status_aktif', 'AKTIF')
                            ->get();
                    }
                } else {
                    $table = User::with('Mappingshift')
                        ->where('dept_id', $request->departemen_filter)
                        ->where('kontrak_kerja', $holding)
                        ->where('kategori', 'Karyawan Bulanan')
                        ->where('status_aktif', 'AKTIF')
                        ->get();
                    // dd($table);
                }
                return DataTables::of($table)
                    ->addColumn('jabatan', function ($row) {
                        $jabatan = $row->Jabatan->nama_jabatan;
                        return $jabatan;
                    })
                    ->addColumn('mapping_shift', function ($row) use ($date1, $date2) {
                        $mapping_senin = 'Senin:&nbsp;' . $row->Mappingshift->where('tanggal_masuk', $date1)->first();
                        $mapping_selasa = 'Senin:&nbsp;' . $row->Mappingshift->where('tanggal_masuk', $date1->addDays(1))->first();
                        $mapping_rabu = 'Senin:&nbsp;' . $row->Mappingshift->where('tanggal_masuk', $date1->addDays(2))->first();
                        $mapping_kamis = 'Senin:&nbsp;' . $row->Mappingshift->where('tanggal_masuk', $date1->addDays(3))->first();
                        $mapping_jumat = 'Senin:&nbsp;' . $row->Mappingshift->where('tanggal_masuk', $date1->addDays(4))->first();
                        $mapping_sabtu = 'Senin:&nbsp;' . $row->Mappingshift->where('tanggal_masuk', $date1->addDays(5))->first();
                        $mapping_minggu = 'Senin:&nbsp;' . $row->Mappingshift->where('tanggal_masuk', $date1->addDays(6))->first();
                        $mapping_shift = '<li>';
                        $mapping_shift = $mapping_shift . '';
                        $mapping_shift = '</li>';
                        // return $data;
                    })
                    ->rawColumns(['jabatan', 'mapping_shift'])
                    ->make(true);
            } else {
                $now = Carbon::parse($request->filter_month)->startOfMonth();
                $now1 = Carbon::parse($request->filter_month)->endOfMonth();
                // dd($now1);
                // dd($tgl_mulai, $tgl_selesai);
                $table = User::with('Mappingshift')
                    ->with('Jabatan')
                    ->where('kontrak_kerja', $holding)
                    ->where('kategori', 'Karyawan Bulanan')
                    ->where('status_aktif', 'AKTIF')
                    // ->limit(210)
                    ->get();
                return DataTables::of($table)
                    ->addColumn('jabatan', function ($row) {
                        $jabatan = $row->Jabatan->nama_jabatan;
                        return $jabatan;
                    })
                    ->addColumn('mapping_shift', function ($row) {
                        $mapping_shift = $row->Mappingshift;
                        return $mapping_shift;
                    })
                    ->rawColumns(['jabatan', 'mapping_shift'])
                    ->make(true);
            }
        }
    }
}
