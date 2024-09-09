<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lembur;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LemburController extends Controller
{
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $user_login = auth()->user()->id;
        $tanggal = "";
        $tglskrg = date("Y-m-d");
        $tglkmrn = date('Y-m-d', strtotime('-1 days'));
        $lembur = Lembur::where('user_id', $user_login)->where('tanggal', $tglkmrn)->get();
        if($lembur->count() > 0) {
            foreach($lembur as $l) {
                $jam_keluar = $l->jam_keluar;
            }
        } else {
            $jam_keluar = "-";
        }
        if($jam_keluar == null){
            $tanggal = $tglkmrn;
        } else {
            $tanggal = $tglskrg;
        }

        return view('lembur.index', [
            'title' => 'Absen Lembur',
            'lembur' => Lembur::where('user_id', $user_login)->where('tanggal', $tanggal)->get()
        ]);
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

    public function masuk(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $lokasi_kantor = Lokasi::first();
        $lat_kantor = $lokasi_kantor->lat_kantor;
        $long_kantor = $lokasi_kantor->long_kantor;

        $request["jarak_masuk"] = $this->distance($request["lat_masuk"], $request["long_masuk"], $lat_kantor, $long_kantor, "K") * 1000;

        if($request["jarak_masuk"] > $lokasi_kantor->radius) {
            Alert::error('Diluar Jangkauan', 'Lokasi Anda Diluar Radius Kantor');
            return redirect('/lembur');
        } else {
            $foto_jam_masuk = $request["foto_jam_masuk"];

            $image_parts = explode(";base64,", $foto_jam_masuk);
    
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'foto_jam_masuk_lembur/' . uniqid() . '.png';
    
            Storage::put($fileName, $image_base64);
    
            $request["foto_jam_masuk"] = $fileName;

            $validatedData = $request->validate([
                'user_id' => 'required',
                'tanggal' => 'required',
                'jam_masuk' => 'required',
                'foto_jam_masuk' => 'required',
                'lat_masuk' => 'required',
                'long_masuk' => 'required',
                'jarak_masuk' => 'required'
            ]);
    
            Lembur::create($validatedData);

            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'create',
                'description' => 'Absen Lembur Masuk'
            ]);
    
            $request->session()->flash('success', 'Berhasil Masuk Lembur');
    
            return redirect('/lembur');
        }

    }

    public function pulang(Request $request, $id)
    {
        date_default_timezone_set('Asia/Jakarta');

        $lokasi_kantor = Lokasi::first();
        $lat_kantor = $lokasi_kantor->lat_kantor;
        $long_kantor = $lokasi_kantor->long_kantor;

        $request["jarak_keluar"] = $this->distance($request["lat_keluar"], $request["long_keluar"], $lat_kantor, $long_kantor, "K") * 1000;

        if($request["jarak_keluar"] > $lokasi_kantor->radius) {
            Alert::error('Diluar Jangkauan', 'Lokasi Anda Diluar Radius Kantor');
            return redirect('/lembur');
        } else {
            $foto_jam_keluar = $request["foto_jam_keluar"];

            $image_parts = explode(";base64,", $foto_jam_keluar);

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'foto_jam_keluar_lembur/' . uniqid() . '.png';

            Storage::put($fileName, $image_base64);

            $request["foto_jam_keluar"] = $fileName;

            $lembur = Lembur::where('id', $id)->get();
            foreach ($lembur as $l) {
                $jam_masuk = $l->jam_masuk;
            }
            $time_masuk = strtotime($jam_masuk);
            $time_keluar = strtotime($request["jam_keluar"]);

            $diff = $time_keluar - $time_masuk;

            $request["total_lembur"] = $diff;
            
            $validatedData = $request->validate([
                'jam_keluar' => 'required',
                'lat_keluar' => 'required',
                'long_keluar' => 'required',
                'jarak_keluar' => 'required',
                'foto_jam_keluar' => 'required',
                'total_lembur' => 'required'
            ]);
    
            Lembur::where('id', $id)->update($validatedData);

            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'create',
                'description' => 'Absen Lembur Pulang'
            ]);
    
            return redirect('/lembur')->with('success', 'Berhasil Pulang Lembur');
        }

    }

    public function dataLembur(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tglskrg = date('Y-m-d');
        $data_lembur = Lembur::where('tanggal', $tglskrg);

        if($request["mulai"] == null) {
            $request["mulai"] = $request["akhir"];
        }

        if($request["akhir"] == null) {
            $request["akhir"] = $request["mulai"];
        }

        if ($request["user_id"] && $request["mulai"] && $request["akhir"]) {
            $data_lembur = Lembur::where('user_id', $request["user_id"])->whereBetween('tanggal', [$request["mulai"], $request["akhir"]]);
        }

        return view('lembur.datalembur', [
            'title' => 'Data Lembur',
            'user' => User::select('id', 'name')->get(),
            'data_lembur' => $data_lembur->get()
        ]);
    }

    public function myLembur(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tglskrg = date('Y-m-d');
        $data_lembur = Lembur::where('tanggal', $tglskrg)->where('user_id', auth()->user()->id);
        if($request["mulai"] == null) {
            $request["mulai"] = $request["akhir"];
        }

        if($request["akhir"] == null) {
            $request["akhir"] = $request["mulai"];
        }

        if ($request["mulai"] && $request["akhir"]) {
            $data_lembur = Lembur::where('user_id', auth()->user()->id)->whereBetween('tanggal', [$request["mulai"], $request["akhir"]]);
        }

        return view('lembur.mylembur', [
            'title' => 'My Lembur',
            'data_lembur' => $data_lembur->get()
        ]);
    }
}
