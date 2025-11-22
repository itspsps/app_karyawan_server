<?php

namespace App\Http\Controllers;

use App\Models\KaryawanKesehatan;
use App\Models\KaryawanKesehatanKecelakaan;
use App\Models\KaryawanKesehatanPengobatan;
use App\Models\KaryawanKesehatanRS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;


class KaryawanKesehatanController extends Controller
{
    //
    public function kesehatan_post(Request $request)
    {
        // dd($request->all());
        if ($request->alergi == '1') {
            $sebutkan_alergi = 'required';
        } else {
            $sebutkan_alergi = 'nullable';
        }
        if ($request->phobia == '1') {
            $sebutkan_phobia = 'required';
        } else {
            $sebutkan_phobia = 'nullable';
        }
        if ($request->keterbatasan_fisik == '1') {
            $sebutkan_keterbatasan_fisik = 'required';
        } else {
            $sebutkan_keterbatasan_fisik = 'nullable';
        }
        if ($request->pemeriksaan_kerja_sebelumnya == '1') {
            $pemeriksaan_sebelumnya_hasil = 'required';
        } else {
            $pemeriksaan_sebelumnya_hasil = 'nullable';
        }
        if ($request->gangguan_lainnya == null) {
            $sebutkan_gangguan = 'nullable';
        } else {
            $sebutkan_gangguan = 'required';
        }
        if ($request->vaksin_lainnya == null) {
            $sebutkan_vaksin_lainnya = 'nullable';
        } else {
            $sebutkan_vaksin_lainnya = 'required';
        }
        $validator = Validator::make($request->all(), [
            'id_karyawan' => 'required',
            'sebutkan_alergi' => $sebutkan_alergi,
            'sebutkan_phobia' => $sebutkan_phobia,
            'sebutkan_keterbatasan_fisik' => $sebutkan_keterbatasan_fisik,
            'pemeriksaan_sebelumnya_hasil' => $pemeriksaan_sebelumnya_hasil,
            'sebutkan_gangguan' => $sebutkan_gangguan,
            'sebutkan_vaksin_lainnya' => $sebutkan_vaksin_lainnya,
            'persetujuan_kesehatan' => 'required',
        ], [
            'required' => ':attribute tidak boleh kosong',
            'numeric' => ':attribute harus berupa angka',
            'max' => ':attribute terlalu banyak',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }
        try {
            // dd($request->all());
            //memasukkan data
            $kesehatan = KaryawanKesehatan::where('id_karyawan', $request->id_karyawan)->first();
            $kesehatan->id_karyawan = $request->id_karyawan;
            $kesehatan->perokok = $request->perokok;
            $kesehatan->alkohol = $request->alkohol;
            $kesehatan->alergi = $request->alergi;
            $kesehatan->sebutkan_alergi = $request->sebutkan_alergi;
            $kesehatan->phobia = $request->phobia;
            $kesehatan->sebutkan_phobia = $request->sebutkan_phobia;
            $kesehatan->keterbatasan_fisik = $request->keterbatasan_fisik;
            $kesehatan->sebutkan_keterbatasan_fisik = $request->sebutkan_keterbatasan_fisik;
            $kesehatan->pengobatan_rutin = $request->pengobatan_rutin;
            $kesehatan->asma = $request->asma;
            $kesehatan->diabetes = $request->diabetes;
            $kesehatan->hipertensi = $request->hipertensi;
            $kesehatan->jantung = $request->jantung;
            $kesehatan->tbc = $request->tbc;
            $kesehatan->hepatitis = $request->hepatitis;
            $kesehatan->epilepsi = $request->epilepsi;
            $kesehatan->gangguan_mental = $request->gangguan_mental;
            $kesehatan->gangguan_pengelihatan = $request->gangguan_pengelihatan;
            $kesehatan->gangguan_lainnya = $request->gangguan_lainnya;
            $kesehatan->sebutkan_gangguan = $request->sebutkan_gangguan;
            $kesehatan->pernah_dirawat_rs = $request->pernah_dirawat_rs;
            $kesehatan->kecelakaan_serius = $request->kecelakaan_serius;
            $kesehatan->keterbatasan_fisik = $request->keterbatasan_fisik;
            $kesehatan->mampu_shift = $request->mampu_shift;
            $kesehatan->pemeriksaan_kerja_sebelumnya = $request->pemeriksaan_kerja_sebelumnya;
            $kesehatan->pemeriksaan_sebelumnya_hasil = $request->pemeriksaan_sebelumnya_hasil;
            $kesehatan->covid = $request->covid;
            $kesehatan->tetanus = $request->tetanus;
            $kesehatan->vaksin_lainnya = $request->vaksin_lainnya;
            $kesehatan->sebutkan_vaksin_lainnya = $request->sebutkan_vaksin_lainnya;
            $kesehatan->persetujuan_kesehatan = $request->persetujuan_kesehatan;
            $kesehatan->updated_at = date('Y-m-d H:i:s');
            $kesehatan->save();
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data_kesehatan' => $data_kesehatan,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    // pengobatan
    public function pengobatan_post(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'jenis_obat' => 'required',
            'alasan_obat' => 'required',
        ], [
            'required' => ':attribute tidak boleh kosong',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }
        try {
            //memasukkan data
            $pengobatan = new KaryawanKesehatanPengobatan();
            $pengobatan->id_pengobatan = Uuid::uuid4();
            $pengobatan->id_karyawan = $request->id_karyawan;
            $pengobatan->jenis_obat = $request->jenis_obat;
            $pengobatan->alasan_obat = $request->alasan_obat;
            $pengobatan->created_at = date('Y-m-d H:i:s');
            $pengobatan->save();
            $data_pengobatan = KaryawanKesehatanPengobatan::select()->where('id_karyawan', $request->id_karyawan)->count();
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                'data_pengobatan' => $data_pengobatan,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function dt_pengobatan($id)
    {
        $table = KaryawanKesehatanPengobatan::where('id_karyawan', $id)->orderBy('created_at', 'ASC')->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('jenis_obat', function ($row) {
                    $jenis_obat = $row->jenis_obat;
                    return $jenis_obat;
                })
                ->addColumn('alasan_obat', function ($row) {
                    $alasan_obat = $row->alasan_obat;
                    return $alasan_obat;
                })
                ->addColumn('option', function ($row) {
                    $btn =
                        '<button type="button" id="btn_delete_pengobatan"
                            data-id_pengobatan="' . $row->id_pengobatan . '"
                            class="btn btn-small btn-danger waves-effect waves-light">
                            Hapus
                        </button>
                        ';
                    return $btn;
                })
                ->rawColumns(['jenis_obat', 'alasan_obat', 'option'])
                ->make(true);
        }
    }
    public function pengobatan_delete(Request $request)
    {
        try {
            // dd($request->all());
            KaryawanKesehatanPengobatan::where('id_pengobatan', $request->id_pengobatan)->delete();
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data_pengobatan' => $data_pengobatan,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function pengobatan_reset(Request $request)
    {
        try {
            // dd($request->all());
            KaryawanKesehatanPengobatan::where('id_karyawan', $request->id_pengobatan)->delete();
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data_pengobatan' => $data_pengobatan,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function pengobatan_count($id)
    {
        try {
            // dd($request->all());
            $data_pengobatan = KaryawanKesehatanPengobatan::select()->where('id_karyawan', $id)->count();
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                'data_pengobatan' => $data_pengobatan,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    // end pengobatan
    // rumah sakit
    public function rumah_sakit_post(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'tahun_rs' => 'required|numeric',
            'penyebab_rs' => 'required',
        ], [
            'required' => ':attribute tidak boleh kosong',
            'numeric' => 'Tahun Harus Berupa Angka!',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }
        try {
            //memasukkan data
            $rumah_sakit = new KaryawanKesehatanRS();
            $rumah_sakit->id_kesehatan_rs = Uuid::uuid4();
            $rumah_sakit->id_karyawan = $request->id_karyawan;
            $rumah_sakit->tahun_rs = $request->tahun_rs;
            $rumah_sakit->penyebab_rs = $request->penyebab_rs;
            $rumah_sakit->created_at = date('Y-m-d H:i:s');
            $rumah_sakit->save();
            $data_rumah_sakit = KaryawanKesehatanRS::select()->where('id_karyawan', $request->id_karyawan)->count();
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                'data_rumah_sakit' => $data_rumah_sakit,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function dt_rumah_sakit($id)
    {
        $table = KaryawanKesehatanRS::where('id_karyawan', $id)->orderBy('created_at', 'ASC')->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('tahun_rs', function ($row) {
                    $tahun_rs = $row->tahun_rs;
                    return $tahun_rs;
                })
                ->addColumn('penyebab_rs', function ($row) {
                    $penyebab_rs = $row->penyebab_rs;
                    return $penyebab_rs;
                })
                ->addColumn('option', function ($row) {
                    $btn =
                        '<button type="button" id="btn_delete_rumah_sakit"
                            data-id_kesehatan_rs="' . $row->id_kesehatan_rs . '"
                            class="btn btn-small btn-danger waves-effect waves-light">
                            Hapus
                        </button>
                        ';
                    return $btn;
                })
                ->rawColumns(['tahun_rs', 'penyebab', 'option'])
                ->make(true);
        }
    }
    public function rumah_sakit_delete(Request $request)
    {
        try {
            // dd($request->all());
            KaryawanKesehatanRS::where('id_kesehatan_rs', $request->id_kesehatan_rs)->delete();
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data_rumah_sakit' => $data_rumah_sakit,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function rumah_sakit_reset(Request $request)
    {
        try {
            // dd($request->all());
            KaryawanKesehatanRS::where('id_karyawan', $request->id_karyawan)->delete();
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data_rumah_sakit' => $data_rumah_sakit,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function rumah_sakit_count($id)
    {
        try {
            // dd($request->all());
            $rumah_sakit = KaryawanKesehatanRS::select()->where('id_karyawan', $id)->count();
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                'rumah_sakit'  => $rumah_sakit
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    // end rumah sakit
    // kecelakaan
    public function kecelakaan_post(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'tahun_kecelakaan' => 'required|numeric',
            'penyebab_kecelakaan' => 'required',
        ], [
            'required' => ':attribute tidak boleh kosong',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }
        try {
            //memasukkan data
            $kecelakaan = new KaryawanKesehatanKecelakaan();
            $kecelakaan->id_kecelakaan = Uuid::uuid4();
            $kecelakaan->id_karyawan = $request->id_karyawan;
            $kecelakaan->tahun_kecelakaan = $request->tahun_kecelakaan;
            $kecelakaan->penyebab_kecelakaan = $request->penyebab_kecelakaan;
            $kecelakaan->created_at = date('Y-m-d H:i:s');
            $kecelakaan->save();
            $data_kecelakaan = KaryawanKesehatanKecelakaan::select()->where('id_karyawan', $request->id_karyawan)->count();
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                'data_kecelakaan' => $data_kecelakaan,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function dt_kecelakaan($id)
    {
        $table = KaryawanKesehatanKecelakaan::where('id_karyawan', $id)->orderBy('created_at', 'ASC')->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('tahun_kecelakaan', function ($row) {
                    $tahun_kecelakaan = $row->tahun_kecelakaan;
                    return $tahun_kecelakaan;
                })
                ->addColumn('penyebab_kecelakaan', function ($row) {
                    $penyebab_kecelakaan = $row->penyebab_kecelakaan;
                    return $penyebab_kecelakaan;
                })
                ->addColumn('option', function ($row) {
                    $btn =
                        '<button type="button" id="btn_delete_kecelakaan"
                            data-id_kecelakaan="' . $row->id_kecelakaan . '"
                            class="btn btn-small btn-danger waves-effect waves-light">
                            Hapus
                        </button>
                        ';
                    return $btn;
                })
                ->rawColumns(['tahun_kecelakaan', 'penyebab_kecelakaan', 'option'])
                ->make(true);
        }
    }
    public function kecelakaan_delete(Request $request)
    {
        try {
            // dd($request->all());
            KaryawanKesehatanKecelakaan::where('id_kecelakaan', $request->id_kecelakaan)->delete();
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data_kecelakaan' => $data_kecelakaan,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function kecelakaan_reset(Request $request)
    {
        try {
            // dd($request->all());
            KaryawanKesehatanKecelakaan::where('id_karyawan', $request->id_karyawan)->delete();
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data_kecelakaan' => $data_kecelakaan,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function kecelakaan_count($id)
    {
        try {
            // dd($request->all());
            $kecelakaan = KaryawanKesehatanKecelakaan::select()->where('id_karyawan', $id)->count();
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                'kecelakaan'  => $kecelakaan
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function kesehatan_get($id)
    {
        $kesehatan_get = KaryawanKesehatan::select()->where('id_karyawan', $id)->first();
        return response()->json([
            'code' => 200,
            // 'data' => $get_data,
            'kesehatan_get'  => $kesehatan_get
            // 'message' => 'Data Berhasil Diupdate'
        ]);
    }
    // end kecelakaan

}
