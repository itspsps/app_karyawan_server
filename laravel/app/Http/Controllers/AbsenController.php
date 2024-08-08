<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AbsenController extends Controller
{
    public function index()
    {
        // dd('ok');
        $holding = request()->segment(count(request()->segments()));
        date_default_timezone_set('Asia/Jakarta');
        $user_login = auth()->user()->id;
        $tanggal = "";
        $tglskrg = date('Y-m-d');
        $tglkmrn = date('Y-m-d', strtotime('-1 days'));
        $mapping_shift = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglkmrn)->get();
        if ($mapping_shift->count() > 0) {
            foreach ($mapping_shift as $mp) {
                $jam_absen = $mp->jam_absen;
                $jam_pulang = $mp->jam_pulang;
            }
        } else {
            $jam_absen = "-";
            $jam_pulang = "-";
        }
        if ($jam_absen != null && $jam_pulang == null) {
            $tanggal = $tglkmrn;
        } else {
            $tanggal = $tglskrg;
        }
        return view('users.home.index', [
            'title' => 'Absen',
            'holding' => $holding,
            'shift_karyawan' => MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tanggal)->get()
        ]);
    }

    public function myLocation(Request $request)
    {
        return redirect('maps/' . $request["lat"] . '/' . $request['long']);
    }

    public function absenMasuk(Request $request, $id)
    {
        $lokasi_kerja = Auth::guard('web')->user()->penempatan_kerja;
        // dd($lokasi_kerja);
        if ($lokasi_kerja == 'null') {
        } else {
        }
        date_default_timezone_set('Asia/Jakarta');

        $lokasi_kantor = Lokasi::where('lokasi_kantor', Auth::guard('web')->user()->penempatan_kerja)->first();
        $lat_kantor = $lokasi_kantor->lat_kantor;
        $long_kantor = $lokasi_kantor->long_kantor;

        $request["jarak_masuk"] = $this->distance($request["lat_absen"], $request["long_absen"], $lat_kantor, $long_kantor, "K") * 1000;

        if ($request["jarak_masuk"] > $lokasi_kantor->radius) {
            Alert::error('Diluar Jangkauan', 'Lokasi Anda Diluar Radius Kantor');
            return redirect('/absen');
        } else {
            $foto_jam_absen = $request["foto_jam_absen"];

            $image_parts = explode(";base64,", $foto_jam_absen);

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'foto_jam_absen/' . uniqid() . '.png';

            Storage::put($fileName, $image_base64);


            $request["foto_jam_absen"] = $fileName;

            $request["status_absen"] = "Masuk";
            $request["keterangan_absensi"] = "ABSEN SESUAI LOKASI";

            $mapping_shift = MappingShift::where('id', $id)->get();

            foreach ($mapping_shift as $mp) {
                $shift = $mp->Shift->jam_masuk;
                $tanggal = $mp->tanggal;
            }

            $tgl_skrg = date("Y-m-d");

            $awal  = strtotime($tanggal . $shift);
            $akhir = strtotime($tgl_skrg . $request["jam_absen"]);
            $diff  = $akhir - $awal;
            dd($diff);
            if ($diff <= 0) {
                $request["telat"] = 0;
            } else {
                $request["telat"] = $diff;
            }

            $validatedData = $request->validate([
                'jam_absen' => 'required',
                'telat' => 'nullable',
                'foto_jam_absen' => 'required',
                'lat_absen' => 'required',
                'long_absen' => 'required',
                'jarak_masuk' => 'required',
                'status_absen' => 'required',
                'keterangan_absensi' => 'required'
            ]);

            MappingShift::where('id', $id)->update($validatedData);
            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'tambah',
                'description' => 'Absen Masuk Pada Tanggal ' . $tanggal
            ]);

            $request->session()->flash('success', 'Berhasil Absen Masuk');

            return redirect('/absen');
        }
    }

    public function absenPulang(Request $request, $id)
    {
        date_default_timezone_set('Asia/Jakarta');

        $lokasi_kantor = Lokasi::where('lokasi_kantor', Auth::guard('web')->user()->penempatan_kerja)->first();
        $lat_kantor = $lokasi_kantor->lat_kantor;
        $long_kantor = $lokasi_kantor->long_kantor;

        $request["jarak_pulang"] = $this->distance($request["lat_pulang"], $request["long_pulang"], $lat_kantor, $long_kantor, "K") * 1000;

        if ($request["jarak_pulang"] > $lokasi_kantor->radius) {
            Alert::error('Diluar Jangkauan', 'Lokasi Anda Diluar Radius Kantor');
            return redirect('/absen');
        } else {
            $foto_jam_pulang = $request["foto_jam_pulang"];

            $image_parts = explode(";base64,", $foto_jam_pulang);

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'foto_jam_pulang/' . uniqid() . '.png';

            Storage::put($fileName, $image_base64);

            $request["foto_jam_pulang"] = $fileName;
            $request["keterangan_absensi"] = "ABSEN SESUAI LOKASI";

            $mapping_shift = MappingShift::where('id', $id)->get();
            foreach ($mapping_shift as $mp) {
                $shiftmasuk = $mp->Shift->jam_masuk;
                $shiftpulang = $mp->Shift->jam_keluar;
                $tanggal = $mp->tanggal;
            }
            $new_tanggal = "";
            $timeMasuk = strtotime($shiftmasuk);
            $timePulang = strtotime($shiftpulang);


            if ($timePulang < $timeMasuk) {
                $new_tanggal = date('Y-m-d', strtotime('+1 days', strtotime($tanggal)));
            } else {
                $new_tanggal = $tanggal;
            }

            $tgl_skrg = date("Y-m-d");

            $akhir = strtotime($new_tanggal . $shiftpulang);
            $awal  = strtotime($tgl_skrg . $request["jam_pulang"]);
            $diff  = $akhir - $awal;

            if ($diff <= 0) {
                $request["pulang_cepat"] = 0;
            } else {
                $request["pulang_cepat"] = $diff;
            }

            $validatedData = $request->validate([
                'jam_pulang' => 'required',
                'foto_jam_pulang' => 'required',
                'lat_pulang' => 'required',
                'long_pulang' => 'required',
                'pulang_cepat' => 'required',
                'jarak_pulang' => 'required',
                'keterangan_absensi' => 'required'
            ]);

            MappingShift::where('id', $id)->update($validatedData);
            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'tambah',
                'description' => 'Absen Pulang Pada Tanggal ' . $tanggal
            ]);

            return redirect('/absen')->with('success', 'Berhasil Absen Pulang');
        }
    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public function dataAbsen(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        date_default_timezone_set('Asia/Jakarta');
        $tglskrg = date('Y-m-d');
        $data_absen = MappingShift::where('tanggal', $tglskrg);

        if ($request["mulai"] == null) {
            $request["mulai"] = $request["akhir"];
        }

        if ($request["akhir"] == null) {
            $request["akhir"] = $request["mulai"];
        }

        if ($request["user_id"] && $request["mulai"] && $request["akhir"]) {
            $data_absen = MappingShift::where('user_id', $request["user_id"])->whereBetween('tanggal', [$request["mulai"], $request["akhir"]]);
        }

        return view('absen.dataabsen', [
            'title' => 'Data Absen',
            'holding' => $holding,
            'user' => User::select('id', 'name')->get(),
            'data_absen' => $data_absen->get()
        ]);
    }

    public function maps($lat, $long)
    {
        date_default_timezone_set('Asia/Jakarta');
        return view('absen.maps', [
            'title' => 'Maps',
            'lat' => $lat,
            'long' => $long,
            'lokasi_kantor' => Lokasi::first()
        ]);
    }

    public function editMasuk($id)
    {
        return view('absen.editmasuk', [
            'title' => 'Edit Absen Masuk',
            'data_absen' => MappingShift::findOrFail($id),
            'lokasi_kantor' => Lokasi::first()
        ]);
    }

    public function prosesEditMasuk(Request $request, $id)
    {
        date_default_timezone_set('Asia/Jakarta');

        $mapping_shift = MappingShift::where('id', $id)->get();

        foreach ($mapping_shift as $mp) {
            $shift = $mp->Shift->jam_masuk;
            $tanggal = $mp->tanggal;
        }

        $awal  = strtotime($tanggal . $shift);
        $akhir = strtotime($tanggal . $request["jam_absen"]);
        $diff  = $akhir - $awal;

        if ($diff <= 0) {
            $request["telat"] = 0;
        } else {
            $request["telat"] = $diff;
        }

        $lokasi_kantor = Lokasi::first();
        $lat_kantor = $lokasi_kantor->lat_kantor;
        $long_kantor = $lokasi_kantor->long_kantor;

        $request["jarak_masuk"] = $this->distance($request["lat_absen"], $request["long_absen"], $lat_kantor, $long_kantor, "K") * 1000;

        $validatedData = $request->validate([
            'jam_absen' => 'required',
            'telat' => 'nullable',
            'foto_jam_absen' => 'image|max:5000',
            'lat_absen' => 'required',
            'long_absen' => 'required',
            'jarak_masuk' => 'required',
            'status_absen' => 'required'
        ]);

        if ($request->file('foto_jam_absen')) {
            if ($request->foto_jam_absen_lama) {
                Storage::delete($request->foto_jam_absen_lama);
            }
            $validatedData['foto_jam_absen'] = $request->file('foto_jam_absen')->store('foto_jam_absen');
        }

        MappingShift::where('id', $id)->update($validatedData);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'tambah',
            'description' => 'Edit Absen Masuk Pada Tanggal ' . $tanggal
        ]);
        return redirect('/data-absen')->with('success', 'Berhasil Edit Absen Masuk (Manual)');
    }

    public function editPulang($id)
    {
        return view('absen.editpulang', [
            'title' => 'Edit Absen Pulang',
            'data_absen' => MappingShift::findOrFail($id),
            'lokasi_kantor' => Lokasi::first()
        ]);
    }

    public function prosesEditPulang(Request $request, $id)
    {
        $mapping_shift = MappingShift::where('id', $id)->get();
        foreach ($mapping_shift as $mp) {
            $shiftmasuk = $mp->Shift->jam_masuk;
            $shiftpulang = $mp->Shift->jam_keluar;
            $tanggal = $mp->tanggal;
        }
        $new_tanggal = "";
        $timeMasuk = strtotime($shiftmasuk);
        $timePulang = strtotime($shiftpulang);


        if ($timePulang < $timeMasuk) {
            $new_tanggal = date('Y-m-d', strtotime('+1 days', strtotime($tanggal)));
        } else {
            $new_tanggal = $tanggal;
        }

        $akhir = strtotime($new_tanggal . $shiftpulang);
        $awal  = strtotime($new_tanggal . $request["jam_pulang"]);
        $diff  = $akhir - $awal;

        if ($diff <= 0) {
            $request["pulang_cepat"] = 0;
        } else {
            $request["pulang_cepat"] = $diff;
        }

        $lokasi_kantor = Lokasi::first();
        $lat_kantor = $lokasi_kantor->lat_kantor;
        $long_kantor = $lokasi_kantor->long_kantor;

        $request["jarak_pulang"] = $this->distance($request["lat_pulang"], $request["long_pulang"], $lat_kantor, $long_kantor, "K") * 1000;

        $validatedData = $request->validate([
            'jam_pulang' => 'required',
            'foto_jam_pulang' => 'image|max:5000',
            'lat_pulang' => 'required',
            'long_pulang' => 'required',
            'pulang_cepat' => 'required',
            'jarak_pulang' => 'required'
        ]);

        if ($request->file('foto_jam_pulang')) {
            if ($request->foto_jam_pulang_lama) {
                Storage::delete($request->foto_jam_pulang_lama);
            }
            $validatedData['foto_jam_pulang'] = $request->file('foto_jam_pulang')->store('foto_jam_pulang');
        }

        MappingShift::where('id', $id)->update($validatedData);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'tambah',
            'description' => 'Edit Absen Pulang Pada Tanggal ' . $tanggal
        ]);

        return redirect('/data-absen')->with('success', 'Berhasil Edit Absen Pulang (Manual)');
    }

    public function deleteAdmin($id)
    {
        $delete = MappingShift::find($id);
        Storage::delete($delete->foto_jam_absen);
        Storage::delete($delete->foto_jam_pulang);
        $delete->delete();
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'hapus',
            'description' => 'Hapus Absen'
        ]);
        return redirect('/data-absen')->with('success', 'Data Berhasil di Delete');
    }

    public function myAbsen(Request $request)
    {
        $user_login = auth()->user()->id;
        $date_now = date('Y');
        $month_now = date('m');
        $month_yesterday = \Carbon\Carbon::now()->subMonthsNoOverflow()->isoFormat('MM');
        $month_yesterday1 = \Carbon\Carbon::now()->subMonthsNoOverflow()->isoFormat('MMMM');
        $month_now1 = \Carbon\Carbon::now()->isoFormat('MMMM');
        date_default_timezone_set('Asia/Jakarta');
        $user_login = auth()->user()->id;
        $tanggal = "";
        $tglskrg = date('Y-m-d');
        $tglkmrn = date('Y-m-d', strtotime('-1 days'));
        $mapping_shift = MappingShift::where('user_id', $user_login)->where('tanggal', $tglkmrn)->get();
        $tidak_masuk = MappingShift::where('status_absen', 'Tidak Masuk')
            ->where('user_id', $user_login)
            ->select(DB::raw("COUNT(*) as count"))
            ->whereYear('tanggal', date('Y'))
            ->groupBy(DB::raw("Month(tanggal)"))
            ->pluck('count');
        $masuk = MappingShift::where('mapping_shifts.status_absen', 'Masuk')
            ->where('user_id', $user_login)
            ->select(DB::raw("COUNT(mapping_shifts.tanggal) as count"))
            ->whereYear('tanggal', date('Y'))
            ->groupBy(DB::raw("Month(tanggal)"))
            ->pluck('count');
        $telat = MappingShift::where('status_absen', 'Telat')
            ->where('user_id', $user_login)
            ->select(DB::raw("COUNT(*) as count"))
            ->whereYear('tanggal', date('Y'))
            ->groupBy(DB::raw("Month(tanggal)"))
            ->pluck('count');
        // dd();
        $telat_now = MappingShift::whereMonth('tanggal', $month_now)
            ->where('user_id', $user_login)
            ->select(DB::raw("telat as count"))
            ->pluck('count');
        $telat_yesterday = MappingShift::whereMonth('tanggal', $month_yesterday)
            ->where('user_id', $user_login)
            ->select(DB::raw("telat as count"))
            ->pluck('count');
        $lembur_now = MappingShift::whereMonth('tanggal', $month_now)
            ->where('user_id', $user_login)
            ->select(DB::raw("lembur as count"))
            ->pluck('count');
        $lembur_yesterday = MappingShift::whereMonth('tanggal', $month_yesterday)
            ->where('user_id', $user_login)
            ->select(DB::raw("lembur as count"))
            ->pluck('count');
        $data_telat_now = MappingShift::whereMonth('tanggal', $month_yesterday)
            ->where('user_id', $user_login)
            ->select(DB::raw("tanggal as count"))
            ->pluck('count');
        $data_telat_yesterday = MappingShift::whereMonth('tanggal', $month_yesterday)
            ->where('user_id', $user_login)
            ->select(DB::raw("tanggal as count "))
            ->pluck('count');
        if ($mapping_shift->count() > 0) {
            foreach ($mapping_shift as $mp) {
                $jam_absen = $mp->jam_absen;
                $jam_pulang = $mp->jam_pulang;
            }
        } else {
            $jam_absen = "-";
            $jam_pulang = "-";
        }
        if ($jam_absen != null && $jam_pulang == null) {
            $tanggal = $tglkmrn;
        } else {
            $tanggal = $tglskrg;
        }

        date_default_timezone_set('Asia/Jakarta');
        $tglskrg = date('Y-m-d');
        $data_absen = MappingShift::where('tanggal', $tglskrg)->where('user_id', auth()->user()->id);

        if ($request["mulai"] == null) {
            $request["mulai"] = $request["akhir"];
        }

        if ($request["akhir"] == null) {
            $request["akhir"] = $request["mulai"];
        }

        if ($request["mulai"] && $request["akhir"]) {
            $data_absen = MappingShift::where('user_id', auth()->user()->id)->whereBetween('tanggal', [$request["mulai"], $request["akhir"]]);
        }

        return view('absen.myabsen', [
            'title' => 'My Absen',
            'shift_karyawan' => MappingShift::where('user_id', $user_login)->where('tanggal', $tanggal)->get(),
            'data_absen' => $data_absen->get(),
            'masuk' => array_map('intval', json_decode($masuk)),
            'tidak_masuk' => array_map('intval', json_decode($tidak_masuk)),
            'telat' => array_map('intval', json_decode($telat)),
            'date_now' => $date_now,
            'month_now1' => $month_now1,
            'month_yesterday1' => $month_yesterday1,
            'telat_now' => array_map('intval', json_decode($telat_now)),
            'telat_yesterday' => array_map('intval', json_decode($telat_yesterday)),
            'lembur_now' => array_map('intval', json_decode($lembur_now)),
            'data_telat_now' => $data_telat_now,
            'data_telat_yesterday' => $data_telat_yesterday,
            'lembur_yesterday' => array_map('intval', json_decode($lembur_yesterday))
        ]);
    }
}
