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
use Carbon\CarbonPeriod;
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

        $divisi      = Divisi::with('Departemen')->whereIn('dept_id', (array)$id_departemen)
            ->where('holding', $request->holding)
            ->orderBy('nama_divisi', 'ASC')
            ->get()
            ->sortBy(function ($item) {
                return $item->Departemen->nama_departemen . ' ' . $item->nama_divisi;
            });
        if ($divisi == NULL || $divisi == '' || count($divisi) == '0') {
            $select = '<option value="">Pilih Divisi...</option>';
        } else {

            $select_divisi[] = "<option value=''>Pilih Divisi...</option>";
            $currentDept = null;
            foreach ($divisi as $divisi) {
                if ($currentDept !== $divisi->Departemen->nama_departemen) {
                    // tutup optgroup sebelumnya
                    if ($currentDept !== null) {
                        $select_divisi1[] = "</optgroup>";
                    }

                    // buka optgroup baru
                    $currentDept = $divisi->Departemen->nama_departemen;
                    $select_divisi1[] = "<optgroup label='{$divisi->Departemen->nama_departemen}'>";
                }
                $select_divisi1[] = "<option value='$divisi->id'>$divisi->nama_divisi</option>";
            }
            // tutup optgroup terakhir
            if ($currentDept !== null) {
                $select_divisi1[] = "</optgroup>";
            }

            $select = array_merge($select_divisi, $select_divisi1);
        }
        return array(
            'select' => $select,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        );
    }
    public function get_bagian(Request $request)
    {
        $id_divisi    = $request->divisi_filter;

        $bagian      = Bagian::with('Divisi')->where('divisi_id', (array)$id_divisi)
            ->where('holding', $request->holding)
            ->orderBy('nama_bagian', 'ASC')->get()
            ->sortBy(function ($item) {
                return $item->Divisi->nama_divisi . ' ' . $item->nama_bagian;
            });
        if ($bagian == NULL || $bagian == '' || count($bagian) == '0') {
            $select = '<option value="">Pilih Bagian...</option>';
        } else {
            $select_bagian[] = "<option value=''>Pilih Bagian...</option>";
            $currentDept = null;
            foreach ($bagian as $bagian) {
                if ($currentDept !== $bagian->Divisi->nama_divisi) {
                    // tutup optgroup sebelumnya
                    if ($currentDept !== null) {
                        $select_bagian1[] = "</optgroup>";
                    }

                    // buka optgroup baru
                    $currentDept = $bagian->Divisi->nama_divisi;
                    $select_bagian1[] = "<optgroup label='{$bagian->Divisi->nama_divisi}'>";
                }
                $select_bagian1[] = "<option value='$bagian->id'>$bagian->nama_bagian</option>";
            }
            // tutup optgroup terakhir
            if ($currentDept !== null) {
                $select_bagian1[] = "</optgroup>";
            }

            $select = array_merge($select_bagian, $select_bagian1);
        }
        return array(
            'select' => $select,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        );
    }
    public function get_jabatan(Request $request)
    {
        $id_bagian    = $request->bagian_filter;
        $jabatan      = Jabatan::with('Bagian')->where('bagian_id', (array)$id_bagian)
            ->where('holding', $request->holding)
            ->orderBy('nama_jabatan', 'ASC')->get()
            ->sortBy(function ($item) {
                return $item->Bagian->nama_bagian . ' ' . $item->nama_jabatan;
            });
        if ($jabatan == NULL || $jabatan == '' || count($jabatan) == '0') {
            $select = '<option value="">Pilih Jabatan...</option>';
        } else {
            $select_jabatan[] = "<option value=''>Pilih Jabatan...</option>";
            $currentDept = null;
            foreach ($jabatan as $jabatan) {
                if ($currentDept !== $jabatan->Bagian->nama_bagian) {
                    // tutup optgroup sebelumnya
                    if ($currentDept !== null) {
                        $select_jabatan1[] = "</optgroup>";
                    }

                    // buka optgroup baru
                    $currentDept = $jabatan->Bagian->nama_bagian;
                    $select_jabatan1[] = "<optgroup label='{$jabatan->Bagian->nama_bagian}'>";
                }
                $select_jabatan1[] = "<option value='$jabatan->id'>$jabatan->nama_jabatan</option>";
            }
            // tutup optgroup terakhir
            if ($currentDept !== null) {
                $select_jabatan1[] = "</optgroup>";
            }

            $select = array_merge($select_jabatan, $select_jabatan1);
        }
        return array(
            'select' => $select,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        );
    }

    public function prosesAddMappingShift(Request $request)
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
            'id_karyawan' => 'required',
            'max:255',
            'shift_id' => 'required',
            'max:255',
            'tanggal_mulai' => 'required',
            'max:16',
            'tanggal_akhir' => 'required',
            'max:16',
            'libur' => 'nullable'
        ];
        $customMessages = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute tidak boleh sama',
            'email' => ':attribute format salah',
            'min' => ':attribute Kurang',
            'max' => ':attribute Melebihi Batas Maksimal',

        ];
        $validasi = Validator::make($request->all(), $rules, $customMessages);

        if ($validasi->fails()) {
            $errors = $validasi->errors()->first();
            // dd($errors);
            Alert::error('Gagal', $errors);
            return response()->json([
                'code' => 500,
                'status' => 'warning',
                'message' => $errors
            ]);
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

                $cek_date_same = MappingShift::where('tanggal_masuk', $tanggal_masuk)->where('tanggal_pulang', $tanggal_pulang)->where('karyawan_id', $validatedData1['id_karyawan'])->where('shift_id', $request->shift_id)->count();
                if ($cek_date_same != 0) {
                    return response()->json([
                        'code' => 500,
                        'status' => 'warning',
                        'message' => 'Data ada yang sama'
                    ]);
                }
                $nama_shift = Shift::where('id', $request['shift_id'])->first();
                $jamMasuk  = Carbon::parse($nama_shift->jam_masuk);
                $jamKeluar = Carbon::parse($nama_shift->jam_keluar);
                if ($jamMasuk->lessThanOrEqualTo($jamKeluar)) {
                    $request["tanggal_pulang"] = $tanggal_pulang;
                } else {
                    $request["tanggal_pulang"] = $tanggal_pulang_malam;
                }

                $week = Carbon::parse($date)->dayOfWeek;
                if ($week == $request->libur) {
                    $request["status_absen"] = "LIBUR";
                    $request["nama_shift"] = "LIBUR";
                    $request["tanggal_masuk"] = $tanggal_masuk;
                    $request["tanggal_pulang"] = $tanggal_pulang;
                } else {
                    $request["status_absen"] = 'TIDAK HADIR KERJA';
                    $request["tanggal_masuk"] = $tanggal_masuk;
                    $request["nama_shift"] = $nama_shift->nama_shift;
                }
                // dd($request['tanggal_pulang'], $nama_shift->jam_masuk, $nama_shift->jam_keluar);
                $validatedData = $request->validate([
                    'shift_id' => 'required',
                    'tanggal_masuk' => 'required',
                    'nama_shift' => 'required',
                    'tanggal_pulang' => 'required',
                ]);
                $karyawan = Karyawan::where('id', $validatedData1['id_karyawan'])->first();
                $insert = new MappingShift();
                $insert->karyawan_id = $karyawan->id;
                $insert->nik_karyawan = $karyawan->nomor_identitas_karyawan;
                $insert->nama_karyawan = $karyawan->name;
                $insert->shift_id = $validatedData['shift_id'];
                $insert->nama_shift = $validatedData['nama_shift'];
                $insert->tanggal_masuk = $validatedData['tanggal_masuk'];
                $insert->tanggal_pulang = $validatedData['tanggal_pulang'];
                $insert->status_absen = $request['status_absen'];
                $insert->kelengkapan_absensi = 'BELUM ABSENSI';
                $insert->save();
            }
        }
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'data berhasil ditambahkan'
        ]);
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
    public function get_columns(Request $request)
    {
        // dd('ok');
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        // dd($end_date);
        $period = CarbonPeriod::create($start_date, $end_date);
        foreach ($period as $date) {
            $data_columns_header[] = ['header' => Carbon::parse($date)->isoFormat('dd, D/M/YYYY')];
            $data_columns[] = ['data' => 'tanggal_' . $date->format('dmY'), 'name' => 'tanggal_' . $date->format('dmY')];
        }
        $count_period = count($period);

        return array(
            'data_columns_header' => $data_columns_header,
            'count_period' => $count_period,
            'datacolumn' => $data_columns,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        );
    }
    public function mapping_shift_datatable(Request $request)
    {
        $holding = Holding::where('holding_code', $request->holding)->first();
        // dd($holding);
        // dd($request->all(), $request->departemen_filter);
        $start_date = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $now = Carbon::parse($start_date);
        $now1 = Carbon::parse($end_date);
        $period = CarbonPeriod::create($now, $now1);
        // dd($request->start_date, $request->end_date);
        $table = Karyawan::with([
            'Departemen',
            'Jabatan',
            'MappingShift' => function ($q) use ($now, $now1) {
                $q->whereBetween('tanggal_masuk', [$now, $now1])
                    ->with('Shift')
                    ->orderBy('tanggal_masuk', 'ASC');
            }
        ])
            ->where('kontrak_kerja', $holding->id)
            ->where('shift', 'SHIFT')
            ->where('kategori', 'Karyawan Bulanan')
            ->where('status_aktif', 'AKTIF');

        if (!empty($request->departemen_filter)) {
            $table->whereIn('dept_id', (array)$request->departemen_filter);
        }
        if (!empty($request->divisi_filter)) {
            $table->whereIn('divisi_id', (array)$request->divisi_filter);
        }
        if (!empty($request->bagian_filter)) {
            $table->whereIn('bagian_id', (array)$request->bagian_filter);
        }
        if (!empty($request->jabatan_filter)) {
            $table->whereIn('jabatan_id', (array)$request->jabatan_filter);
        }
        $table->orderBy('name', 'ASC');
        $query = $table->get();
        // dd($query);
        $column = DataTables::of($query);
        foreach ($period as $date) {
            $colName = 'tanggal_' . $date->format('dmY');
            $column->addColumn('tanggal_' . $date->format('dmY'), function ($row) use ($date) {
                return '-';
            });
            $data_tanggal[] = $colName;
        }
        $column->addColumn('jabatan', function ($row) {
            return $row->Jabatan->nama_jabatan ?? '-';
        });
        $column->addColumn('departemen', function ($row) {
            return $row->Departemen->nama_departemen ?? '-';
        });
        $column->addColumn('mapping_shift', function ($row) use ($holding) {

            if ($row->MappingShift->count() == 0) {
                return '<span class="badge bg-label-danger">Kosong</span>';
            } else {

                $first = $row->MappingShift->min('tanggal_masuk');
                $last  = $row->MappingShift->max('tanggal_masuk');

                $periode = '<span class="badge bg-label-success">'
                    . Carbon::parse($first)->isoFormat('DD MMMM YYYY')
                    . ' - '
                    . Carbon::parse($last)->isoFormat('DD MMMM YYYY')
                    . '</span>';

                $items = [];
                $slice = $row->MappingShift->take(6);
                foreach ($slice as $shift) {
                    $items[] = '<li>'
                        . Carbon::parse($shift->tanggal_masuk)->isoFormat('DD-MM-YYYY')
                        . ' (Jam Kerja : ' . $shift->Shift->jam_kerja . ' - ' . $shift->Shift->jam_keluar . ')</li>';
                }

                if ($row->MappingShift->count() > 1) {
                    $lastShift = $row->MappingShift->last();
                    $items[] = '<li>....</li><li>....</li>';
                    $items[] = '<li>'
                        . Carbon::parse($lastShift->tanggal_masuk)->isoFormat('DD-MM-YYYY')
                        . ' (Jam Kerja : ' . $lastShift->Shift->jam_kerja . ' - ' . $lastShift->Shift->jam_keluar . ')</li>';
                    $items[] = '<li><a href="/karyawan/mapping_shift/' . $row->id . '/' . $holding->holding_code . '"><span class="badge bg-label-info">Lihat Detail..</span></a></li>';
                }

                return $periode . implode('', $items);
            }
            // return $first . ' - ' . $last;
        })
            ->addColumn('select', function ($row) {
                return '<div class="form-check">
            <input class="group_select form-check-input" type="checkbox"
                id="select_karyawan_' . $row->id . '"
                name="select_karyawan[]"  
                data-id="' . $row->id . '" 
                value="' . $row->id . '">
        </div>';
            });
        $rawCols = array_merge([
            'jabatan',
            'departemen',
            'mapping_shift',
            'select'
        ], $data_tanggal);
        $column->rawColumns($rawCols);
        return $column->make(true);
    }

    public function mapping_calendar($holding, Request $request)
    {
        // dd($request->all());
        try {
            $holding = Holding::where('holding_code', $holding)->first();
            $events = [];

            $table = MappingShift::with([
                'Shift'
            ])->with(['User'])
                ->orderBy('tanggal_masuk', 'ASC')
                ->get();
            // dd($table);
            foreach ($table as $row) {
                $events[] = [
                    'title' => $row->User->name,
                    'start' => $row->tanggal_masuk,
                    'end' => $row->tanggal_keluar,
                    'color' => '#3788d8',
                ];
            }
            return response()->json($events);
        } catch (\Exception $e) {
            // log ke laravel.log biar tahu detail error-nya
            \Log::error('Error di getEvents: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getDetailTanggal($holding, Request $request)
    {
        $tanggal = $request->tanggal;
        // dd($tanggal);
        $records = MappingShift::with(['User', 'Shift'])
            ->whereDate('tanggal_masuk', $tanggal)
            ->get();

        if ($records->isEmpty()) {
            return response()->json(['html' => '<p>Tidak ada shift di tanggal ini.</p>']);
        }

        $html = '<table class="table table-sm table-striped mb-0">';
        $html .= '<thead><tr><th>Nama Karyawan</th><th>Shift</th><th</tr></thead><tbody>';
        foreach ($records as $r) {
            $html .= '<tr><td>' . $r->User->name . '</td><td>' . $r->Shift->nama_shift . '</td></tr>';
        }
        $html .= '</tbody></table>';

        return response()->json(['html' => $html]);
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
            ->orderBy('tanggal_masuk', 'DESC')
            ->get();
        // dd($table);
        return DataTables::of($table)
            ->addColumn('nama_shift', function ($row) {
                if ($row->status_absen == 'LIBUR') {
                    return '<span class="badge bg-label-danger">' . $row->status_absen . '</span>';
                } else {
                    return $row->Shift->nama_shift ?? '-';
                }
            })
            ->addColumn('tanggal_masuk', function ($row) {
                return Carbon::parse($row->tanggal_masuk)->isoFormat('DD-MM-YYYY') ?? '-';
            })
            ->addColumn('jam_masuk', function ($row) {
                if ($row->status_absen == 'LIBUR') {
                    return '<span class="badge bg-label-danger">' . $row->status_absen . '</span>';
                } else {
                    return Carbon::parse($row->Shift->jam_masuk)->isoFormat('H:mm') ?? '-';
                }
            })
            ->addColumn('tanggal_keluar', function ($row) {
                return Carbon::parse($row->tanggal_keluar)->isoFormat('DD-MM-YYYY') ?? '-';
            })
            ->addColumn('jam_keluar', function ($row) {
                if ($row->status_absen == 'LIBUR') {
                    return '<span class="badge bg-label-danger">' . $row->status_absen . '</span>';
                } else {
                    return Carbon::parse($row->Shift->jam_keluar)->isoFormat('H:mm') ?? '-';
                }
            })
            ->addColumn('option', function ($row) {
                if ($row->tanggal_masuk < date('Y-m-d')) {
                    return '<button class="btn btn-sm btn-secondary" disabled>Edit</button>';
                } else {
                    return '<button class="btn btn-sm btn-primary" data-id="' . $row->id . '" id="btn_edit_mapping_shift">Edit</button>';
                }
            })
            ->rawColumns(['jabatan', 'nama_shift', 'mapping_shift', 'tanggal_masuk', 'tanggal_keluar', 'jam_masuk', 'jam_keluar', 'option'])
            ->make(true);
    }
    public function mapping_shift_detail_index($id, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $nowMonth = Carbon::now()->month;
        $user_check = Karyawan::where('id', $id)->first();
        if ($user_check == NULL) {
            return redirect()->back()->with('error', 'Data Karyawan Tidak Ditemukan');
        } else {
            if ($user_check->kategori == 'Karyawan Bulanan') {
                if ($user_check->dept_id == NULL || $user_check->divisi_id == NULL || $user_check->jabatan_id == NULL) {
                    return redirect()->back()->with('error', 'Jabatan Karyawan Kosong');
                }
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
