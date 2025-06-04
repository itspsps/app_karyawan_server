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
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
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
        $departemen = Departemen::where('holding', $holding)->orderBy('nama_departemen')->get();
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
                $malam = $date->modify('+1 day');
                $tanggal_pulang_malam = $malam->format("Y-m-d");

                $week = Carbon::parse($date)->dayOfWeek;
                if ($week == 1) {
                    $request["status_absen"] = "LIBUR";
                } else {
                    $request["status_absen"] = 'TIDAK HADIR KERJA';
                }
                $cek_date_same = MappingShift::where('tanggal_masuk', $tanggal_masuk)->where('tanggal_pulang', $tanggal_pulang)->where('user_id', $validatedData1['id_karyawan'])->where('shift_id', $request->shift_id)->count();
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
                $insert->user_id = Karyawan::where('id', $validatedData1['id_karyawan'])->value('id');
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
        // dd($request->filter_month, $request->departemen_filter);
        if (!empty($request->departemen_filter)) {
            $date1 = Carbon::parse($request->filter_month)->startOfMonth()->format('m');
            $date2 = Carbon::parse($request->filter_month)->endOfMonth()->format('m');
            if (!empty($request->divisi_filter)) {
                if (!empty($request->bagian_filter)) {
                    if (!empty($request->jabatan_filter)) {
                        $table = Karyawan::with('Mappingshift')
                            ->where('dept_id', $request->departemen_filter)
                            ->where('divisi_id', $request->divisi_filter)
                            ->where('bagian_id', $request->bagian_filter)
                            ->where('jabatan_id', $request->jabatan_filter)
                            ->where('kontrak_kerja', $holding)
                            ->where('kategori', 'Karyawan Bulanan')
                            ->where('status_aktif', 'AKTIF')
                            ->orderBy('name', 'ASC')
                            ->get();
                    } else {
                        $table = Karyawan::with('Mappingshift')
                            ->where('dept_id', $request->departemen_filter)
                            ->where('divisi_id', $request->divisi_filter)
                            ->where('bagian_id', $request->bagian_filter)
                            ->where('kontrak_kerja', $holding)
                            ->where('kategori', 'Karyawan Bulanan')
                            ->where('status_aktif', 'AKTIF')
                            ->orderBy('name', 'ASC')
                            ->get();
                    }
                } else {
                    $table = Karyawan::with('Mappingshift')
                        ->where('dept_id', $request->departemen_filter)
                        ->where('divisi_id', $request->divisi_filter)
                        ->where('kontrak_kerja', $holding)
                        ->where('kategori', 'Karyawan Bulanan')
                        ->where('status_aktif', 'AKTIF')
                        ->orderBy('name', 'ASC')
                        ->get();
                }
            } else {
                $table = Karyawan::with('Mappingshift')
                    ->where('dept_id', $request->departemen_filter)
                    ->where('kontrak_kerja', $holding)
                    ->where('kategori', 'Karyawan Bulanan')
                    ->where('status_aktif', 'AKTIF')
                    ->orderBy('name', 'ASC')
                    ->get();
            }
            return DataTables::of($table)
                ->addColumn('jabatan', function ($row) {
                    if ($row->Jabatan == NULL) {
                        $jabatan = '-';
                    } else {
                        $jabatan = $row->Jabatan->nama_jabatan;
                    }
                    return $jabatan;
                })
                ->addColumn('mapping_shift', function ($row) use ($date1, $date2) {
                    if ($row->MappingShift == NULL) {
                        $result = '<span class="badge bg-label-danger">Kosong</span>';
                        return $result;
                    } else {

                        $first = MappingShift::where('user_id', $row->id)->whereMonth('tanggal_masuk', $date1)->orderBy('tanggal_masuk', 'ASC')->first();
                        $last = MappingShift::where('user_id', $row->id)->whereMonth('tanggal_masuk', $date1)->orderBy('tanggal_masuk', 'DESC')->first();
                        if ($first == NULL || $last == NULL) {
                            $result = '<span class="badge bg-label-danger">Kosong</span>';
                            return $result;
                        } else {

                            // dd($first);
                            // dd($mapping);
                            $get_month = MappingShift::With('Shift')->where('user_id', $row->id)->whereBetween('tanggal_masuk', [$first->tanggal_masuk, $last->tanggal_masuk])->orderBy('tanggal_masuk', 'ASC')->get();
                            $get = '<span class="badge bg-label-success">' . Carbon::parse($first->tanggal_masuk)->isoFormat('DD MMMM YYYY')  . ' - ' . Carbon::parse($last->tanggal_masuk)->isoFormat('DD MMMM YYYY') . '</span>';
                            // dd($get_month->toArray());
                            if (count($get_month) >= 7) {
                                $get_month = array_slice($get_month->toArray(), 0, 6);
                                foreach ($get_month as $a) {
                                    // dd($a);
                                    $mapping_shift[] = '<li>' . Carbon::parse($a['tanggal_masuk'])->isoFormat('DD-MM-YYYY') . ' (Jam Kerja : ' . $a['shift']['jam_kerja'] . ' - ' . $a['shift']['jam_keluar'] . ')</li>';
                                }
                                $mapping_shift5 =  '<li>' . Carbon::parse($last->tanggal_masuk)->isoFormat('DD-MM-YYYY') . ' (Jam Kerja : ' . $a['shift']['jam_kerja'] . ' - ' . $a['shift']['jam_keluar'] . ')</li>';
                                $mapping_shift1 = str_replace(['[', ']'], '', json_encode($mapping_shift));
                                $mapping_shift2 = str_replace(['"', ','], "", $mapping_shift1);
                                $mapping_shift3 = str_replace(['\/'], "/", $mapping_shift2);
                                $mapping_shift4 = $mapping_shift3 . '<li>....</li><li>....</li>' . $mapping_shift5 . '<li><a href="/karyawan/mapping_shift/' . $row->id . '/sp"><span class="badge bg-label-info">Lihat Detail..</span></a></li>';
                            } else {
                                foreach ($get_month as $a) {
                                    $mapping_shift[] = '<li>' . $a['tanggal_masuk'] . '</li>';
                                }
                                $mapping_shift1 = str_replace(['[', ']'], '', json_encode($mapping_shift));
                                $mapping_shift2 = str_replace(['"', ','], "", $mapping_shift1);
                                $mapping_shift3 = str_replace(['\/'], "/", $mapping_shift2);
                                $mapping_shift4 = $mapping_shift3;
                            }
                            // dd($mapping_shift3);
                            $result = $get . $mapping_shift4;
                            return $result;
                        }
                    }
                })
                ->addColumn('select', function ($row) use ($date1, $date2) {
                    $select = '<div class="form-check">
                          <input class="group_select form-check-input" type="checkbox"  id="select_karyawan_' . $row->id . '" name="select_karyawan[]"  data-id="' . $row->id . '" value="' . $row->id . '">
                        </div>';
                    return $select;
                })
                ->rawColumns(['jabatan', 'mapping_shift', 'select'])
                ->make(true);
        } else {
            $now1 = Carbon::parse($request->filter_month)->startOfMonth()->format('m');
            $now2 = Carbon::parse($request->filter_month)->endOfMonth()->format('m');
            // dd($now1);
            // dd($tgl_mulai, $tgl_selesai);
            $table = Karyawan::with('Mappingshift')
                ->with('Jabatan')
                ->where('kontrak_kerja', $holding)
                ->where('kategori', 'Karyawan Bulanan')
                ->where('status_aktif', 'AKTIF')
                ->select('name', 'nomor_identitas_karyawan', 'jabatan_id')
                // ->limit(210)
                ->get();
            // dd($table);
            return DataTables::of($table)
                ->addColumn('jabatan', function ($row) use ($now1, $now2) {
                    if ($row->Jabatan == NULL) {
                        $jabatan = '-';
                    } else {
                        $jabatan = $row->Jabatan->nama_jabatan;
                    }
                    return $jabatan;
                })
                ->addColumn('mapping_shift', function ($row) use ($now1, $now2) {
                    if ($row->MappingShift == NULL) {
                        $result = '<span class="badge bg-label-danger">Kosong</span>';
                        return $result;
                    } else {
                        $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                        $first = MappingShift::where('user_id', $id_karyawan)->whereMonth('tanggal_masuk', $now1)->orderBy('tanggal_masuk', 'ASC')->first();
                        $last = MappingShift::where('user_id', $id_karyawan)->whereMonth('tanggal_masuk', $now1)->orderBy('tanggal_masuk', 'DESC')->first();
                        if ($first == NULL || $last == NULL) {
                            $result = '<span class="badge bg-label-danger">Kosong</span>';
                            return $result;
                        } else {

                            // dd($first);
                            // dd($mapping);
                            $get_month = MappingShift::With('Shift')->where('user_id', $id_karyawan)->whereBetween('tanggal_masuk', [$first->tanggal_masuk, $last->tanggal_masuk])->orderBy('tanggal_masuk', 'ASC')->get();
                            $get = '<span class="badge bg-label-success">' . Carbon::parse($first->tanggal_masuk)->isoFormat('DD MMMM YYYY')  . ' - ' . Carbon::parse($last->tanggal_masuk)->isoFormat('DD MMMM YYYY') . '</span>';
                            // dd($get_month->toArray());
                            if (count($get_month) >= 7) {
                                $get_month = array_slice($get_month->toArray(), 0, 6);
                                foreach ($get_month as $a) {
                                    // dd($a);
                                    $mapping_shift[] = '<li>' . Carbon::parse($a['tanggal_masuk'])->isoFormat('DD-MM-YYYY') . ' (Jam Kerja : ' . $a['shift']['jam_kerja'] . ' - ' . $a['shift']['jam_keluar'] . ')</li>';
                                }
                                $mapping_shift5 =  '<li>' . Carbon::parse($last->tanggal_masuk)->isoFormat('DD-MM-YYYY') . ' (Jam Kerja : ' . $a['shift']['jam_kerja'] . ' - ' . $a['shift']['jam_keluar'] . ')</li>';
                                $mapping_shift1 = str_replace(['[', ']'], '', json_encode($mapping_shift));
                                $mapping_shift2 = str_replace(['"', ','], "", $mapping_shift1);
                                $mapping_shift3 = str_replace(['\/'], "/", $mapping_shift2);
                                $mapping_shift4 = $mapping_shift3 . '<li>....</li><li>....</li>' . $mapping_shift5 . '<li><a href="/karyawan/mapping_shift/' . $id_karyawan . '/sp"><span class="badge bg-label-info">Lihat Detail..</span></a></li>';
                            } else {
                                foreach ($get_month as $a) {
                                    $mapping_shift[] = '<li>' . Carbon::parse($a['tanggal_masuk'])->isoFormat('DD-MM-YYYY') . ' (Jam Kerja : ' . $a['shift']['jam_kerja'] . ' - ' . $a['shift']['jam_keluar'] . ')</li>';
                                }
                                $mapping_shift1 = str_replace(['[', ']'], '', json_encode($mapping_shift));
                                $mapping_shift2 = str_replace(['"', ','], "", $mapping_shift1);
                                $mapping_shift3 = str_replace(['\/'], "/", $mapping_shift2);
                                $mapping_shift4 = $mapping_shift3;
                            }
                            // dd($mapping_shift3);
                            $result = $get . $mapping_shift4;
                            return $result;
                        }
                    }
                })
                ->addColumn('select', function ($row) use ($now1, $now2) {
                    $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                    $select = '<div class="form-check">
                          <input class="group_select form-check-input" type="checkbox"  id="select_karyawan_' . $id_karyawan . '" name="select_karyawan[]"  data-id="' . $id_karyawan . '" value="' . $id_karyawan . '">
                        </div>';
                    return $select;
                })
                ->rawColumns(['jabatan', 'mapping_shift', 'select'])
                ->make(true);
        }
    }
    public function mapping_shift_detail_index($id)
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
        return view('admin.karyawan.mappingshift', [
            'title' => 'Mapping Shift',
            'karyawan' => $user,
            'holding' => $holding,
            'shift_karyawan' => MappingShift::where('user_id', $id)->orderBy('created_at', 'desc')->limit(100)->get(),
            'shift' => Shift::all(),
            'jabatan_karyawan' => $jabatan,
            'divisi_karyawan' => $divisi,
            'no' => $no,
            'no1' => $no1,
        ]);
    }
}
