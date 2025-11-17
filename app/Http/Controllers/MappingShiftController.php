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
use App\Models\Menu;
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
        // dd('test');
        $roleId = Auth::user();
        $menus = Menu::whereIn('id', function ($query) use ($roleId) {
            $query->select('menu_id')
                ->from('role_menus')
                ->where('role_id', $roleId->role);
        })
            ->whereNull('parent_id') // menu utama
            ->with('children')
            ->where('kategori', 'web')      // load submenunya
            ->orderBy('sort_order')
            ->get();
        // dd($menus);
        $holding = Holding::where('holding_code', $holding)->first();
        // dd($holding);
        if ($holding == NULL) {
            return redirect()->route('dashboard_holding')->with('error', 'Holding Tidak Ditemukan');
        }
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
        $departemen = Departemen::where('holding', $holding->id)->orderBy('nama_departemen')->get();
        $shift = Shift::orderBy('nama_shift')->get();
        // dd($user);

        return view('admin.karyawan.karyawan_mappingshift', [
            'tanggal_mulai' => $tanggal_mulai,
            'departemen' => $departemen,
            'holding' => $holding,
            'menus' => $menus,
            'tanggal_akhir' => $tanggal_akhir,
            'user' => $user,
            'shift' => $shift
        ]);
    }
    function get_karyawan_mapping(Request $request, $holding)
    {
        // dd($request->all());
        $holding = Holding::where('holding_code', $holding)->first();
        if ($holding == null) {
            return response()->json([
                'code' => 500,
                'status' => 'Holding tidak ditemukan',
            ]);
        }
        try {
            $query = Karyawan::where('shift', 'SHIFT')
                ->where('status_aktif', 'AKTIF')
                ->where('kontrak_kerja', $holding->id);


            if (!empty($request->departemen_filter)) {
                $query->whereIn('dept_id', (array)$request->departemen_filter);
            }
            if (!empty($request->divisi_filter)) {
                $query->whereIn('divisi_id', (array)$request->divisi_filter);
            }
            if (!empty($request->bagian_filter)) {
                $query->whereIn('bagian_id', (array)$request->bagian_filter);
            }
            if (!empty($request->jabatan_filter)) {
                $query->whereIn('jabatan_id', (array)$request->jabatan_filter);
            }
            $data = $query->select('id', 'nomor_identitas_karyawan', 'name')->orderBy('name', 'ASC')->get();
            // dd($data);
            return response()->json([
                'code' => 200,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'status' => 'Gagal mengambil data karyawan',
                'message' => $e->getMessage()
            ]);
        }
    }
    function tambah_mapping()
    {
        $hrd = Karyawan::with(['Departemen' => function ($query) {
            $query->where('nama_departemen', 'HRD & GA');
            $query->with(['Divisi' => function ($q) {
                $q->with(['Jabatan' => function ($l) {
                    $l->with('LevelJabatan');
                }]);
            }]);
        }])->whereHas('Departemen', function ($query) {
            $query->where('nama_departemen', 'HRD & GA');
        })->whereHas('Departemen.Divisi.Jabatan.LevelJabatan', function ($query) {
            $query->whereIn('level_jabatan', ['1', '2']);
        })
            ->get();
        dd($hrd);
        $shift = Shift::whereNotIn('nama_shift', ['NON SHIFT'])->orderBy('nama_shift')->get();
        $user_karyawan = User::with([
            'Karyawan' => function ($query) {
                $query->with('Departemen');
                $query->with('Divisi');
                $query->with('Jabatan');
                $query->with('KontrakKerja');
                $query->with('PenempatanKerja');
            }
        ])->where('id', Auth()->user()->id)
            ->first();
        if ($user_karyawan->Karyawan->Departemen == NULL) {
            $departemen = NULL;
        } else {
            $departemen = $user_karyawan->Karyawan->Departemen->nama_departemen;
        }
        $roleId = Auth::user()->role;
        // dd($roleId);
        if ($roleId == null) {
            $all_menu = collect();
        } else {
            $all_menu  = Menu::whereIn('id', function ($query) use ($roleId) {
                $query->select('menu_id')
                    ->from('role_menus')
                    ->where('role_id', $roleId);
            })
                ->whereNull('parent_id') // menu utama
                ->with('children')       // load submenunya
                ->orderBy('sort_order')
                ->get();
        }
        // dd($departemen);
        $user_shift = Karyawan::with('Departemen', 'Divisi', 'Jabatan', 'KontrakKerja', 'PenempatanKerja')->with(['MappingShift' => function ($query) {
            $query;
        }])->where('dept_id', $user_karyawan->Karyawan->dept_id)
            ->where('divisi_id', $user_karyawan->Karyawan->divisi_id)
            ->where('penempatan_kerja', $user_karyawan->Karyawan->penempatan_kerja)
            ->where('shift', 'SHIFT')
            ->where('status_aktif', 'AKTIF')
            ->whereNotIn('id', [$user_karyawan->Karyawan->id])
            ->get();
        // dd($user_shift);
        return view('users.mapping_shift.tambah_mapping', [
            'user_karyawan' => $user_karyawan,
            'all_menus' => $all_menu,
            'shift' => $shift,
            'departemen' => $departemen,
            'user_shift' => $user_shift
        ]);
    }
    public function index(Request $request)
    {
        $shift = Shift::whereNotIn('nama_shift', ['NON SHIFT'])->orderBy('nama_shift')->get();
        $user_karyawan = User::with([
            'Karyawan' => function ($query) {
                $query->with('Departemen');
                $query->with('Divisi');
                $query->with('Jabatan');
                $query->with('KontrakKerja');
                $query->with('PenempatanKerja');
            }
        ])->where('id', Auth()->user()->id)
            ->first();
        if ($user_karyawan->Karyawan->Departemen == NULL) {
            $departemen = NULL;
        } else {
            $departemen = $user_karyawan->Karyawan->Departemen->nama_departemen;
        }
        $weekStart = Carbon::now()->startOfWeek();
        $days = collect(range(0, 6))->map(fn($i) => $weekStart->copy()->addDays($i));

        // Contoh data shift
        $shifts = [
            '2025-11-10' => [
                ['name' => 'Budi', 'shift' => 'Pagi (08:00 - 16:00)'],
                ['name' => 'Siti', 'shift' => 'Malam (20:00 - 04:00)'],
            ],
            '2025-11-11' => [
                ['name' => 'Andi', 'shift' => 'Siang (12:00 - 20:00)'],
            ],
        ];
        $roleId = Auth::user()->role;
        // dd($roleId);
        if ($roleId == null) {
            $all_menus = collect();
        } else {
            $all_menus  = Menu::whereIn('id', function ($query) use ($roleId) {
                $query->select('menu_id')
                    ->from('role_menus')
                    ->where('role_id', $roleId);
            })
                ->whereNull('parent_id') // menu utama
                ->with('children')       // load submenunya
                ->orderBy('sort_order')
                ->get();
        }
        return view('users.mapping_shift.index', compact(['user_karyawan', 'shift', 'departemen', 'shifts', 'days', 'all_menus']));
    }
    public function getShiftData(Request $request)
    {
        $start = Carbon::parse($request->query('start'))->format('Y-m-d'); // contoh: 2025-11-10$request->query('start'); // contoh: 2025-11-10
        $end = Carbon::parse($request->query('end'))->format('Y-m-d'); // contoh: 2025-11-16$request->query('end');     // contoh: 2025-11-16

        $data = MappingShift::with('User', 'Shift')
            ->whereBetween('tanggal_masuk', array($start, $end))
            ->orderBy('tanggal_masuk', 'asc')
            ->get();

        // ubah ke format seperti di JS
        $grouped = [];
        foreach ($data as $item) {
            $grouped[$item->tanggal_masuk][] = [
                'id_mapping' => $item->id,
                'shift_id' => $item->shift_id,
                'name' => $item->nama_karyawan,
                'shift' => $item->nama_shift,
                'jam_shift' => '(' . $item->Shift->jam_masuk . ' - ' . $item->Shift->jam_keluar . ')', // ' . $item->Shift->jam_masuk . ' - ' . $item->Shift->jam_keluar
            ];
        }

        // dd($grouped, $start, $end, $data);
        return response()->json($grouped);
    }
    public function getKaryawanMappingShift(Request $request)
    {
        $karyawan = Karyawan::select('id', 'name')->with('MappingShift')->whereIn('id', $request->user)->get();
        // dd($karyawan);
        if ($karyawan) {
            return response()->json([
                'code' => 200,
                'data' => $karyawan
            ]);
        } else {
            return response()->json([
                'code' => 404,
                'message' => 'Karyawan tidak ditemukan'
            ]);
        }
    }
    public function addMappingShift(Request $request)
    {
        // dd($request->all());
        $validation = Validator::make($request->all(), [
            'users' => 'required',
            'shift' => 'required',
            'tanggal' => 'required',
            'approve_hrd' => 'required',

        ], [
            'users.required' => 'Karyawan wajib diisi',
            'shift.required' => 'Shift wajib diisi',
            'tanggal.required' => 'Tanggal wajib diisi',
            'approve_hrd.required' => 'Approve HRD wajib diisi',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'code' => 500,
                'title' => 'Gagal',
                'message' => $validation->errors()
            ]);
        }
        // Pisahkan jadi dua tanggal
        [$start, $end] = explode(' - ', $request->tanggal);
        $start = Carbon::createFromFormat('d/m/Y', trim($start))->format('Y-m-d');
        $end = Carbon::createFromFormat('d/m/Y', trim($end))->format('Y-m-d');
        // dd($start, $end);
        // Buat periode tanggal
        $period = CarbonPeriod::create($start, $end);
        // dd($start, $end, $period);
        $no_urut =  MappingShift::whereNotNull('legal_number_mapping')->count() + 1;
        try {
            $get_shift = Shift::where('id', $request->shift)->first();
            if ($get_shift == null) {
                return response()->json([
                    'code' => 500,
                    'message' => 'Shift tidak ditemukan'
                ]);
            }
            foreach ($request->users as $user) {
                $get_karyawan = Karyawan::where('id', $user)->first();
                foreach ($period as $date) {

                    if ($get_shift->tgl_pulang_besok == 1) {
                        $tanggal_pulang =  Carbon::parse($date)->addDay('1')->format('Y-m-d');
                    } else {
                        $tanggal_pulang = Carbon::parse($date)->format('Y-m-d');
                    }
                    $get_mapping = MappingShift::where('karyawan_id', $user)->where('tanggal_masuk', Carbon::parse($date)->format('Y-m-d'))->first();
                    if ($get_mapping != null) {
                        $get_mapping->update([
                            'shift_id' => $request->shift,
                            'nama_shift' => $get_shift->nama_shift,
                            'tanggal_masuk' => Carbon::parse($date)->format('Y-m-d'),
                            'tanggal_pulang' => $tanggal_pulang,
                        ]);
                    } else {

                        MappingShift::create([
                            'karyawan_id' => $user,
                            'nik_karyawan' => $get_karyawan->nik,
                            'nama_karyawan' => $get_karyawan->name,
                            'shift_id' => $request->shift,
                            'nama_shift' => $get_shift->nama_shift,
                            'tanggal_masuk' => Carbon::parse($date)->format('Y-m-d'),
                            'tanggal_pulang' => $tanggal_pulang,
                        ]);
                    }
                }
            }
            return response()->json([
                'code' => 200,
                'message' => 'Berhasil menambahkan mapping shift',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'status' => 'Gagal menambahkan mapping shift',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update_mapping_shift(Request $request)
    {
        // dd($request->all());
        $validation = Validator::make($request->all(), [

            'shift' => 'required',
        ], [
            'shift.required' => 'Shift wajib diisi',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'code' => 500,
                'title' => 'Gagal',
                'message' => $validation->errors()
            ]);
        }
        try {
            $get_shift = Shift::where('id', $request->shift)->first();
            MappingShift::where('id', $request->id_mapping)->update([
                'shift_id' => $request->shift,
                'nama_shift' => $get_shift->nama_shift,
            ]);
            return response()->json([
                'code' => 200,
                'message' => 'Berhasil memperbarui mapping shift',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function delete_mapping_shift(Request $request)
    {
        try {
            $mapping = MappingShift::where('id', $request->id_mapping)->first();
            $mapping->delete();
            return response()->json([
                'code' => 200,
                'message' => 'Berhasil menghapus mapping shift',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage()
            ]);
        }
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

        if (empty($request["karyawan_id"])) {
            return response()->json([
                'code' => 500,
                'status' => 'warning',
                'message' => 'Karyawan belum dipilih'
            ]);
        } else {
            if ($request["shift_id"] == null) {
                return response()->json([
                    'code' => 500,
                    'status' => 'warning',
                    'message' => 'Shift belum dipilih'
                ]);
            }
        }


        $rules = [
            'karyawan_id' => 'required|array|min:1|max:255',
            'karyawan_id.*' => 'required|max:255',
            'shift_id' => 'required|array|min:1',
            'shift_id.*' => 'required',
            'max:255',
            'tanggal' => 'required|array|min:1',
            'tanggal.*' => 'required',
            'max:16',
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
        try {
            $countInsert = 0;
            foreach ($request['karyawan_id'] as $karyawanId) {
                for ($i = 0; $i < count($request->tanggal); $i++) {
                    $tanggal = $request->tanggal[$i];
                    $shiftId = $request->shift_id[$i];
                    $shift = Shift::where('id', $shiftId)->first();
                    if ($shift) {
                        if ($shift->tgl_pulang_besok == 1) {
                            $tanggal_pulang =  Carbon::parse($tanggal)->addDay('1')->format('Y-m-d');
                        } else {
                            $tanggal_pulang = Carbon::parse($tanggal)->format('Y-m-d');
                        }
                        $namaShift = $shiftId;
                    } else {
                        $namaShift = NULL;
                    }
                    // Cek apakah sudah ada jadwal untuk tanggal tsb
                    $exists = MappingShift::where('tanggal_masuk', $tanggal)
                        ->where('karyawan_id', $karyawanId)
                        ->exists();
                    if (!$exists) {
                        $insert = MappingShift::insert([
                            'karyawan_id' => $karyawanId,
                            'tanggal_masuk' => $tanggal,
                            'tanggal_pulang' => $tanggal_pulang,
                            'shift_id' => $namaShift,
                            'nama_shift' => Shift::where('id', $namaShift)->value('nama_shift'),
                            'nik_karyawan' => Karyawan::where('id', $karyawanId)->value('nomor_identitas_karyawan'),
                            'nama_karyawan' => Karyawan::where('id', $karyawanId)->value('name'),
                        ]);
                        $countInsert++;
                    } else {
                        $update = MappingShift::where('tanggal_masuk', $tanggal)->where('karyawan_id', $karyawanId)->update([
                            'karyawan_id' => $karyawanId,
                            'tanggal_masuk' => $tanggal,
                            'tanggal_pulang' => $tanggal_pulang,
                            'shift_id' => $namaShift,
                            'nama_shift' => Shift::where('id', $namaShift)->value('nama_shift'),
                            'nik_karyawan' => Karyawan::where('id', $karyawanId)->value('nomor_identitas_karyawan'),
                            'nama_karyawan' => Karyawan::where('id', $karyawanId)->value('name'),
                        ]);
                        $countInsert++;
                    }
                }
            }
            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'data berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'status' => 'Gagal menyimpan jadwal',
                'message' => $e->getMessage()
            ]);
        }
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
        // dd($request->all());
        date_default_timezone_set('Asia/Jakarta');
        $shift = Shift::where('id', $request['shiftSaatIni'])->first();
        $now = Carbon::now();
        $validatedData = $request->validate([
            'idMappingShift' => 'required',
            'tanggalMasukShift' => 'required',
            'shiftSaatIni' => 'required',

        ]);
        if ($now->greaterThan(Carbon::parse($validatedData['tanggalMasukShift']))) {
            return response()->json([
                'code' => 500,
                'status' => 'warning',
                'message' => 'Tanggal masuk shift tidak boleh kurang dari tanggal hari ini'
            ]);
        }
        if ($shift == NULL) {
            return response()->json([
                'code' => 500,
                'status' => 'warning',
                'message' => 'Shift tidak ditemukan'
            ]);
        } else {
            if ($shift->tgl_pulang_besok == 1) {
                $tanggal_pulang =  Carbon::parse($validatedData['tanggalMasukShift'])->addDay('1')->format('Y-m-d');
            } else {
                $tanggal_pulang = Carbon::parse($validatedData['tanggalMasukShift'])->format('Y-m-d');
            }
        }
        try {
            MappingShift::where('id', $request['idMappingShift'])->update([
                'shift_id' => Shift::where('id', $validatedData['shiftSaatIni'])->value('id'),
                'tanggal_masuk' => $validatedData['tanggalMasukShift'],
                'nama_shift' => Shift::where('id', $validatedData['shiftSaatIni'])->value('nama_shift'),
                'tanggal_pulang' => $tanggal_pulang,
                'status_absen' => $request['status_absen'],
            ]);
            return response()->json([
                'code' => 200,
                'status' => 'Success',
                'message' => 'Data Berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'status' => 'Gagal menyimpan jadwal',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function get_columns(Request $request)
    {
        // dd('ok');
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        // dd($end_date);
        $period = CarbonPeriod::create($start_date, $end_date);
        foreach ($period as $date) {
            $data_columns_header[] = ['header' => Carbon::parse($date)->isoFormat('ddd/DD')];
            $data_columns[] = ['data' => 'tanggal_' . $date->format('dmY'), 'name' => 'tanggal_' . $date->format('dmY')];
        }
        $count_period = count($period);

        return array(
            'data_columns_header' => $data_columns_header,
            'count_period' => $count_period,
            'datacolumn' => $data_columns,
            'start_date' => Carbon::parse($request->start_date)->format('d-m-Y'),
            'end_date' => Carbon::parse($request->end_date)->format('d-m-Y')
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
            'Bagian',
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
        // $table->limit(1);
        $query = $table->get();
        // dd($query);
        $column = DataTables::of($query);
        foreach ($period as $date) {
            $colName = 'tanggal_' . $date->format('dmY');
            $column->addColumn('tanggal_' . $date->format('dmY'), function ($row) use ($date) {
                $shift = $row->MappingShift->where('tanggal_masuk', $date->format('Y-m-d'))->first();
                if ($shift == NULL) {
                    return '-';
                } else {
                    $backgroundColor = $shift->Shift->kode_warna; // Nilai Hex (misal: #007bff)

                    // Panggil fungsi untuk menentukan class warna teks yang kontras
                    $textColorClass = getContrastTextColor($backgroundColor);
                    $shiftName = $shift->Shift->nama_shift;

                    // Menggunakan div dengan style yang memastikan warna mengisi cell
                    $tanggal = Carbon::parse($date->format('Y-m-d'))->isoFormat('DD MMMM YYYY');
                    $jabatan = $row->Jabatan->nama_jabatan ?? '-';
                    $bagian =  $row->Bagian->nama_bagian ?? '-';
                    $departemen = $row->Departemen->nama_departemen ?? '-';
                    $shiftid = $shift->Shift->id ?? '-';
                    return "<a href='javascript:void(0);'id='detail_shift' data-idmapping='{$shift->id}' data-idkaryawan='{$row->id}' data-nama='{$row->name}' data-jabatan='{$jabatan}' data-bagian='{$bagian}' data-departemen='{$departemen}' data-nip='{$row->nomor_identitas_karyawan}' data-tanggal='{$tanggal}' data-shift='{$shiftid}' data-tanggal_masuk='{$date->format('Y-m-d')}'><div class='text-center' style='width: 100%; background-color: {$shift->Shift->kode_warna}; height: 100%; display: block; padding: 5px 5px; border-radius: 0; margin: -8px 0 -8px 0; line-height: 2.5;'><span class='{$textColorClass}'>{$shiftName}</span></div></a>";

                    // ATAU, jika framework CSS Anda sudah menyediakan class full-cell:
                    // return '<span class="full-cell-success">' . $shift->Shift->nama_shift . '</span>'; 
                }
            });
            $data_tanggal[] = $colName;
        }
        $column->addColumn('jabatan', function ($row) {
            return $row->Jabatan->nama_jabatan ?? '-';
        });
        $column->addColumn('departemen', function ($row) {
            return $row->Departemen->nama_departemen ?? '-';
        });
        $rawCols = array_merge([
            'jabatan',
            'departemen',
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
