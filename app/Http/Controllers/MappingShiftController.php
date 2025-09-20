<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Bagian;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Holding;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\MappingShift;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class MappingShiftController extends Controller
{
    function mapping_shift_index(Request $request, $holding)
    {

        $holding = Holding::where('holding_code', $holding)->first();
        date_default_timezone_set('Asia/Jakarta');
        // dd($holding);
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
        $departemen = Departemen::where('holding', $holding->id)->orderBy('nama_departemen')->get();
        $shift = Shift::orderBy('nama_shift')->get();
        // dd($user);

        return view('admin.karyawan.karyawan_mappingshift', [
            'tanggal_mulai' => $tanggal_mulai,
            'departemen' => $departemen,
            'holding' => $holding,
            'tanggal_akhir' => $tanggal_akhir,
            'user' => $user,
            'shift' => $shift
        ]);
    }
    function get_karyawan_selected(Request $request)
    {
        // dd('ok');
        $get_value1 = str_replace(['[', ']'], '', json_encode($request->value));
        $get_value2 = str_replace(['"'], "'", $get_value1);
        // dd(json_decode($get_value1));
        $data = Karyawan::whereIn('id', $request->value)->select('id', 'nomor_identitas_karyawan', 'name')->get();
        // dd($get_value2, $data);
        return response()->json($data);
    }
    function index()
    {
        $shift = Shift::whereNotIn('nama_shift', ['Office'])->get();
        $user = DB::table('users')->join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
            ->join('departemens', 'departemens.id', '=', 'users.dept_id')
            ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
            ->where('users.id', Auth()->user()->id)->first();


        $user_shift = Karyawan::with(['MappingShift' => function ($query) {
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
            'id_karyawan' => 'required',
            'max:255',
            'shift_id' => 'required',
            'max:255',
            'tanggal_mulai' => 'required',
            'max:16',
            'tanggal_akhir' => 'required',
            'max:16',
            'libur' => 'required'
        ];
        $customMessages = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute tidak boleh sama',
            'email' => ':attribute format salah',
            'min' => ':attribute Kurang',
            'max' => ':attribute Melebihi Batas Maksimal'
        ];
        $validasi = Validator::make($request->all(), $rules, $customMessages);

        if ($validasi->fails()) {
            $errors = $validasi->errors()->first();
            // dd($errors);
            Alert::error('Gagal', $errors);
            return back()->withInput();
        }
        // dd($request->all());
        // dd('p');
        // dd($request->all());
        $array_karyawan = explode(',', $request->id_karyawan);
        // dd($array_karyawan);
        $begin = new \DateTime($request["tanggal_mulai"]);
        $end = new \DateTime($request["tanggal_akhir"]);
        $end = $end->modify('+1 day');

        $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
        $daterange = new \DatePeriod($begin, $interval, $end);


        foreach ($array_karyawan as $karyawan) {
            $karyawan_id = $karyawan;
            $request["id_karyawan"] = $karyawan_id;

            $validatedData1 = $request->validate([
                'id_karyawan' => 'required',
            ]);
            foreach ($daterange as $date) {
                // dd($validatedData1['id_karyawan']);
                $tanggal_masuk = $date->format("Y-m-d");
                $tanggal_pulang = $date->format("Y-m-d");
                // pakai clone biar $date tidak berubah
                $malam = (clone $date)->modify('+1 day');
                $tanggal_pulang_malam = $malam->format("Y-m-d");

                $week = Carbon::parse($date)->dayOfWeek;
                if ($week == $request->libur) {
                    $request["status_absen"] = "LIBUR";
                } else {
                    $request["status_absen"] = 'TIDAK HADIR KERJA';
                }
                $cek_date_same = MappingShift::where('tanggal_masuk', $tanggal_masuk)->where('tanggal_pulang', $tanggal_pulang)->where('karyawan_id', $validatedData1['id_karyawan'])->where('shift_id', $request->shift_id)->count();
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

                $validatedData = $request->validate([
                    'shift_id' => 'required',
                    'tanggal_masuk' => 'required',
                    'tanggal_pulang' => 'required',
                ]);
                $insert = new MappingShift();
                $insert->karyawan_id = Karyawan::where('id', $validatedData1['id_karyawan'])->value('id');
                $insert->nik_karyawan = Karyawan::where('id', $validatedData1['id_karyawan'])->value('nomor_identitas_karyawan');
                $insert->nama_karyawan = Karyawan::where('id', $validatedData1['id_karyawan'])->value('name');
                $insert->shift_id = Shift::where('id', $validatedData['shift_id'])->value('id');
                $insert->nama_shift = Shift::where('id', $validatedData['shift_id'])->value('nama_shift');
                $insert->tanggal_masuk = $validatedData['tanggal_masuk'];
                $insert->tanggal_pulang = $validatedData['tanggal_pulang'];
                $insert->status_absen = $request['status_absen'];
                $insert->kelengkapan_absensi = 'BELUM ABSENSI';
                $insert->save();
            }
        }
        $request->session()->flash('mappingshiftsuccess');
        return redirect()->back()->with('success', 'data berhasil ditambahkan');
    }
    public function prosesTambahDetailShift(Request $request)
    {
        // dd($request->all());
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
        $holding = Holding::where('holding_code', $request->holding)->first();
        // dd($holding);
        // dd($request->filter_month, $request->departemen_filter);
        $nowMonth = Carbon::parse($request->filter_month)->month;
        $table = Karyawan::query()
            ->with([
                'Jabatan',
                'MappingShift' => function ($q) use ($nowMonth) {
                    $q->whereMonth('tanggal_masuk', $nowMonth)
                        ->with('Shift')
                        ->orderBy('tanggal_masuk', 'ASC');
                }
            ])
            ->where('kontrak_kerja', $holding->id)
            ->where('kategori', 'Karyawan Bulanan')
            ->where('status_aktif', 'AKTIF');

        if ($request->filled('departemen_filter')) {
            $table->where('dept_id', $request->departemen_filter);
        }
        if ($request->filled('divisi_filter')) {
            $table->where('divisi_id', $request->divisi_filter);
        }
        if ($request->filled('bagian_filter')) {
            $table->where('bagian_id', $request->bagian_filter);
        }
        if ($request->filled('jabatan_filter')) {
            $table->where('jabatan_id', $request->jabatan_filter);
        }
        $table->orderBy('name', 'ASC')->get();
        // dd($table->get());
        return DataTables::eloquent($table)
            ->addColumn('jabatan', function ($row) {
                return $row->Jabatan->nama_jabatan ?? '-';
            })
            ->addColumn('mapping_shift', function ($row) use ($holding) {
                if ($row->MappingShift->isEmpty()) {
                    return '<span class="badge bg-label-danger">Kosong</span>';
                }

                $first = $row->MappingShift->first();
                $last  = $row->MappingShift->last();

                $periode = '<span class="badge bg-label-success">'
                    . Carbon::parse($first->tanggal_masuk)->isoFormat('DD MMMM YYYY')
                    . ' - '
                    . Carbon::parse($last->tanggal_masuk)->isoFormat('DD MMMM YYYY')
                    . '</span>';

                $items = [];
                $slice = $row->MappingShift->take(6);
                foreach ($slice as $shift) {
                    $items[] = '<li>'
                        . Carbon::parse($shift->tanggal_masuk)->isoFormat('DD-MM-YYYY')
                        . ' (Jam Kerja : ' . $shift->Shift->jam_kerja . ' - ' . $shift->Shift->jam_keluar . ')</li>';
                }

                if ($row->MappingShift->count() > 6) {
                    $lastShift = $row->MappingShift->last();
                    $items[] = '<li>....</li><li>....</li>';
                    $items[] = '<li>'
                        . Carbon::parse($lastShift->tanggal_masuk)->isoFormat('DD-MM-YYYY')
                        . ' (Jam Kerja : ' . $lastShift->Shift->jam_kerja . ' - ' . $lastShift->Shift->jam_keluar . ')</li>';
                    $items[] = '<li><a href="/karyawan/mapping_shift/' . $row->id . '/' . $holding->holding_code . '"><span class="badge bg-label-info">Lihat Detail..</span></a></li>';
                }

                return $periode . implode('', $items);
            })
            ->addColumn('select', function ($row) {
                return '<div class="form-check">
            <input class="group_select form-check-input" type="checkbox"
                id="select_karyawan_' . $row->id . '"
                name="select_karyawan[]"  
                data-id="' . $row->id . '" 
                value="' . $row->id . '">
        </div>';
            })
            ->rawColumns(['jabatan', 'mapping_shift', 'select'])
            ->make(true);
    }
    public function mapping_shift_detail_datatable($id, $holding, Request $request)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        // dd($holding);
        // dd($request->start_date, $request->end_date, $holding);
        $nowMonth = Carbon::parse($request->filter_month)->month;
        $table = MappingShift::with([
            'Shift'
        ])
            ->where('karyawan_id', $request->id)
            ->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date])
            ->orderBy('tanggal_masuk', 'ASC')
            ->get();
        // dd($table);
        return DataTables::of($table)
            ->addColumn('nama_shift', function ($row) {
                return $row->Shift->nama_shift ?? '-';
            })
            ->addColumn('tanggal_masuk', function ($row) {
                return Carbon::parse($row->tanggal_masuk)->isoFormat('DD-MM-YYYY') ?? '-';
            })
            ->addColumn('jam_masuk', function ($row) {
                return Carbon::parse($row->Shift->jam_masuk)->isoFormat('H:mm') ?? '-';
            })
            ->addColumn('tanggal_keluar', function ($row) {
                return Carbon::parse($row->tanggal_keluar)->isoFormat('DD-MM-YYYY') ?? '-';
            })
            ->addColumn('jam_keluar', function ($row) {
                return Carbon::parse($row->Shift->jam_keluar)->isoFormat('H:mm') ?? '-';
            })
            ->addColumn('option', function ($row) {
                if ($row->tanggal_masuk < date('Y-m-d')) {
                    return '<button class="btn btn-sm btn-secondary" disabled>Edit</button>';
                } else {
                    return '<button class="btn btn-sm btn-primary" data-id="' . $row->id . '" id="btn_edit_mapping_shift">Edit</button>';
                }
            })
            ->rawColumns(['jabatan', 'mapping_shift', 'tanggal_masuk', 'tanggal_keluar', 'jam_masuk', 'jam_keluar', 'option'])
            ->make(true);
    }
    public function mapping_shift_detail_index($id, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $nowMonth = Carbon::now()->month;
        $user_check = Karyawan::where('id', $id)->first();
        if ($user_check->kategori == 'Karyawan Bulanan') {
            if ($user_check->dept_id == NULL || $user_check->divisi_id == NULL || $user_check->jabatan_id == NULL) {
                return redirect()->back()->with('error', 'Jabatan Karyawan Kosong');
            }
        }
        // dd($oke);
        $user = Karyawan::with([
            'Jabatan',
            'KontrakKerja',
            'Divisi',
            'MappingShift' => function ($q) use ($nowMonth) {
                $q->with('Shift')
                    ->whereMonth('tanggal_masuk', $nowMonth)
                    ->limit(100);
            }
        ])
            ->findOrFail($id);
        // dd($user);
        if ($user->kategori === 'Karyawan Bulanan') {
            if (empty($user->dept_id) || empty($user->divisi_id) || empty($user->jabatan_id)) {
                return back()->with('error', 'Jabatan Karyawan Kosong');
            }
        }
        $jabatan = Jabatan::whereIn('id', array_filter([
            $user->jabatan_id,
            $user->jabatan1_id,
            $user->jabatan2_id,
            $user->jabatan3_id,
            $user->jabatan4_id,
        ]))->get();
        $divisi = Divisi::whereIn('id', array_filter([
            $user->divisi_id,
            $user->divisi1_id,
            $user->divisi2_id,
            $user->divisi3_id,
            $user->divisi4_id,
        ]))->get();
        $no = 1;
        $no1 = 1;
        $oke = $user->MappingShift->last();
        // $shift = Carbon::parse($oke->tanggal_masuk)->addDay(1)->format('Y-m-d');

        // dd($shift);
        return view('admin.karyawan.mappingshift', [
            'title'             => 'Mapping Shift',
            'karyawan'          => $user,
            'holding'           => $holding,
            // 'shift_karyawan'    => $shift,
            'shift'             => Shift::all(),
            'jabatan_karyawan'  => $jabatan,
            'divisi_karyawan'   => $divisi,
            'no'                => $no,
            'no1'               => $no1,
        ]);
    }
}
