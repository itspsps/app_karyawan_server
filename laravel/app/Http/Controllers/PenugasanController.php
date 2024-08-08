<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Departemen;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MappingShift;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;
use App\Models\KategoriCuti;
use App\Models\LevelJabatan;
use App\Models\Lokasi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Carbon\CarbonPeriod;
use DateTime;

class PenugasanController extends Controller
{
    public function index()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.penugasan.index', [
            'holding' => $holding,
        ]);
    }

    public function tambahPenugasan(Request $request)
    {
        // dd($request->all());
        $date_now = Carbon::now();
        if ($request->tanggal_kunjungan > $date_now || $request->tanggal_kunjungan == $date_now) {
            // dd('oke');
            if ($request->alamat_dikunjungi == NULL) {
                $alamat_dikunjungi = $request->alamat_dikunjungi1;
            } else {
                $alamat_dikunjungi = $request->alamat_dikunjungi;
            }
            Penugasan::create([
                'id_user'                       => User::where('id', Auth::user()->id)->value('id'),
                'nama_user'                     => User::where('id', Auth::user()->id)->value('name'),
                'id_user_atasan'                => User::where('id', $request->id_user_atasan)->value('id'),
                'id_user_atasan2'               => User::where('id', $request->id_user_atasan2)->value('id'),
                'id_jabatan'                    => Jabatan::where('id', $request->id_jabatan)->value('id'),
                'id_departemen'                 => Departemen::where('id', $request->id_departemen)->value('id'),
                'id_divisi'                     => Divisi::where('id', $request->id_divisi)->value('id'),
                'asal_kerja'                    => $request->asal_kerja,
                'id_diajukan_oleh'              => User::where('id', $request->id_diajukan_oleh)->value('id'),
                'nama_diajukan'                 => User::where('id', $request->id_diajukan_oleh)->value('name'),
                'ttd_id_diajukan_oleh'          => $request->ttd_id_diajukan_oleh,
                'waktu_ttd_id_diajukan_oleh'    => $request->waktu_ttd_id_diajukan_oleh,
                'id_diminta_oleh'               => User::where('id', $request->id_diminta_oleh)->value('id'),
                'nama_diminta'                  => User::where('id', $request->id_diminta_oleh)->value('name'),
                'ttd_id_diminta_oleh'           => $request->ttd_id_diminta_oleh,
                'waktu_ttd_id_diminta_oleh'     => $request->waktu_ttd_id_diminta_oleh,
                'id_disahkan_oleh'              => User::where('id', $request->id_disahkan_oleh)->value('id'),
                'nama_disahkan'                 => User::where('id', $request->id_disahkan_oleh)->value('name'),
                'ttd_id_disahkan_oleh'          => $request->ttd_id_disahkan_oleh,
                'waktu_ttd_id_disahkan_oleh'    => $request->waktu_ttd_id_disahkan_oleh,
                'id_user_hrd'                   => User::where('id', $request->proses_hrd)->value('id'),
                'nama_hrd'                      => User::where('id', $request->proses_hrd)->value('name'),
                'ttd_proses_hrd'                => $request->ttd_proses_hrd,
                'waktu_ttd_proses_hrd'          => $request->waktu_ttd_proses_hrd,
                'id_user_finance'               => User::where('id', $request->proses_finance)->value('id'),
                'nama_finance'                  => User::where('id', $request->proses_finance)->value('name'),
                'ttd_proses_finance'            => $request->ttd_proses_finance,
                'waktu_ttd_proses_finance'      => $request->waktu_ttd_proses_finance,
                'penugasan'                     => $request->penugasan,
                'wilayah_penugasan'             => $request->wilayah_penugasan,
                'tanggal_kunjungan'             => $request->tanggal_kunjungan,
                'selesai_kunjungan'             => $request->selesai_kunjungan,
                'kegiatan_penugasan'            => $request->kegiatan_penugasan,
                'pic_dikunjungi'                => $request->pic_dikunjungi,
                'alamat_dikunjungi'             => $alamat_dikunjungi,
                'transportasi'                  => $request->transportasi,
                'kelas'                         => $request->kelas,
                'budget_hotel'                  => $request->budget_hotel,
                'makan'                         => $request->makan,
                'status_penugasan'              => 0,
                'tanggal_pengajuan'             => $request->tanggal_pengajuan,

            ]);
            $request->session()->flash('penugasansukses', 'Berhasil Membuat Perdin');
            return redirect('/penugasan/dashboard');
        } else {
            $request->session()->flash('penugasangagal1');
            return redirect('/penugasan/dashboard');
        }
    }

    public function penugasanEdit($id)
    {
        $user           = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
            ->join('departemens', 'departemens.id', '=', 'users.dept_id')
            ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
            ->where('users.id', Auth()->user()->id)->first();
        $penugasan      = Penugasan::join('jabatans', 'jabatans.id', 'penugasans.id_jabatan')
            ->join('departemens', 'departemens.id', 'penugasans.id_departemen')
            ->join('divisis', 'divisis.id', 'penugasans.id_divisi')
            ->join('users', 'users.id', 'penugasans.id_diminta_oleh')
            ->where('penugasans.id', $id)->first();
        // $id_penugasan   = $id;
        $master_lokasi = Lokasi::whereNotIn('kategori_kantor', ['all sps', 'all sp', 'all sip', 'all'])->get();
        $diminta = User::where(['id' => $penugasan->id_diminta_oleh])->first();
        $disahkan = User::where(['id' => $penugasan->id_disahkan_oleh])->first();
        if ($user->kontrak_kerja == 'SP') {
            // Bu fitri
            $hrd = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                // ->where('jabatans.holding', 'sp')
                ->where('jabatans.nama_jabatan', 'MANAGER')
                ->where('bagians.nama_bagian', 'HRD & GA')
                ->where('divisis.nama_divisi', 'HRD & GA')
                ->where('departemens.nama_departemen', 'HRD & GA')
                ->select('users.*')
                ->get();
            $finance = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                ->where('jabatans.holding', 'sp')
                ->where('bagians.nama_bagian', 'CASH AND BANK (CASHIER)')
                ->where('divisis.nama_divisi', 'FINANCE')
                ->where('departemens.nama_departemen', 'FINANCE AND ACCOUNTING')
                ->select('users.*')
                ->get();
        } else if ($user->kontrak_kerja == 'SPS') {
            $hrd = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                // ->where('jabatans.holding', 'sp')
                ->where('jabatans.nama_jabatan', 'MANAGER')
                ->where('bagians.nama_bagian', 'HRD & GA')
                ->where('divisis.nama_divisi', 'HRD & GA')
                ->where('departemens.nama_departemen', 'HRD & GA')
                ->select('users.*')
                ->get();
            // diana sps
            $finance = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                ->where('jabatans.holding', 'sps')
                ->where('bagians.nama_bagian', 'CASH AND BANK (CASHIER)')
                ->where('divisis.nama_divisi', 'FINANCE')
                ->where('departemens.nama_departemen', 'FINANCE AND ACCOUNTING')
                ->select('users.*')
                ->get();
            // dd($finance);
        } else {
            $hrd = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                // ->where('jabatans.holding', 'sp')
                ->where('jabatans.nama_jabatan', 'MANAGER')
                ->where('bagians.nama_bagian', 'HRD & GA')
                ->where('divisis.nama_divisi', 'HRD & GA')
                ->where('departemens.nama_departemen', 'HRD & GA')
                ->select('users.*')
                ->get();
            // diana sps
            $finance = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                ->where('jabatans.holding', 'sip')
                ->where('bagians.nama_bagian', 'CASH AND BANK (CASHIER)')
                ->where('divisis.nama_divisi', 'FINANCE')
                ->where('departemens.nama_departemen', 'FINANCE AND ACCOUNTING')
                ->select('users.*')
                ->get();
        }

        // dd($hrd);
        return view('users.penugasan.edit', [
            'penugasan'     => $penugasan,
            'user'          => $user,
            'diminta'          => $diminta,
            'disahkan'          => $disahkan,
            'hrd'          => $hrd,
            'finance'          => $finance,
            'id_penugasan'  => $id,
            'master_lokasi'  => $master_lokasi,
        ]);
    }

    public function penugasanUpdate(Request $request, $id)
    {
        if ($request->alamat_dikunjungi == NULL) {
            $alamat_dikunjungi = $request->alamat_dikunjungi1;
        } else {
            $alamat_dikunjungi = $request->alamat_dikunjungi;
        }
        $folderPath     = public_path('signature/');
        $image_parts    = explode(";base64,", $request->signature);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type     = $image_type_aux[1];
        $image_base64   = base64_decode($image_parts[1]);
        $uniqid         = date('y-m-d') . '-' . uniqid();
        $file           = $folderPath . $uniqid . '.' . $image_type;
        file_put_contents($file, $image_base64);
        $data                               = Penugasan::find($id);
        $data->asal_kerja                   = $request->asal_kerja;
        $data->penugasan                    = $request->penugasan;
        $data->wilayah_penugasan                    = $request->wilayah_penugasan;
        $data->tanggal_kunjungan            = $request->tanggal_kunjungan;
        $data->selesai_kunjungan            = $request->selesai_kunjungan;
        $data->kegiatan_penugasan           = $request->kegiatan_penugasan;
        $data->pic_dikunjungi               = $request->pic_dikunjungi;
        $data->alamat_dikunjungi            = $alamat_dikunjungi;
        $data->transportasi                 = $request->transportasi;
        $data->kelas                        = $request->kelas;
        $data->budget_hotel                 = $request->budget_hotel;
        $data->makan                        = $request->makan;
        $data->id_user_hrd                  = User::where('id', $request->proses_hrd)->value('id');
        $data->nama_hrd                     = User::where('id', $request->proses_hrd)->value('name');
        $data->id_user_finance              = User::where('id', $request->proses_finance)->value('id');
        $data->nama_finance                 = User::where('id', $request->proses_finance)->value('name');
        $data->ttd_id_diajukan_oleh         = $uniqid;
        $data->waktu_ttd_id_diajukan_oleh   = date('Y-m-d H:i:s');
        $data->status_penugasan             = 1;
        $data->save();
        $request->session()->flash('updatesukses', 'Berhasil Membuat Perdin');
        return redirect('/penugasan/dashboard');
    }


    public function approveShow($id)
    {
        $user       = DB::table('users')->join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
            ->join('departemens', 'departemens.id', '=', 'users.dept_id')
            ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
            ->where('users.id', Auth()->user()->id)->first();
        $penugasan  = DB::table('penugasans')->join('jabatans', 'jabatans.id', 'penugasans.id_jabatan')
            ->join('departemens', 'departemens.id', 'penugasans.id_departemen')
            ->join('users', 'users.id', 'penugasans.id_user')
            ->join('divisis', 'divisis.id', 'penugasans.id_divisi')
            ->where('penugasans.id', $id)->first();
        // dd($penugasan);
        // $id_penugasan   = $id;
        $diminta = User::where(['id' => $penugasan->id_diminta_oleh])->first();
        $disahkan = User::where(['id' => $penugasan->id_disahkan_oleh])->first();
        $hrd = User::where('id', 'e30d4a42-5562-415c-b1b6-f6b9ccc379a1')->first();
        if ($user->kontrak_kerja == 'sp') {
            // kasir SP
            $finance = User::where('id', '436da676-5782-4f4e-ad50-52b45060430c')->first();
        } else {
            // diana sps
            $finance = User::where('id', 'b709b754-7b00-4118-ab3f-e9b2760b08cf')->first();
        }
        $id_penugasan   = DB::table('penugasans')->where('id', $id)->first();
        return view('users.penugasan.approve', [
            'penugasan' => $penugasan,
            'user'      => $user,
            'id_penugasan'  => $id_penugasan,
            'diminta'  => $diminta,
            'disahkan'  => $disahkan,
            'hrd'  => $hrd,
            'finance'  => $finance,
        ]);
    }

    public function approvePenugasan(Request $request, $id)
    {
        // dd($request->all());
        $folderPath     = public_path('signature/');
        $image_parts    = explode(";base64,", $request->signature);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type     = $image_type_aux[1];
        $image_base64   = base64_decode($image_parts[1]);
        $uniqid         = date('Y-m-d') . '-' . uniqid();
        $file           = $folderPath . $uniqid . '.' . $image_type;
        file_put_contents($file, $image_base64);
        $data                               = Penugasan::find($id);
        if ($request->status_penugasan == 2) {
            $data->ttd_id_diminta_oleh          = $uniqid;
            $data->waktu_ttd_id_diminta_oleh    = date('Y-m-d H:i:s');
        } else if ($request->status_penugasan == 3) {
            $data->ttd_id_disahkan_oleh          = $uniqid;
            $data->waktu_ttd_id_disahkan_oleh    = date('Y-m-d H:i:s');
        } else if ($request->status_penugasan == 4) {
            $data->ttd_proses_hrd          = $uniqid;
            $data->waktu_ttd_proses_hrd    = date('Y-m-d H:i:s');
        } else if ($request->status_penugasan == 5) {
            $data->ttd_proses_finance          = $uniqid;
            $data->waktu_ttd_proses_finance    = date('Y-m-d H:i:s');
        }
        $data->status_penugasan             = $request->status_penugasan;
        $data->save();
        $request->session()->flash('approveperdinsukses', 'Berhasil Approve Perjalanan Dinas');
        return redirect('/home');
    }
    public function delete_penugasan(Request $request, $id)
    {
        // dd($id);
        $query = Penugasan::where('id', $id)->delete();
        $request->session()->flash('hapussukses', 'Berhasil MembuatHapus Perdin');
        return redirect('penugasan/dashboard');
    }
    public function cetak_form_penugasan($id)
    {
        $jabatan = Jabatan::join('users', function ($join) {
            $join->on('jabatans.id', '=', 'users.jabatan_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
        })->where('users.id', Auth::user()->id)->get();
        $divisi = Divisi::join('users', function ($join) {
            $join->on('divisis.id', '=', 'users.divisi_id');
            $join->orOn('divisis.id', '=', 'users.divisi1_id');
            $join->orOn('divisis.id', '=', 'users.divisi2_id');
            $join->orOn('divisis.id', '=', 'users.divisi3_id');
            $join->orOn('divisis.id', '=', 'users.divisi4_id');
        })->where('users.id', Auth::user()->id)->get();
        $cuti = Pen::where('id', $id)->first();
        $departemen = Departemen::where('id', Auth::user()->dept_id)->first();
        $pengganti = User::where('id', $cuti->user_id_backup)->first();
        // dd(Cuti::with('KategoriCuti')->with('User')->where('cutis.id', $id)->where('cutis.status_cuti', '3')->first());
        $data = [
            'title' => 'domPDF in Laravel 10',
            'data_cuti' => Cuti::with('KategoriCuti')->with('User')->where('cutis.id', $id)->where('cutis.status_cuti', '3')->first(),
            'jabatan' => $jabatan,
            'divisi' => $divisi,
            'departemen' => $departemen,
            'pengganti' => $pengganti,
        ];
        $pdf = PDF::loadView('users/cuti/form_cuti', $data);
        return $pdf->download('FORM_PENGAJUAN_CUTI_' . Auth::user()->name . '_' . date('Y-m-d H:i:s') . '.pdf');
    }
}
