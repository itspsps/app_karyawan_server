<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MappingShift;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;
use App\Models\Jabatan;
use App\Models\Izin;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\KategoriIzin;
use App\Models\LevelJabatan;
use App\Models\Lokasi;
use DateTime;
use DB;
use Maatwebsite\Excel\Excel;
use Ramsey\Uuid\Uuid;

class IzinUserController extends Controller
{
    public function index(Request $request)
    {
        $user_id        = Auth()->user()->id;
        $kontrak = Auth::guard('web')->user()->kontrak_kerja;
        $site_job = Auth::guard('web')->user()->site_job;
        $lokasi_site_job = Lokasi::where('lokasi_kantor', $site_job)->first();
        // dd($user);
        if ($kontrak == '') {
            $request->session()->flash('kontrakkerjaNULL');
            return redirect('/home');
        }
        if (Auth::user()->kategori == 'Karyawan Bulanan') {
            $user = DB::table('users')->join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                ->where('users.id', Auth()->user()->id)->first();
            if ($user == NULL) {
                $request->session()->flash('jabatanNULL');
                return redirect('/home');
            } else {
                $IdLevelAtasan = $user->atasan_id;
                if ($IdLevelAtasan == NULL) {
                    $getUserAtasan = NULL;
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->where('dept_id', Auth::user()->dept_id)
                        ->get();
                } else {


                    if ($lokasi_site_job->kategori_kantor == 'sps') {
                        $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan)
                            ->orWhere('jabatan1_id', $IdLevelAtasan)
                            ->orWhere('jabatan2_id', $IdLevelAtasan)
                            ->orWhere('jabatan3_id', $IdLevelAtasan)
                            ->orWhere('jabatan4_id', $IdLevelAtasan)
                            ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->first();
                        // dd($get_nama_jabatan);
                        if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                ->where('jabatans.id', $IdLevelAtasan)
                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                ->first();
                            if ($get_atasan_site->holding == 'sps') {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->where('jabatans.holding', 'sp')
                                    ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            } else {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->where('jabatans.holding', 'sps')
                                    ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            }
                            // dd($get_atasan_more);

                            $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                        } else {

                            $atasan = User::where('jabatan_id', $IdLevelAtasan)
                                ->orWhere('jabatan1_id', $IdLevelAtasan)
                                ->orWhere('jabatan2_id', $IdLevelAtasan)
                                ->orWhere('jabatan3_id', $IdLevelAtasan)
                                ->orWhere('jabatan4_id', $IdLevelAtasan)
                                ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                        }
                        if ($atasan == '') {
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else if ($lokasi_site_job->kategori_kantor == 'sp') {
                        $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan)
                            ->orWhere('jabatan1_id', $IdLevelAtasan)
                            ->orWhere('jabatan2_id', $IdLevelAtasan)
                            ->orWhere('jabatan3_id', $IdLevelAtasan)
                            ->orWhere('jabatan4_id', $IdLevelAtasan)
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->first();
                        // dd($get_nama_jabatan);
                        if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                ->where('jabatans.id', $IdLevelAtasan)
                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                ->first();
                            if ($get_atasan_site->holding == 'sps') {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->where('jabatans.holding', 'sp')
                                    ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            } else {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->where('jabatans.holding', 'sps')
                                    ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            }
                            // dd($get_atasan_more);
                            $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                        } else {
                            $atasan = User::where('jabatan_id', $IdLevelAtasan)
                                ->orWhere('jabatan1_id', $IdLevelAtasan)
                                ->orWhere('jabatan2_id', $IdLevelAtasan)
                                ->orWhere('jabatan3_id', $IdLevelAtasan)
                                ->orWhere('jabatan4_id', $IdLevelAtasan)
                                ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                        }
                        if ($atasan == '') {
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else if ($lokasi_site_job->kategori_kantor == 'sip') {
                        $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan)
                            ->orWhere('jabatan1_id', $IdLevelAtasan)
                            ->orWhere('jabatan2_id', $IdLevelAtasan)
                            ->orWhere('jabatan3_id', $IdLevelAtasan)
                            ->orWhere('jabatan4_id', $IdLevelAtasan)
                            ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                            ->first();
                        // dd($get_nama_jabatan);
                        if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                ->where('jabatans.id', $IdLevelAtasan)
                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                ->first();
                            if ($get_atasan_site->holding == 'sps') {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->where('jabatans.holding', 'sp')
                                    ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            } else {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->where('jabatans.holding', 'sps')
                                    ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            }
                            // dd($get_atasan_more);

                            $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                        } else {

                            $atasan = User::where('jabatan_id', $IdLevelAtasan)
                                ->orWhere('jabatan1_id', $IdLevelAtasan)
                                ->orWhere('jabatan2_id', $IdLevelAtasan)
                                ->orWhere('jabatan3_id', $IdLevelAtasan)
                                ->orWhere('jabatan4_id', $IdLevelAtasan)
                                ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                        }
                        if ($atasan == '') {
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else if ($lokasi_site_job->kategori_kantor == 'all sps') {

                        $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan)
                            ->orWhere('jabatan1_id', $IdLevelAtasan)
                            ->orWhere('jabatan2_id', $IdLevelAtasan)
                            ->orWhere('jabatan3_id', $IdLevelAtasan)
                            ->orWhere('jabatan4_id', $IdLevelAtasan)
                            ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                            ->first();
                        // dd($get_nama_jabatan);
                        if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                ->where('jabatans.id', $IdLevelAtasan)
                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                ->first();
                            if ($get_atasan_site->holding == 'sps') {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->where('jabatans.holding', 'sp')
                                    ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            } else {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->where('jabatans.holding', 'sps')
                                    ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            }
                            // dd($get_atasan_more);

                            $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                        } else {

                            $atasan = User::where('jabatan_id', $IdLevelAtasan)
                                ->orWhere('jabatan1_id', $IdLevelAtasan)
                                ->orWhere('jabatan2_id', $IdLevelAtasan)
                                ->orWhere('jabatan3_id', $IdLevelAtasan)
                                ->orWhere('jabatan4_id', $IdLevelAtasan)
                                ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                        }
                        if ($atasan == '') {
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else if ($lokasi_site_job->kategori_kantor == 'all sp') {

                        $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan)
                            ->orWhere('jabatan1_id', $IdLevelAtasan)
                            ->orWhere('jabatan2_id', $IdLevelAtasan)
                            ->orWhere('jabatan3_id', $IdLevelAtasan)
                            ->orWhere('jabatan4_id', $IdLevelAtasan)
                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                            ->first();
                        // dd($get_nama_jabatan);
                        if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                ->where('jabatans.id', $IdLevelAtasan)
                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                ->first();
                            if ($get_atasan_site->holding == 'sps') {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->where('jabatans.holding', 'sp')
                                    ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            } else {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->where('jabatans.holding', 'sps')
                                    ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            }
                            // dd($get_atasan_more);

                            $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                        } else {
                            $atasan = User::where('jabatan_id', $IdLevelAtasan)
                                ->orWhere('jabatan1_id', $IdLevelAtasan)
                                ->orWhere('jabatan2_id', $IdLevelAtasan)
                                ->orWhere('jabatan3_id', $IdLevelAtasan)
                                ->orWhere('jabatan4_id', $IdLevelAtasan)
                                ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                        }
                        // jika atasan tingkat 1 
                        // dd($atasan);
                        if ($atasan == '') {
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else if ($lokasi_site_job->kategori_kantor == 'all') {
                        $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan)
                            ->orWhere('jabatan1_id', $IdLevelAtasan)
                            ->orWhere('jabatan2_id', $IdLevelAtasan)
                            ->orWhere('jabatan3_id', $IdLevelAtasan)
                            ->orWhere('jabatan4_id', $IdLevelAtasan)
                            // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                            ->first();
                        // dd($get_nama_jabatan);
                        if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                ->where('jabatans.id', $IdLevelAtasan)
                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                ->first();
                            if ($get_atasan_site->holding == 'sps') {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->where('jabatans.holding', 'sp')
                                    ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            } else {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->where('jabatans.holding', 'sps')
                                    ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            }

                            $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                            // dd($atasan);
                        } else {

                            $atasan = User::where('jabatan_id', $IdLevelAtasan)
                                ->orWhere('jabatan1_id', $IdLevelAtasan)
                                ->orWhere('jabatan2_id', $IdLevelAtasan)
                                ->orWhere('jabatan3_id', $IdLevelAtasan)
                                ->orWhere('jabatan4_id', $IdLevelAtasan)
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                            // dd($atasan);
                        }
                        // dd($atasan);
                        // jika atasan tingkat 1 
                        if ($atasan == '') {
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    }
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->where('dept_id', Auth::user()->dept_id)
                        ->get();
                }
            }
        } else if (Auth::user()->kategori == 'Karyawan Harian') {
            $user = DB::table('users')->where('id', Auth()->user()->id)->first();
            $atasan = DB::table('users')
                ->join('mapping_shifts', function ($join) {
                    $join->on('mapping_shifts.koordinator_id', '=', 'users.id');
                })
                ->select('users.*', 'mapping_shifts.koordinator_id')
                ->first();
            $get_user_backup = NULL;
            $getUserAtasan = $atasan;
        }
        // dd($getUserAtasan);
        $jam_kerja = MappingShift::with('Shift')->where('user_id', Auth::user()->id)->where('tanggal_masuk', date('Y-m-d'))->first();
        $record_data    = DB::table('izins')->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
        $kategori_izin = KategoriIzin::orderBy('id', 'ASC')->get();
        if ($jam_kerja == '' || $jam_kerja == NULL) {
            $req_jm_klr = NULL;
        } else {
            $jam_masuk = \Carbon\Carbon::parse($jam_kerja->Shift->jam_masuk)->addMinute(5)->isoFormat('HH:mm');
            // dd($jam_masuk);
            if ($jam_kerja->jam_absen <= $jam_masuk) {
                $req_jm_klr = new DateTime(date('Y-m-d') . $jam_kerja->Shift->jam_masuk);
            } else {
                $req_jm_klr = new DateTime(date('Y-m-d') . $jam_kerja->jam_absen);
            }
        }
        if ($req_jm_klr == '' || $req_jm_klr == NULL) {
            $jam_min_plg_cpt = NULL;
        } else {
            $jam_min_plg_cpt = \Carbon\Carbon::parse($req_jm_klr)->addHour(6)->isoFormat('H:mm');
        }
        // dd($req_jm_klr);
        return view('users.izin.index', [
            'title'             => 'Tambah Permintaan Cuti Karyawan',
            'data_user'         => $user,
            'data_izin_user'    => Cuti::where('user_id', $user_id)->orderBy('id', 'desc')->get(),
            'getUserAtasan'     => $getUserAtasan,
            'user'              => $user,
            'record_data'       => $record_data,
            'kategori_izin'       => $kategori_izin,
            'jam_kerja'       => $jam_kerja,
            'get_user_backup'       => $get_user_backup,
            'jam_min_plg_cpt'       => $jam_min_plg_cpt,
        ]);
    }
    public function izinEdit($id)
    {
        if (Auth::user()->kategori == 'Karyawan Bulanan') {
            $user = DB::table('users')->join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                ->where('users.id', Auth()->user()->id)->first();
        } else if (Auth::user()->kategori == 'Karyawan Harian') {
            $user = DB::table('users')->where('users.id', Auth()->user()->id)->first();
        }
        $get_izin_id = Izin::where('id', $id)->first();
        $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
            ->where('is_admin', 'user')
            ->where('users.id', '!=', Auth::user()->id)
            ->where('users.site_job', Auth::user()->site_job)
            ->where('users.divisi_id', Auth::user()->divisi_id)
            ->orWhere('users.divisi1_id', Auth::user()->divisi_id)
            ->orWhere('users.divisi2_id', Auth::user()->divisi_id)
            ->orWhere('users.divisi3_id', Auth::user()->divisi_id)
            ->orWhere('users.divisi4_id', Auth::user()->divisi_id)
            ->get();
        $kategori_izin = KategoriIzin::orderBy('id', 'ASC')->get();
        $jam_kerja = MappingShift::with('Shift')->where('user_id', Auth::user()->id)->where('tanggal_masuk', date('Y-m-d'))->first();
        // dd($jam_kerja);
        if ($jam_kerja == '' || $jam_kerja == NULL) {
            $req_jm_klr = NULL;
        } else {
            $jam_masuk = \Carbon\Carbon::parse($jam_kerja->Shift->jam_masuk)->addMinute(5)->isoFormat('HH:mm');
            // dd($jam_masuk);
            if ($jam_kerja->jam_absen <= $jam_masuk) {
                $req_jm_klr = new DateTime(date('Y-m-d') . $jam_kerja->Shift->jam_masuk);
            } else {
                $req_jm_klr = new DateTime(date('Y-m-d') . $jam_kerja->jam_absen);
            }
        }
        $jam_min_plg_cpt = \Carbon\Carbon::parse($req_jm_klr)->addHour(6)->isoFormat('H:mm');
        return view(
            'users.izin.edit',
            [
                'user' => $user,
                'get_izin' => $get_izin_id,
                'kategori_izin' => $kategori_izin,
                'jam_kerja' => $jam_kerja,
                'get_user_backup' => $get_user_backup,
                'jam_min_plg_cpt' => $jam_min_plg_cpt,
            ]
        );
    }
    public function izinEditProses(Request $request)
    {
        // dd($request->all());
        if ($request->signature !== null) {
            if ($request->izin_old == $request->izin) {
                $count_izin = Izin::where('izin', $request->izin)->whereMonth('tanggal', date('m'))->count();
                $count_tbl_izin = $count_izin - 1;
            } else {
                $count_tbl_izin = Izin::where('izin', $request->izin)->whereMonth('tanggal', date('m'))->count();
            }
            // dd($count_tbl_izin);
            $get_izin = Izin::where('id', $request->id)->first();
            if ($request->izin == $get_izin->izin) {
                $add = 0;
            } else {
                $add = 1;
            }
            $countstr = strlen($count_tbl_izin + 1);
            if ($countstr == '1') {
                $no = '000' . $count_tbl_izin + $add;
            } else if ($countstr == '2') {
                $no = '00' . $count_tbl_izin + $add;
            } else if ($countstr == '3') {
                $no = '0' . $count_tbl_izin + $add;
            } else {
                $no = $count_tbl_izin + $add;
            }
            if ($request->izin == 'Pulang Cepat') {
                $pulang_cepat = $request->jam_pulang_cepat;
                $terlambat = NULL;
                $id_backup = NULL;
                $name_backup = NULL;
                $img_name = NULL;
                $jam_keluar = NULL;
                $jam_kembali = NULL;
                $catatan_backup = NULL;
                $jam_masuk = NULL;
                $tanggal = $request->tanggal;
                $tanggal_selesai = $request->tanggal;
                if ($request->izin_old == $request->izin) {
                    $no_form = $request->no_form_old;
                } else {
                    $no_form = Auth::user()->kontrak_kerja . '/IP/' . date('Y/m/d') . '/' . $no;
                }
            } else if ($request->izin == 'Keluar Kantor') {
                $jam_keluar = $request->jam_keluar;
                $jam_kembali = $request->jam_kembali;
                $pulang_cepat = NULL;
                $jam_masuk = NULL;
                $img_name = NULL;
                $id_backup = NULL;
                $name_backup = NULL;
                $catatan_backup = NULL;
                $tanggal = $request->tanggal;
                $tanggal_selesai = $request->tanggal;
                $terlambat = NULL;
                if ($request->izin_old == $request->izin) {
                    $no_form = $request->no_form_old;
                } else {
                    $no_form = Auth::user()->kontrak_kerja . '/MK/' . date('Y/m/d') . '/' . $no;
                }
            } else if ($request->izin == 'Tidak Masuk (Mendadak)') {
                $jumlah_hari = explode(' ', $request->tanggal);
                $startDate = trim($jumlah_hari[0]);
                $endDate = trim($jumlah_hari[2]);
                $date1          = new DateTime($startDate);
                $date2          = new DateTime($endDate);
                $interval       = $date1->diff($date2);
                $data_interval  = $interval->days;
                $tanggal = date('Y-m-d', strtotime($startDate));
                $tanggal_selesai = date('Y-m-d', strtotime($endDate));
                // dd($data_interval);
                $jam_keluar = NULL;
                $jam_kembali = NULL;
                $pulang_cepat = NULL;
                $terlambat = NULL;
                $jam_masuk = NULL;
                $img_name = NULL;
                $id_backup = $request->user_backup;
                $name_backup = User::where('id', $request->user_backup)->value('name');
                $catatan_backup = $request->catatan_backup;
                if ($request->izin_old == $request->izin) {
                    $no_form = $request->no_form_old;
                } else {
                    $no_form = Auth::user()->kontrak_kerja . '/FPI/' . date('Y/m/d') . '/' . $no;
                }
            } else if ($request->izin == 'Sakit') {
                if ($request->foto_izin_lama == 'TIDAK ADA' || $request->foto_izin_lama == NULL) {
                    if ($request['file_sakit']) {
                        // dd('ok');
                        $extension     = $request->file('file_sakit')->extension();
                        // dd($extension);
                        $img_name         = date('y-m-d') . '-' . Uuid::uuid4() . '.' . $extension;
                        $path           = Storage::putFileAs('foto_izin/', $request->file('file_sakit'), $img_name);
                    } else {
                        $img_name = 'TIDAK ADA';
                    }
                } else {
                    $delete           = Storage::delete('foto_izin/', $request->file('file_sakit'));
                }
                // $jam_pulang_cepat = $request->pulang_cepat;
                $jumlah_hari = explode(' ', $request->tanggal);
                $startDate = trim($jumlah_hari[0]);
                $endDate = trim($jumlah_hari[2]);
                $date1          = new DateTime($startDate);
                $date2          = new DateTime($endDate);
                $interval       = $date1->diff($date2);
                $data_interval  = $interval->days;
                $tanggal = date('Y-m-d', strtotime($startDate));
                $tanggal_selesai = date('Y-m-d', strtotime($endDate));
                // dd($tanggal_selesai);
                // $jam_pulang_cepat = $request->pulang_cepat;
                $terlambat = NULL;
                $jam_masuk = NULL;
                $pulang_cepat = NULL;
                $jam_keluar = NULL;
                $jam_kembali = NULL;
                $no_form = NULL;
                $id_backup = NULL;
                $name_backup = NULL;
                $catatan_backup = NULL;
            } else if ($request->izin == 'Datang Terlambat') {
                // dd($request->all());
                $pulang_cepat = NULL;
                $jam_keluar = NULL;
                $jam_kembali = NULL;
                $id_backup = NULL;
                $name_backup = NULL;
                $catatan_backup = NULL;
                $terlambat = $request->terlambat;
                $jam_masuk = $request->jam_masuk;
                $img_name = NULL;
                $tanggal = $request->tanggal;
                $tanggal_selesai = $request->tanggal;
                if ($request->izin_old == $request->izin) {
                    $no_form = $request->no_form_old;
                } else {
                    $no_form = Auth::user()->kontrak_kerja . '/FKDT/' . date('Y/m/d') . '/' . $no;
                }
            } else {
                $catatan_backup = NULL;
                $id_backup = NULL;
                $name_backup = NULL;
                $img_name = NULL;
                $pulang_cepat = NULL;
                $terlambat  = NULL;
                $jam_masuk = NULL;
            }
            $folderPath     = public_path('signature/');
            $image_parts    = explode(";base64,", $request->signature);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type     = $image_type_aux[1];
            $image_base64   = base64_decode($image_parts[1]);
            $uniqid         = date('y-m-d') . '-' . uniqid();
            $file           = $folderPath . $uniqid . '.' . $image_type;
            file_put_contents($file, $image_base64);
            $data                   = Izin::where('id', $request['id'])->first();
            $data->user_id          = $request->id_user;
            $data->departements_id  = Departemen::where('id', $request["departements"])->value('id');
            $data->jabatan_id       = Jabatan::where('id', $request["jabatan"])->value('id');
            $data->divisi_id        = Divisi::where('id', $request["divisi"])->value('id');
            $data->telp             = $request->telp;
            $data->email            = $request->email;
            $data->fullname         = $request->fullname;
            $data->izin             = $request->izin;
            $data->terlambat        = $terlambat;
            $data->tanggal          = $tanggal;
            $data->tanggal_selesai  = $tanggal_selesai;
            $data->catatan_backup  = $catatan_backup;
            $data->jam_masuk_kerja  = $jam_masuk;
            $data->pulang_cepat     = $pulang_cepat;
            $data->user_id_backup   = $id_backup;
            $data->user_name_backup = $name_backup;
            $data->jam_keluar       = $jam_keluar;
            $data->jam              = $request->jam;
            $data->jam_kembali     = $jam_kembali;
            $data->keterangan_izin  = $request->keterangan_izin;
            $data->no_form_izin  = $no_form;
            $data->approve_atasan   = $request->approve_atasan;
            $data->id_approve_atasan = $request->id_user_atasan;
            $data->ttd_pengajuan    = $uniqid;
            $data->waktu_ttd_pengajuan    = date('Y-m-d');
            if ($request->level_jabatan == '1') {
                $data->status_izin      = 2;
            } else {
                $data->status_izin      = 1;
            }
            $data->update();
            $request->session()->flash('izineditsuccess');
            return redirect('/izin/dashboard');
        } else {
            Alert::info('info', 'Tanda Tangan Harus Terisi');
            return redirect()->back()->with('info', 'Tanda Tangan Harus Terisi');
        }
    }

    public function izinAbsen(Request $request)
    {
        $jam_kerja = MappingShift::with('Shift')->where('user_id', Auth::user()->id)->where('tanggal_masuk', date('Y-m-d'))->first();
        if ($jam_kerja == '' || $jam_kerja == NULL) {
            $request->session()->flash('mapping_kosong');
            return redirect('/izin/dashboard');
        } else {
            if ($request->id_user_atasan == NULL || $request->id_user_atasan == '') {
                if ($request->level_jabatan != '1') {
                    $request->session()->flash('atasankosong');
                    return redirect('/izin/dashboard');
                } else {
                    $data                   = new Izin();
                    $data->user_id          = $request->id_user;
                    $data->departements_id  = Departemen::where('id', $request["departements"])->value('id');
                    $data->jabatan_id       = Jabatan::where('id', $request["jabatan"])->value('id');
                    $data->divisi_id        = Divisi::where('id', $request["divisi"])->value('id');
                    $data->telp             = $request->telp;
                    $data->email            = $request->email;
                    $data->fullname         = $request->fullname;
                    $data->izin             = $request->izin;
                    $data->tanggal          = $request->tanggal;
                    $data->jam              = $request->jam;
                    $data->keterangan_izin  = $request->keterangan_izin;
                    $data->status_izin      = 0;
                    $data->ttd_atasan      = NULL;
                    $data->waktu_approve      = NULL;
                    $data->save();
                    $request->session()->flash('izinsuccess');
                    return redirect('/izin/dashboard');
                }
            } else {
                // No form
                $count_tbl_izin = Izin::where('izin', $request->izin)->count();
                // dd($count_tbl_izin);
                $countstr = strlen($count_tbl_izin + 1);
                if ($countstr == '1') {
                    $no = '000' . $count_tbl_izin + 1;
                } else if ($countstr == '2') {
                    $no = '00' . $count_tbl_izin + 1;
                } else if ($countstr == '3') {
                    $no = '0' . $count_tbl_izin + 1;
                } else {
                    $no = $count_tbl_izin + 1;
                }
                if ($request->izin == 'Datang Terlambat') {
                    // dd('ok');
                    if ($jam_kerja->jam_pulang != '') {
                        $request->session()->flash('absen_pulang_terisi');
                        return redirect('/izin/dashboard');
                    } else {
                        $jam_terlambat = $request->terlambat;
                        $jam_masuk_kerja = $request->jam_masuk;
                        $jam_pulang_cepat = NULL;
                        $img_name = NULL;
                        $jam_keluar = NULL;
                        $jam_kembali = NULL;
                        $id_backup = NULL;
                        $name_backup = NULL;
                        $catatan_backup = NULL;
                        $tanggal = $request->tanggal;
                        $tanggal_selesai = NULL;
                        $no_form = Auth::user()->kontrak_kerja . '/SK/FKDT/' . date('Y/m/d') . '/' . $no;
                    }
                } else if ($request->izin == 'Pulang Cepat') {
                    // $req_plg_cpt = new DateTime(date('Y-m-d') . $request->jam_pulang_cepat);
                    // $req_jm_klr = new DateTime(date('Y-m-d') . $jam_kerja->Shift->jam_keluar);
                    // $jam_plg_cpt = $req_plg_cpt->diff($req_jm_klr);
                    // if ($jam_plg_cpt->h == 3 && $jam_plg_cpt->i > 0) {
                    // }
                    if ($jam_kerja->jam_absen == '' && $jam_kerja->jam_pulang == '') {
                        $request->session()->flash('absen_masuk_kosong');
                        return redirect('/izin/dashboard');
                    } else if ($jam_kerja->jam_pulang != '') {
                        $request->session()->flash('absen_pulang_terisi');
                        return redirect('/izin/dashboard');
                    } else {
                        // dd($request->all());
                        $id_backup = NULL;
                        $name_backup = NULL;
                        $jam_pulang_cepat = $request->jam_pulang_cepat;
                        $jam_terlambat = NULL;
                        $jam_masuk_kerja = NULL;
                        $img_name = NULL;
                        $jam_keluar = NULL;
                        $jam_kembali = NULL;
                        $catatan_backup = NULL;
                        $tanggal = date('Y-m-d');
                        $tanggal_selesai = NULL;
                        $no_form = Auth::user()->kontrak_kerja . '/IP/' . date('Y/m/d') . '/' . $no;
                    }
                } else if ($request->izin == 'Keluar Kantor') {
                    if ($jam_kerja->jam_pulang != '') {
                        $request->session()->flash('absen_pulang_terisi');
                        return redirect('/izin/dashboard');
                    } else {
                        $jam_keluar = $request->jam_keluar;
                        $jam_kembali = $request->jam_kembali;
                        $jam_pulang_cepat = NULL;
                        $jam_terlambat = NULL;
                        $jam_masuk_kerja = NULL;
                        $img_name = NULL;
                        $id_backup = NULL;
                        $name_backup = NULL;
                        $catatan_backup = NULL;
                        $tanggal = date('Y-m-d');
                        $tanggal_selesai = date('Y-m-d');
                        $no_form = Auth::user()->kontrak_kerja . '/MK/' . date('Y/m/d') . '/' . $no;
                    }
                } else if ($request->izin == 'Tidak Masuk (Mendadak)') {
                    $jumlah_hari = explode(' ', $request->tanggal);
                    $startDate = trim($jumlah_hari[0]);
                    $endDate = trim($jumlah_hari[2]);
                    $date1          = new DateTime($startDate);
                    $date2          = new DateTime($endDate);
                    $interval       = $date1->diff($date2);
                    $data_interval  = $interval->days;
                    $tanggal = date('Y-m-d', strtotime($startDate));
                    $tanggal_selesai = date('Y-m-d', strtotime($endDate));
                    // dd($data_interval);
                    $jam_keluar = $request->jam_keluar;
                    $jam_kembali = $request->jam_kembali;
                    $jam_pulang_cepat = NULL;
                    $jam_terlambat = NULL;
                    $jam_masuk_kerja = NULL;
                    $img_name = NULL;
                    $id_backup = $request->user_backup;
                    $name_backup = User::where('id', $request->user_backup)->value('name');
                    $catatan_backup = $request->catatan_backup;
                    $no_form = Auth::user()->kontrak_kerja . '/MK/' . date('Y/m/d') . '/' . $no;
                } else if ($request->izin == 'Sakit') {

                    if ($request['file_sakit']) {
                        // dd($request->all());
                        // dd('ok');
                        $extension     = $request->file('file_sakit')->extension();
                        // dd($extension);
                        $img_name         = date('y-m-d') . '-' . Uuid::uuid4() . '.' . $extension;
                        $path           = Storage::putFileAs('foto_izin/', $request->file('file_sakit'), $img_name);
                    } else {
                        // dd($request->all());
                        $img_name = 'TIDAK ADA';
                    }
                    $jumlah_hari = explode(' ', $request->tanggal);
                    $startDate = trim($jumlah_hari[0]);
                    $endDate = trim($jumlah_hari[2]);
                    $date1          = new DateTime($startDate);
                    $date2          = new DateTime($endDate);
                    $interval       = $date1->diff($date2);
                    $data_interval  = $interval->days;
                    $tanggal = date('Y-m-d', strtotime($startDate));
                    $tanggal_selesai = date('Y-m-d', strtotime($endDate));
                    // $jam_pulang_cepat = $request->pulang_cepat;
                    $jam_terlambat = NULL;
                    $jam_masuk_kerja = NULL;
                    $jam_pulang_cepat = NULL;
                    $jam_keluar = NULL;
                    $jam_kembali = NULL;
                    $no_form = NULL;
                    $id_backup = NULL;
                    $name_backup = NULL;
                    $catatan_backup = NULL;
                } else {
                    $id_backup = NULL;
                    $catatan_backup = NULL;
                    $name_backup = NULL;
                    $jam_keluar = NULL;
                    $jam_kembali = NULL;
                    $jam_masuk_kerja = NULL;
                    $jam_terlambat = NULL;
                    $jam_pulang_cepat = NULL;
                    $img_name = NULL;
                    $tanggal = $request->tanggal;
                    $tanggal_selesai = NULL;
                }
                $data                   = new Izin();
                $data->user_id          = $request->id_user;
                $data->departements_id  = Departemen::where('id', $request["departements"])->value('id');
                $data->jabatan_id       = Jabatan::where('id', $request["jabatan"])->value('id');
                $data->divisi_id        = Divisi::where('id', $request["divisi"])->value('id');
                $data->telp             = $request->telp;
                $data->terlambat        = $jam_terlambat;
                $data->jam_masuk_kerja  = $jam_masuk_kerja;
                $data->pulang_cepat     = $jam_pulang_cepat;
                $data->email            = $request->email;
                $data->fullname         = $request->fullname;
                $data->izin             = $request->izin;
                $data->user_id_backup   = $id_backup;
                $data->user_name_backup = $name_backup;
                $data->catatan_backup = $catatan_backup;
                $data->foto_izin        = $img_name;
                $data->jam_keluar        = $jam_keluar;
                $data->jam_kembali        = $jam_kembali;
                $data->tanggal          = $tanggal;
                $data->tanggal_selesai   = $tanggal_selesai;
                $data->jam              = $request->jam;
                $data->keterangan_izin  = $request->keterangan_izin;
                $data->approve_atasan   = $request->approve_atasan;
                $data->id_approve_atasan = $request->id_user_atasan;
                $data->status_izin      = 0;
                $data->no_form_izin      = $no_form;
                $data->ttd_atasan      = NULL;
                $data->waktu_approve      = NULL;
                $data->save();
                $request->session()->flash('izinsuccess');
                return redirect('/izin/dashboard');
            }
        }
    }

    public function izinApprove($id)
    {
        $user   = DB::table('users')->join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
            ->join('departemens', 'departemens.id', '=', 'users.dept_id')
            ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
            ->where('users.id', Auth()->user()->id)->first();
        $data   = DB::table('izins')->where('id', $id)->first();
        $jam_kerja = MappingShift::with('Shift')->where('user_id', $data->user_id)->where('tanggal_masuk', date('Y-m-d'))->first();
        return view('users.izin.approveizin', [
            'user'  => $user,
            'jam_kerja'  => $jam_kerja,
            'data'  => $data
        ]);
    }

    public function izinApproveProses(Request $request)
    {
        // dd($request->all());
        if ($request->izin == 'Sakit') {
            if ($request->signature != null) {
                $folderPath     = public_path('signature/');
                $image_parts    = explode(";base64,", $request->signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $uniqid         = date('y-m-d') . '-' . uniqid();
                $file           = $folderPath . $uniqid . '.' . $image_type;
                file_put_contents($file, $image_base64);
                // dd($request->all());
                $date1          = new DateTime($request->tanggal);
                $date2          = new DateTime($request->tanggal_selesai);
                $interval       = $date1->diff($date2);
                $data_interval  = $interval->days;
                $plus_1 = $data_interval + 1;
                $potong_cuti1hari = ($plus_1);
                $potong_cuti2hari = ($plus_1 * 2);
                $get_kuota_cuti = User::where('id', $request->id_user)->first();
                if ($request->foto_izin == NULL) {
                    if ($get_kuota_cuti->kuota_cuti_tahunan > $potong_cuti2hari) {
                        if ($request->approve == 'not_approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = 'NOT APPROVE';
                            $data->catatan      = $request->catatan;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();
                            $user_pengajuan = User::where('id', $request->id_user)->first();
                            $user_pengajuan->kuota_cuti_tahunan = ($get_kuota_cuti->kuota_cuti_tahunan - $potong_cuti2hari);
                            $user_pengajuan->update();
                            $alert = $request->session()->flash('approveizin_not_approve');
                            return response()->json($alert);
                        } else if ($request->approve == 'approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = $request->status_izin;
                            $data->catatan      = $request->catatan;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();
                            $user_pengajuan = User::where('id', $request->id_user)->first();
                            $user_pengajuan->kuota_cuti_tahunan = ($get_kuota_cuti->kuota_cuti_tahunan - $potong_cuti2hari);
                            $user_pengajuan->update();
                            $alert = $request->session()->flash('approveizin_success');
                            return response()->json($alert);
                        }
                    } else {
                        if ($request->approve == 'not_approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = 'NOT APPROVE';
                            $data->catatan      = $request->catatan;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();
                            $alert = $request->session()->flash('approveizin_not_approve');
                            return response()->json($alert);
                        } else if ($request->approve == 'approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = $request->status_izin;
                            $data->catatan      = $request->catatan;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();
                        }
                        $update_izin = Izin::where('id', $request->id)->where('user_id', $request->id_user)->where('izin', 'Sakit')->where('status_izin', '1')->get();
                        // dd($update_izin);
                        foreach ($update_izin as $mapping) {
                            $update = MappingShift::where('user_id', $request->id_user)
                                ->whereBetween('tanggal_masuk', [$mapping->tanggal, $mapping->tanggal_selesai])
                                ->update([
                                    'keterangan_absensi' => 'Potong Gaji ,izin sakit tanpa SKD',
                                    'status_absen' => 'Tidak Masuk',
                                ]);
                        }
                        $alert = $request->session()->flash('approveizin_success');
                        return response()->json($alert);
                        // dd('ok1');
                    }
                } else {
                    if ($get_kuota_cuti->kuota_cuti_tahunan > $potong_cuti1hari) {
                        if ($request->approve == 'not_approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = 'NOT APPROVE';
                            $data->catatan      = $request->catatan;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();
                            $user_pengajuan = User::where('id', $request->id_user)->first();
                            $user_pengajuan->kuota_cuti_tahunan = ($get_kuota_cuti->kuota_cuti_tahunan - $potong_cuti1hari);
                            $user_pengajuan->update();
                            $alert = $request->session()->flash('approveizin_not_approve');
                            return response()->json($alert);
                        } else if ($request->approve == 'approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = $request->status_izin;
                            $data->catatan      = $request->catatan;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();
                            $user_pengajuan = User::where('id', $request->id_user)->first();
                            $user_pengajuan->kuota_cuti = ($get_kuota_cuti->kuota_cuti - $potong_cuti1hari);
                            $user_pengajuan->update();
                            $alert = $request->session()->flash('approveizin_success');
                            return response()->json($alert);
                        }
                    } else {
                        if ($request->approve == 'not_approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = 'NOT APPROVE';
                            $data->catatan      = $request->catatan;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();
                            $alert = $request->session()->flash('approveizin_not_approve');
                            return response()->json($alert);
                        } else if ($request->approve == 'approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = $request->status_izin;
                            $data->catatan      = $request->catatan;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();
                        }
                        $update_izin = Izin::where('id', $request->id)->where('user_id', $request->id_user)->where('izin', 'Sakit')->where('status_izin', '1')->get();
                        // dd($update_izin);
                        foreach ($update_izin as $mapping) {
                            $update = MappingShift::where('user_id', $request->id_user)
                                ->whereBetween('tanggal_masuk', [$mapping->tanggal, $mapping->tanggal_selesai])
                                ->update([
                                    'keterangan_absensi' => 'Potong Gaji ,izin sakit tanpa SKD',
                                    'status_absen' => 'Tidak Masuk',
                                ]);
                        }
                        $alert = $request->session()->flash('approveizin_success');
                        return response()->json($alert);
                        // dd('ok1');
                    }
                }
            } else {
                Alert::info('info', 'Tanda Tangan Harus Terisi');
                return redirect()->back()->with('info', 'Tanda Tangan Harus Terisi');
            }
        } else if ($request->izin == 'Tidak Masuk (Mendadak)') {
            if ($request->signature != null) {
                $folderPath     = public_path('signature/');
                $image_parts    = explode(";base64,", $request->signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $uniqid         = date('y-m-d') . '-' . uniqid();
                $file           = $folderPath . $uniqid . '.' . $image_type;
                file_put_contents($file, $image_base64);
                $date1          = new DateTime($request->tanggal);
                $date2          = new DateTime($request->tanggal_selesai);
                $interval       = $date1->diff($date2);
                $data_interval  = $interval->days;
                $plus_1 = $data_interval + 1;
                $potong_cuti1hari = ($plus_1);
                $potong_cuti2hari = ($plus_1 * 2);
                $get_kuota_cuti = User::where('id', $request->id_user)->first();
                if ($get_kuota_cuti->kuota_cuti_tahunan > $plus_1) {
                    if ($request->approve == 'not_approve') {
                        $data = Izin::where('id', $request->id)->first();
                        $data->status_izin  = 'NOT APPROVE';
                        $data->catatan      = $request->catatan;
                        $data->waktu_approve = date('Y-m-d H:i:s');
                        $data->update();
                        $user_pengajuan = User::where('id', $request->id_user)->first();
                        $user_pengajuan->kuota_cuti_tahunan = ($get_kuota_cuti->kuota_cuti_tahunan - $potong_cuti2hari);
                        $user_pengajuan->update();
                        $update_izin = Izin::where('id', $request->id)->where('user_id', $request->id_user)->where('izin', 'Tidak Masuk (Mendadak)')->where('status_izin', '1')->get();
                        // dd($update_izin);
                        foreach ($update_izin as $mapping) {
                            $update = MappingShift::where('user_id', $request->id_user)
                                ->whereBetween('tanggal_masuk', [$mapping->tanggal, $mapping->tanggal_selesai])
                                ->update([
                                    'keterangan_absensi' => 'Potong saldo cuti 2  ,Atasan Not Approve',
                                    'status_absen' => 'Masuk',
                                ]);
                        }
                        $alert = $request->session()->flash('approveizin_not_approve');
                        return response()->json($alert);
                    } else if ($request->approve == 'approve') {
                        $data = Izin::where('id', $request->id)->first();
                        $data->status_izin  = $request->status_izin;
                        $data->catatan      = $request->catatan;
                        $data->waktu_approve = date('Y-m-d H:i:s');
                        $data->update();
                        $user_pengajuan = User::where('id', $request->id_user)->first();
                        $user_pengajuan->kuota_cuti_tahunan = ($get_kuota_cuti->kuota_cuti_tahunan - $potong_cuti1hari);
                        $user_pengajuan->update();
                        $update_izin = Izin::where('id', $request->id)->where('user_id', $request->id_user)->where('izin', 'Tidak Masuk (Mendadak)')->where('status_izin', '1')->get();
                        // dd($update_izin);
                        foreach ($update_izin as $mapping) {
                            $update = MappingShift::where('user_id', $request->id_user)
                                ->whereBetween('tanggal_masuk', [$mapping->tanggal, $mapping->tanggal_selesai])
                                ->update([
                                    'keterangan_absensi' => 'Izin Tidak Masuk Disetujui',
                                    'status_absen' => 'Masuk',
                                ]);
                        }
                        $alert = $request->session()->flash('approveizin_success');
                        return response()->json($alert);
                    }
                } else {
                    if ($request->approve == 'not_approve') {
                        $data = Izin::where('id', $request->id)->first();
                        $data->status_izin  = 'NOT APPROVE';
                        $data->catatan      = $request->catatan;
                        $data->waktu_approve = date('Y-m-d H:i:s');
                        $data->update();
                        $user_pengajuan = User::where('id', $request->id_user)->first();
                        $user_pengajuan->kuota_cuti_tahunan = ($get_kuota_cuti->kuota_cuti_tahunan - $potong_cuti2hari);
                        $user_pengajuan->update();
                        $update_izin = Izin::where('id', $request->id)->where('user_id', $request->id_user)->where('izin', 'Tidak Masuk (Mendadak)')->where('status_izin', '1')->get();
                        // dd($update_izin);
                        foreach ($update_izin as $mapping) {
                            $update = MappingShift::where('user_id', $request->id_user)
                                ->whereBetween('tanggal_masuk', [$mapping->tanggal, $mapping->tanggal_selesai])
                                ->update([
                                    'keterangan_absensi' => 'Potong Gaji ,izin Tidak Masuk,belum dapat saldo Cuti',
                                    'status_absen' => 'Tidak Masuk',
                                ]);
                        }
                        $alert = $request->session()->flash('approveizin_not_approve');
                        return response()->json($alert);
                    } else if ($request->approve == 'approve') {
                        $data = Izin::where('id', $request->id)->first();
                        $data->status_izin  = $request->status_izin;
                        $data->catatan      = $request->catatan;
                        $data->waktu_approve = date('Y-m-d H:i:s');
                        $data->update();
                        $update_izin = Izin::where('id', $request->id)->where('user_id', $request->id_user)->where('izin', 'Tidak Masuk (Mendadak)')->where('status_izin', '1')->get();

                        foreach ($update_izin as $mapping) {
                            $update = MappingShift::where('user_id', $request->id_user)
                                ->whereBetween('tanggal_masuk', [$mapping->tanggal, $mapping->tanggal_selesai])
                                ->update([
                                    'keterangan_absensi' => 'Potong Gaji ,izin Tidak Masuk,belum dapat saldo Cuti',
                                    'status_absen' => 'Tidak Masuk',
                                ]);
                        }
                        $alert = $request->session()->flash('approveizin_success');
                        return response()->json($alert);
                    }
                    // dd('ok1');
                }
            } else {
                Alert::info('info', 'Tanda Tangan Harus Terisi');
                return redirect()->back()->with('info', 'Tanda Tangan Harus Terisi');
            }
        } else if ($request->izin == 'Pulang Cepat') {
            // dd($request->signature);
            // dd('ok');
            if ($request->signature != null) {
                $folderPath     = public_path('signature/');
                $image_parts    = explode(";base64,", $request->signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $uniqid         = date('y-m-d') . '-' . uniqid();
                $file           = $folderPath . $uniqid . '.' . $image_type;
                file_put_contents($file, $image_base64);
                // dd('ok');
                if ($request->approve == 'not_approve') {
                    $data = Izin::where('id', $request->id)->first();
                    $data->status_izin  = 'NOT APPROVE';
                    $data->catatan      = $request->catatan;
                    $data->waktu_approve = date('Y-m-d H:i:s');
                    $data->update();
                    $alert = $request->session()->flash('approveizin_not_approve');
                    return response()->json($alert);
                } else if ($request->approve == 'approve') {
                    // dd($request->tanggal);
                    $mapping = MappingShift::where('tanggal_masuk', $request->tanggal)
                        ->where('user_id', $request->id_user)
                        ->first();
                    // dd($mapping);
                    $data = Izin::where('id', $request->id)->first();
                    $data->status_izin  = $request->status_izin;
                    $data->catatan      = $request->catatan;
                    $data->waktu_approve = date('Y-m-d H:i:s');
                    $data->update();
                    $mapping            = MappingShift::where('id', $mapping->id)->first();
                    $mapping->izin_id      = $data->id;
                    $mapping->update();

                    $alert = $request->session()->flash('approveizin_success');
                    return response()->json($alert);
                }
            } else {
                Alert::info('info', 'Tanda Tangan Harus Terisi');
                return redirect()->back()->with('info', 'Tanda Tangan Harus Terisi');
            }
        } else if ($request->izin == 'Datang Terlambat') {
            if ($request->signature != null) {
                $folderPath     = public_path('signature/');
                $image_parts    = explode(";base64,", $request->signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $uniqid         = date('y-m-d') . '-' . uniqid();
                $file           = $folderPath . $uniqid . '.' . $image_type;
                file_put_contents($file, $image_base64);
                // dd('ok');
                if ($request->approve == 'not_approve') {
                    $data = Izin::where('id', $request->id)->first();
                    $data->status_izin  = 'NOT APPROVE';
                    $data->ttd_atasan      = $uniqid;
                    $data->catatan      = $request->catatan;
                    $data->waktu_approve = date('Y-m-d H:i:s');
                    $data->update();
                    $alert = $request->session()->flash('approveizin_not_approve');
                    return response()->json($alert);
                } else if ($request->approve == 'approve') {
                    $data = Izin::where('id', $request->id)->first();
                    $data->status_izin  = $request->status_izin;
                    $data->ttd_atasan      = $uniqid;
                    $data->catatan      = $request->catatan;
                    $data->waktu_approve = date('Y-m-d H:i:s');
                    $data->update();

                    $mapping = MappingShift::where('tanggal_masuk', $request->tanggal)
                        ->orWhere('user_id', $request->id_user)
                        ->get();
                    // dd($mapping);
                    $alert = $request->session()->flash('approveizin_success');
                    return response()->json($alert);
                }
            } else {
                Alert::info('info', 'Tanda Tangan Harus Terisi');
                return redirect()->back()->with('info', 'Tanda Tangan Harus Terisi');
            }
        }
    }
    public function delete_izin(Request $request, $id)
    {
        // dd($id);
        $query = Izin::where('id', $id)->delete();
        $request->session()->flash('hapus_izin_sukses');
        return redirect('izin/dashboard');
    }
    public function cetak_form_izin($id)
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
        $izin = Izin::where('id', $id)->first();
        $date1          = new DateTime($izin->tanggal);
        $date2          = new DateTime($izin->tanggal_selesai);
        $interval       = $date1->diff($date2);
        $data_interval  = $interval->days;
        // dd($data_interval);
        $departemen = Departemen::where('id', Auth::user()->dept_id)->first();
        $user_backup = User::where('id', $izin->user_id_backup)->first();
        // dd(Izin::with('User')->where('izins.id', $id)->where('izins.status_izin', '2')->first());
        $jam_kerja = MappingShift::with('Shift')->where('user_id', $izin->user_id)->where('tanggal_masuk', date('Y-m-d'))->first();
        $data = [
            'data_izin' => Izin::with('User')->where('izins.id', $id)->where('izins.status_izin', '2')->first(),
            'jabatan' => $jabatan,
            'divisi' => $divisi,
            'departemen' => $departemen,
            'jam_kerja' => $jam_kerja,
            'user_backup' => $user_backup,
            'data_interval' => $data_interval,
        ];
        if ($izin->izin == 'Datang Terlambat') {
            $pdf = PDF::loadView('users/izin/form_izin_terlambat', $data)->setPaper('A5', 'landscape');
            return $pdf->download('FORM_KETERANGAN_DATANG_TERLAMBAT_' . Auth::user()->name . '_' . date('Y-m-d H:i:s') . '.pdf');
        } else if ($izin->izin == 'Tidak Masuk (Mendadak)') {
            $pdf = PDF::loadView('users/izin/form_izin_tidak_masuk', $data);
            return $pdf->stream('FORM_PENGAJUAN_IZIN_TIDAK_MASUK_' . Auth::user()->name . '_' . date('Y-m-d H:i:s') . '.pdf');
        } else if ($izin->izin == 'Pulang Cepat') {
            $pdf = PDF::loadView('users/izin/form_izin_pulang_cepat', $data)->setPaper('A5', 'landscape');
            return $pdf->stream('FORM_PENGAJUAN_IZIN_PULANG_CEPAT_' . Auth::user()->name . '_' . date('Y-m-d H:i:s') . '.pdf');
        } else if ($izin->izin == 'Keluar Kantor') {
            $pdf = PDF::loadView('users/izin/form_izin_keluar', $data)->setPaper('A5', 'landscape');
            return $pdf->stream('FORM_PENGAJUAN_IZIN_KELUAR_KANTOR_' . Auth::user()->name . '_' . date('Y-m-d H:i:s') . '.pdf');
        }
    }
}
