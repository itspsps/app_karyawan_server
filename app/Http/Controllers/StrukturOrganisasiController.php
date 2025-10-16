<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StrukturOrganisasiController extends Controller
{
    public function index()
    {
        // syncfusion
        $jabatan = Jabatan::join('divisis', 'divisis.id', '=', 'jabatans.divisi_id')
            ->join('level_jabatans', 'level_jabatans.id', '=', 'jabatans.level_id')
            ->join('departemens', 'departemens.id', '=', 'divisis.dept_id')
            ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
            ->where('jabatans.holding', 'sp')
            ->orderBy('departemens.nama_departemen', 'ASC')
            ->orderBy('jabatans.nama_jabatan', 'ASC')
            ->orderBy('bagians.nama_bagian', 'ASC')
            ->select('bagians.nama_bagian as nama_bagian', 'divisis.nama_divisi as nama_divisi', 'jabatans.divisi_id', 'jabatans.bagian_id', 'jabatans.id as id', 'jabatans.atasan_id', 'jabatans.nama_jabatan as nama_jabatan')
            // ->select('bagians.nama_bagian', 'jabatans.id', 'jabatans.atasan_id', 'jabatans.nama_jabatan', 'divisis.nama_divisi', 'level_jabatans.level_jabatan')
            // ->take('10')
            ->get();
        // dd($jabatan);
        if (count($jabatan) == 0) {
            $jabatan_struktur = NULL;
        } else {
            foreach ($jabatan as $jabatan) {
                // $ok = $jabatan->User->toArray();
                $ok = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian_id')
                    ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'ALL SITES (SP)'])
                    ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->where('jabatans.nama_jabatan', $jabatan["nama_jabatan"])
                    ->where('karyawans.status_aktif', 'AKTIF')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                $ok1 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan1_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi1_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept1_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian1_id')
                    ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'ALL SITES (SP)'])
                    ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->where('jabatans.nama_jabatan', $jabatan["nama_jabatan"])
                    ->where('karyawans.status_aktif', 'AKTIF')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                $ok2 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan2_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi2_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept2_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian2_id')
                    ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'ALL SITES (SP)'])
                    ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->where('jabatans.nama_jabatan', $jabatan["nama_jabatan"])
                    ->where('karyawans.status_aktif', 'AKTIF')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                $ok3 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan3_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi3_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept3_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian3_id')
                    ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'ALL SITES (SP)'])
                    ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->where('jabatans.nama_jabatan', $jabatan["nama_jabatan"])
                    ->where('karyawans.status_aktif', 'AKTIF')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                $ok4 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan4_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi4_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept4_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian4_id')
                    ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'ALL SITES (SP)'])
                    ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->where('jabatans.nama_jabatan', $jabatan["nama_jabatan"])
                    ->where('karyawans.status_aktif', 'AKTIF')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                // dd(count($ok));
                if ($ok == []) {
                    $user_name = NULL;
                } else {
                    // dd(json_encode($ok));
                    $user_name = str_replace('[{', '', json_encode($ok));
                    $user_name = str_replace('{', '<li>', $user_name);
                    $user_name = str_replace('"', '', $user_name);
                    $user_name = str_replace('}', '', $user_name);
                    $user_name = str_replace(']', '', $user_name);
                    $user_name = str_replace('name:', ' ', $user_name);
                    $user_name = str_replace(' ', '&nbsp;', $user_name);
                    $user_name = str_replace(',', '</li>', $user_name);
                }

                if ($ok1 == []) {
                    $user_name1 = NULL;
                } else {
                    $user_name1 = str_replace('[{', '', json_encode($ok1));
                    $user_name1 = str_replace('{', '<li>', $user_name1);
                    $user_name1 = str_replace('"', '', $user_name1);
                    $user_name1 = str_replace('}', '', $user_name1);
                    $user_name1 = str_replace(']', '', $user_name1);
                    $user_name1 = str_replace('name:', ' ', $user_name1);
                    $user_name1 = str_replace(' ', '&nbsp;', $user_name1);
                    $user_name1 = str_replace(',', '</li>', $user_name1);
                }
                if ($ok2 == []) {
                    $user_name2 = NULL;
                } else {
                    $user_name2 = str_replace('[{', '', json_encode($ok2));
                    $user_name2 = str_replace('{', '<li>', $user_name2);
                    $user_name2 = str_replace('"', '', $user_name2);
                    $user_name2 = str_replace('}', '', $user_name2);
                    $user_name2 = str_replace(']', '', $user_name2);
                    $user_name2 = str_replace('name:', ' ', $user_name2);
                    $user_name2 = str_replace(' ', '&nbsp;', $user_name2);
                    $user_name2 = str_replace(',', '</li>', $user_name2);
                }
                if ($ok3 == []) {
                    $user_name3 = NULL;
                } else {
                    $user_name3 = str_replace('[{', '', json_encode($ok3));
                    $user_name3 = str_replace('{', '<li>', $user_name3);
                    $user_name3 = str_replace('"', '', $user_name3);
                    $user_name3 = str_replace('}', '', $user_name3);
                    $user_name3 = str_replace(']', '', $user_name3);
                    $user_name3 = str_replace('name:', ' ', $user_name3);
                    $user_name3 = str_replace(' ', '&nbsp;', $user_name3);
                    $user_name3 = str_replace(',', '</li>', $user_name3);
                }
                if ($ok4 == []) {
                    $user_name4 = NULL;
                } else {
                    $user_name4 = str_replace('[{', '', json_encode($ok4));
                    $user_name4 = str_replace('{', '<li>', $user_name4);
                    $user_name4 = str_replace('"', '', $user_name4);
                    $user_name4 = str_replace('}', '', $user_name4);
                    $user_name4 = str_replace(']', '', $user_name4);
                    $user_name4 = str_replace('name:', ' ', $user_name4);
                    $user_name4 = str_replace(' ', '&nbsp;', $user_name4);
                    $user_name4 = str_replace(',', '</li>', $user_name4);
                }
                // $role = '<a class="btn btn-sm btn-primary"> oke</a>';
                $count_username = (count($ok) + count($ok1) + count($ok2) + count($ok3) + count($ok4)) . '&nbsp;Karyawan';

                $foto = '<img width=30 height=30 style="border-radius: 50%;" align=center margin_bottom=4 margin_top=4 src=https://hrd.sumberpangan.store:4430/public/admin/assets/img/avatars/1.png><br>';
                $jabatan_struktur[] = array('x' => $jabatan['nama_jabatan'] . ' <br>(' . $jabatan['nama_bagian'] . ')', 'id' => str_replace("-", "", $jabatan['id']), 'parent' => str_replace("-", "", $jabatan['atasan_id']), 'user' => $user_name  . $user_name1 . $user_name2 . $user_name3 . $user_name4, 'attributes' => array('role' => $count_username, 'photo' => $foto));
                // $jabatan_struktur[] = array('x' => $jabatan['nama_jabatan'] . ' (' . $jabatan['nama_bagian'] . ')', 'id' => str_replace("-", "", $jabatan['id']), 'parent' => str_replace("-", "", $jabatan['atasan_id']), 'attributes' => array('role' => $user_name  . $user_name1 . $user_name2 . $user_name3 . $user_name4, 'photo' => $foto));
            }
        }
        // dd($count_username);

        // dd($jabatan_struktur);
        // dd($user_sps);
        $jabatan1 = Jabatan::join('divisis', 'divisis.id', '=', 'jabatans.divisi_id')
            ->join('level_jabatans', 'level_jabatans.id', '=', 'jabatans.level_id')
            ->join('departemens', 'departemens.id', '=', 'divisis.dept_id')
            ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
            ->where('jabatans.holding', 'sps')
            ->orderBy('departemens.nama_departemen', 'ASC')
            ->orderBy('jabatans.nama_jabatan', 'ASC')
            ->orderBy('bagians.nama_bagian', 'ASC')
            ->select('bagians.nama_bagian as nama_bagian', 'divisis.nama_divisi as nama_divisi', 'jabatans.divisi_id', 'jabatans.bagian_id', 'jabatans.id as id', 'jabatans.atasan_id', 'jabatans.nama_jabatan as nama_jabatan')
            // ->take('10')
            ->get();
        // dd($jabatan);
        // dd($jabatan1);
        if (count($jabatan1) == 0) {
            $jabatan_struktur1 = NULL;
        } else {
            foreach ($jabatan1 as $jabatan) {
                $ok = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian_id')
                    ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'ALL SITES (SPS)'])
                    ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->where('jabatans.nama_jabatan', $jabatan["nama_jabatan"])
                    ->where('karyawans.status_aktif', 'AKTIF')
                    // ->orWhere('karyawans.site_job', 'CV. SUMBER PANGAN - KEDIRI')
                    // ->take('5')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                // dd($ok);
                $ok1 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan1_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi1_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept1_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian1_id')
                    ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'ALL SITES (SPS)'])
                    ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->where('jabatans.nama_jabatan', $jabatan["nama_jabatan"])
                    ->where('karyawans.status_aktif', 'AKTIF')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                $ok2 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan2_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi2_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept2_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian2_id')
                    ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'ALL SITES (SPS)'])
                    ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->where('jabatans.nama_jabatan', $jabatan["nama_jabatan"])
                    ->where('karyawans.status_aktif', 'AKTIF')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                $ok3 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan3_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi3_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept3_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian3_id')
                    ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'ALL SITES (SPS)'])
                    ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->where('jabatans.nama_jabatan', $jabatan["nama_jabatan"])
                    ->where('karyawans.status_aktif', 'AKTIF')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                $ok4 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan4_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi4_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept4_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian4_id')
                    ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'ALL SITES (SPS)'])
                    ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->where('jabatans.nama_jabatan', $jabatan["nama_jabatan"])
                    ->where('karyawans.status_aktif', 'AKTIF')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();

                if ($ok == []) {
                    $user_name = NULL;
                } else {
                    // dd(json_encode($ok));
                    $user_name = str_replace('[{', '', json_encode($ok));
                    $user_name = str_replace('{', '<li>', $user_name);
                    $user_name = str_replace('"', '', $user_name);
                    $user_name = str_replace('}', '', $user_name);
                    $user_name = str_replace(']', '', $user_name);
                    $user_name = str_replace('name:', ' ', $user_name);
                    $user_name = str_replace(' ', '&nbsp;', $user_name);
                    $user_name = str_replace(',', '</li>', $user_name);
                }

                if ($ok1 == []) {
                    $user_name1 = NULL;
                } else {
                    $user_name1 = str_replace('[{', '', json_encode($ok1));
                    $user_name1 = str_replace('{', '<li>', $user_name1);
                    $user_name1 = str_replace('"', '', $user_name1);
                    $user_name1 = str_replace('}', '', $user_name1);
                    $user_name1 = str_replace(']', '', $user_name1);
                    $user_name1 = str_replace('name:', ' ', $user_name1);
                    $user_name1 = str_replace(' ', '&nbsp;', $user_name1);
                    $user_name1 = str_replace(',', '</li>', $user_name1);
                }
                if ($ok2 == []) {
                    $user_name2 = NULL;
                } else {
                    $user_name2 = str_replace('[{', '', json_encode($ok2));
                    $user_name2 = str_replace('{', '<li>', $user_name2);
                    $user_name2 = str_replace('"', '', $user_name2);
                    $user_name2 = str_replace('}', '', $user_name2);
                    $user_name2 = str_replace(']', '', $user_name2);
                    $user_name2 = str_replace('name:', ' ', $user_name2);
                    $user_name2 = str_replace(' ', '&nbsp;', $user_name2);
                    $user_name2 = str_replace(',', '</li>', $user_name2);
                }
                if ($ok3 == []) {
                    $user_name3 = NULL;
                } else {
                    $user_name3 = str_replace('[{', '', json_encode($ok3));
                    $user_name3 = str_replace('{', '<li>', $user_name3);
                    $user_name3 = str_replace('"', '', $user_name3);
                    $user_name3 = str_replace('}', '', $user_name3);
                    $user_name3 = str_replace(']', '', $user_name3);
                    $user_name3 = str_replace('name:', ' ', $user_name3);
                    $user_name3 = str_replace(' ', '&nbsp;', $user_name3);
                    $user_name3 = str_replace(',', '</li>', $user_name3);
                }
                if ($ok4 == []) {
                    $user_name4 = NULL;
                } else {
                    $user_name4 = str_replace('[{', '', json_encode($ok4));
                    $user_name4 = str_replace('{', '<li>', $user_name4);
                    $user_name4 = str_replace('"', '', $user_name4);
                    $user_name4 = str_replace('}', '', $user_name4);
                    $user_name4 = str_replace(']', '', $user_name4);
                    $user_name4 = str_replace('name:', ' ', $user_name4);
                    $user_name4 = str_replace(' ', '&nbsp;', $user_name4);
                    $user_name4 = str_replace(',', '</li>', $user_name4);
                }
                $count_username = (count($ok) + count($ok1) + count($ok2) + count($ok3) + count($ok4)) . '&nbsp;Karyawan';

                $foto = '<img width=30 height=30 style="border-radius: 50%;" align=center margin_bottom=4 margin_top=4 src=https://hrd.sumberpangan.store:4430/public/admin/assets/img/avatars/1.png><br>';
                $jabatan_struktur1[] = array('x' => $jabatan['nama_jabatan'] . ' <br>(' . $jabatan['nama_bagian'] . ')', 'id' => str_replace("-", "", $jabatan['id']), 'parent' => str_replace("-", "", $jabatan['atasan_id']), 'user' => $user_name  . $user_name1 . $user_name2 . $user_name3 . $user_name4, 'attributes' => array('role' => $count_username, 'photo' => $foto));
            }
        }
        // dd($jabatan_struktur1);
        // dd($jabatan_struktur);
        $jabatan2 = Jabatan::join('divisis', 'divisis.id', '=', 'jabatans.divisi_id')
            ->join('level_jabatans', 'level_jabatans.id', '=', 'jabatans.level_id')
            ->join('departemens', 'departemens.id', '=', 'divisis.dept_id')
            ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
            ->where('jabatans.holding', 'sip')
            ->orderBy('departemens.nama_departemen', 'ASC')
            ->orderBy('jabatans.nama_jabatan', 'ASC')
            ->orderBy('bagians.nama_bagian', 'ASC')
            ->select('bagians.nama_bagian as nama_bagian', 'divisis.nama_divisi as nama_divisi', 'jabatans.divisi_id', 'jabatans.bagian_id', 'jabatans.id as id', 'jabatans.atasan_id', 'jabatans.nama_jabatan as nama_jabatan')
            // ->take('10')
            ->get();
        // dd($jabatan);
        // dd($jabatan);
        if (count($jabatan1) == 0) {
            $jabatan_struktur2 = NULL;
        } else {
            foreach ($jabatan2 as $jabatan) {
                $ok = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian_id')
                    ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'CV. SURYA INTI PANGAN - MAKASAR', 'ALL SITES (SIP)'])
                    ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->where('jabatans.nama_jabatan', $jabatan["nama_jabatan"])
                    ->where('karyawans.status_aktif', 'AKTIF')
                    // ->orWhere('karyawans.site_job', 'CV. SUMBER PANGAN - KEDIRI')
                    // ->take('5')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                // dd($ok);
                $ok1 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan1_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi1_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept1_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian1_id')
                    ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'CV. SURYA INTI PANGAN - MAKASAR', 'ALL SITES (SIP)'])
                    ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->where('jabatans.nama_jabatan', $jabatan["nama_jabatan"])
                    ->where('karyawans.status_aktif', 'AKTIF')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                $ok2 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan2_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi2_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept2_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian2_id')
                    ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'CV. SURYA INTI PANGAN - MAKASAR', 'ALL SITES (SIP)'])
                    ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->where('jabatans.nama_jabatan', $jabatan["nama_jabatan"])
                    ->where('karyawans.status_aktif', 'AKTIF')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                $ok3 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan3_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi3_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept3_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian3_id')
                    ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'CV. SURYA INTI PANGAN - MAKASAR', 'ALL SITES (SIP)'])
                    ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->where('jabatans.nama_jabatan', $jabatan["nama_jabatan"])
                    ->where('karyawans.status_aktif', 'AKTIF')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                $ok4 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan4_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi4_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept4_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian4_id')
                    ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'CV. SURYA INTI PANGAN - MAKASAR', 'ALL SITES (SIP)'])
                    ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->where('jabatans.nama_jabatan', $jabatan["nama_jabatan"])
                    ->where('karyawans.status_aktif', 'AKTIF')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();

                if ($ok == []) {
                    $user_name = NULL;
                } else {
                    // dd(json_encode($ok));
                    $user_name = str_replace('[{', '', json_encode($ok));
                    $user_name = str_replace('{', '<li>', $user_name);
                    $user_name = str_replace('"', '', $user_name);
                    $user_name = str_replace('}', '', $user_name);
                    $user_name = str_replace(']', '', $user_name);
                    $user_name = str_replace('name:', ' ', $user_name);
                    $user_name = str_replace(' ', '&nbsp;', $user_name);
                    $user_name = str_replace(',', '</li>', $user_name);
                }

                if ($ok1 == []) {
                    $user_name1 = NULL;
                } else {
                    $user_name1 = str_replace('[{', '', json_encode($ok1));
                    $user_name1 = str_replace('{', '<li>', $user_name1);
                    $user_name1 = str_replace('"', '', $user_name1);
                    $user_name1 = str_replace('}', '', $user_name1);
                    $user_name1 = str_replace(']', '', $user_name1);
                    $user_name1 = str_replace('name:', ' ', $user_name1);
                    $user_name1 = str_replace(' ', '&nbsp;', $user_name1);
                    $user_name1 = str_replace(',', '</li>', $user_name1);
                }
                if ($ok2 == []) {
                    $user_name2 = NULL;
                } else {
                    $user_name2 = str_replace('[{', '', json_encode($ok2));
                    $user_name2 = str_replace('{', '<li>', $user_name2);
                    $user_name2 = str_replace('"', '', $user_name2);
                    $user_name2 = str_replace('}', '', $user_name2);
                    $user_name2 = str_replace(']', '', $user_name2);
                    $user_name2 = str_replace('name:', ' ', $user_name2);
                    $user_name2 = str_replace(' ', '&nbsp;', $user_name2);
                    $user_name2 = str_replace(',', '</li>', $user_name2);
                }
                if ($ok3 == []) {
                    $user_name3 = NULL;
                } else {
                    $user_name3 = str_replace('[{', '', json_encode($ok3));
                    $user_name3 = str_replace('{', '<li>', $user_name3);
                    $user_name3 = str_replace('"', '', $user_name3);
                    $user_name3 = str_replace('}', '', $user_name3);
                    $user_name3 = str_replace(']', '', $user_name3);
                    $user_name3 = str_replace('name:', ' ', $user_name3);
                    $user_name3 = str_replace(' ', '&nbsp;', $user_name3);
                    $user_name3 = str_replace(',', '</li>', $user_name3);
                }
                if ($ok4 == []) {
                    $user_name4 = NULL;
                } else {
                    $user_name4 = str_replace('[{', '', json_encode($ok4));
                    $user_name4 = str_replace('{', '<li>', $user_name4);
                    $user_name4 = str_replace('"', '', $user_name4);
                    $user_name4 = str_replace('}', '', $user_name4);
                    $user_name4 = str_replace(']', '', $user_name4);
                    $user_name4 = str_replace('name:', ' ', $user_name4);
                    $user_name4 = str_replace(' ', '&nbsp;', $user_name4);
                    $user_name4 = str_replace(',', '</li>', $user_name4);
                }
                $count_username = (count($ok) + count($ok1) + count($ok2) + count($ok3) + count($ok4)) . '&nbsp;Karyawan';

                $foto = '<img width=30 height=30 style="border-radius: 50%;" align=center margin_bottom=4 margin_top=4 src=https://hrd.sumberpangan.store:4430/public/admin/assets/img/avatars/1.png><br>';
                $jabatan_struktur2[] = array('x' => $jabatan['nama_jabatan'] . ' <br>(' . $jabatan['nama_bagian'] . ')', 'id' => str_replace("-", "", $jabatan['id']), 'parent' => str_replace("-", "", $jabatan['atasan_id']), 'user' => $user_name  . $user_name1 . $user_name2 . $user_name3 . $user_name4, 'attributes' => array('role' => $count_username, 'photo' => $foto));
            }
        }
        return view('admin.struktur_organisasi.index', [
            'holding' => $holding,
            'user' => $jabatan_struktur,
            'user1' => $jabatan_struktur1,
            'user2' => $jabatan_struktur2,
            'user_node' => $jabatan_struktur
        ]);
    }
    public function index1()
    {
        $holding = request()->segment(count(request()->segments()));
        if ($holding == 'sp') {
            $kontrak = 'SP';
            // syncfusion
            $user = Karyawan::join('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                ->join('level_jabatans', 'level_jabatans.id', '=', 'jabatans.level_id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->join('bagians', 'bagians.id', '=', 'karyawans.bagian_id')
                ->where('karyawans.kategori', 'Karyawan Bulanan')
                ->whereNotNull('karyawans.bagian_id')
                ->where('karyawans.site_job', 'CV. SUMBER PANGAN - KEDIRI')
                // ->where('divisis.nama_divisi', 'PRODUCTION')
                ->orWhere('karyawans.site_job', 'CV. SUMBER PANGAN - TUBAN')
                ->orWhere('karyawans.site_job', 'ALL SITES (SP, SPS, SIP)')
                ->orWhere('karyawans.site_job', 'DEPO SPS SIDOARJO')
                // ->where('penempatan_kerja', 'ALL SITES (SP, SPS, SIP)')
                // ->orWhere('karyawans.site_job', 'ALL SITES (SP)')
                // ->where('departemens.nama_departemen', 'PENGEMBANGAN TEKNOLOGI & SISTEM INFORMASI')
                // ->where('karyawans.is_admin', 'user')
                ->where('karyawans.status_aktif', 'AKTIF')
                ->orderBy('level_jabatans.level_jabatan', 'ASC')
                ->select('karyawans.*', 'bagians.nama_bagian', 'jabatans.id as id_jabatan', 'jabatans.nama_jabatan', 'divisis.nama_divisi', 'level_jabatans.level_jabatan')
                // ->select('karyawans.id', 'karyawans.name')
                // ->limit(1)
                ->get();
            // dd(json_encode($user));
            foreach ($user as $user) {
                $user_struktur[] = array('x' => $user['name'], 'id' => str_replace("-", "", $user['id']), 'parent' => str_replace("-", "", $user['atasan_1']), 'attributes' => array('role' => $user['nama_jabatan'] . ' (' . $user['nama_bagian'] . ')', 'photo' => ''));
            }
        } else if ($holding == 'sps') {
            $user = Karyawan::join('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                ->join('level_jabatans', 'level_jabatans.id', '=', 'jabatans.level_id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->join('bagians', 'bagians.id', '=', 'karyawans.bagian_id')
                ->where('karyawans.kategori', 'Karyawan Bulanan')
                ->whereNotNull('karyawans.bagian_id')
                ->where('karyawans.site_job', 'PT. SURYA PANGAN SEMESTA - KEDIRI')
                ->orWhere('karyawans.site_job', 'PT. SURYA PANGAN SEMESTA - NGAWI')
                ->orWhere('karyawans.site_job', 'PT. SURYA PANGAN SEMESTA - SUBANG')
                ->orWhere('karyawans.site_job', 'ALL SITES (SP, SPS, SIP)')
                ->orWhere('karyawans.site_job', 'DEPO SPS SIDOARJO')
                // ->where('penempatan_kerja', 'ALL SITES (SP, SPS, SIP)')
                // ->orWhere('karyawans.site_job', 'ALL SITES (SP)')
                // ->where('departemens.nama_departemen', 'PENGEMBANGAN TEKNOLOGI & SISTEM INFORMASI')
                // ->where('karyawans.is_admin', 'user')
                ->where('karyawans.status_aktif', 'AKTIF')
                ->orderBy('level_jabatans.level_jabatan', 'ASC')
                ->select('karyawans.*', 'bagians.nama_bagian', 'jabatans.id as id_jabatan', 'jabatans.nama_jabatan', 'divisis.nama_divisi', 'level_jabatans.level_jabatan')
                // ->select('karyawans.id', 'karyawans.name')
                // ->limit(1)
                ->get();
            // dd(json_encode($user));
            foreach ($user as $user) {
                $user_struktur1[] = array('x' => $user['name'], 'id' => str_replace("-", "", $user['id']), 'parent' => str_replace("-", "", $user['atasan_1']), 'attributes' => array('role' => $user['nama_jabatan'] . ' (' . $user['nama_bagian'] . ')', 'photo' => ''));
            }
        } else {
            $user = Karyawan::join('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                ->join('level_jabatans', 'level_jabatans.id', '=', 'jabatans.level_id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->join('bagians', 'bagians.id', '=', 'karyawans.bagian_id')
                ->where('karyawans.kategori', 'Karyawan Bulanan')
                ->whereNotNull('karyawans.bagian_id')
                ->where('karyawans.site_job', 'CV. SURYA INTI PANGAN - MAKASAR')
                ->orWhere('karyawans.site_job', 'ALL SITES (SP, SPS, SIP)')
                // ->where('penempatan_kerja', 'ALL SITES (SP, SPS, SIP)')
                // ->orWhere('karyawans.site_job', 'ALL SITES (SP)')
                // ->where('departemens.nama_departemen', 'PENGEMBANGAN TEKNOLOGI & SISTEM INFORMASI')
                // ->where('karyawans.is_admin', 'user')
                ->where('karyawans.status_aktif', 'AKTIF')
                ->orderBy('level_jabatans.level_jabatan', 'ASC')
                ->select('karyawans.*', 'bagians.nama_bagian', 'jabatans.id as id_jabatan', 'jabatans.nama_jabatan', 'divisis.nama_divisi', 'level_jabatans.level_jabatan')
                // ->select('karyawans.id', 'karyawans.name')
                // ->limit(1)
                ->get();
            // dd(json_encode($user));
            foreach ($user as $user) {
                $user_struktur2[] = array('x' => $user['name'], 'id' => str_replace("-", "", $user['id']), 'parent' => str_replace("-", "", $user['atasan_1']), 'attributes' => array('role' => $user['nama_jabatan'] . ' (' . $user['nama_bagian'] . ')', 'photo' => ''));
            }
        }
        return view('admin.struktur_organisasi.index', [
            'holding' => $holding,
            'user' => $user_struktur,
            'user1' => $user_struktur1,
            'user2' => $user_struktur2,
            'user_node' => $user_struktur
        ]);
    }
}
