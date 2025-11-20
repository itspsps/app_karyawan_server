<?php

namespace App\Http\Controllers;

use App\Models\KaryawanRiwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;


class KaryawanRiwayatController extends Controller
{
    // Riwayat
    public function button_riwayat($id)
    {
        $data_riwayat = KaryawanRiwayat::select()->where('id_karyawan', $id)->count();
        return response()->json([
            'code' => 200,
            // 'data' => $get_data,
            'data_riwayat' => $data_riwayat,
            // 'message' => 'Data Berhasil Diupdate'
        ]);
    }
    public function riwayat_datatable($id)
    {
        $table = KaryawanRiwayat::where('id_karyawan', $id)->orderBy('created_at', 'ASC')->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_perusahaan', function ($row) {
                    $nama_perusahaan = $row->nama_perusahaan;
                    return $nama_perusahaan;
                })
                ->addColumn('alamat_perusahaan', function ($row) {
                    $alamat_perusahaan = $row->alamat_perusahaan;
                    return $alamat_perusahaan;
                })
                ->addColumn('posisi', function ($row) {
                    $posisi = $row->posisi;
                    return $posisi;
                })
                ->addColumn('gaji', function ($row) {
                    $gaji = rupiah($row->gaji);
                    return $gaji;
                })
                ->addColumn('tanggal_masuk', function ($row) {
                    $tanggal_masuk = $row->tanggal_masuk;
                    return $tanggal_masuk;
                })
                ->addColumn('tanggal_keluar', function ($row) {
                    $tanggal_keluar = $row->tanggal_keluar;
                    return $tanggal_keluar;
                })
                ->addColumn('alasan_keluar', function ($row) {
                    $alasan_keluar = $row->alasan_keluar;
                    return $alasan_keluar;
                })
                ->addColumn('surat_keterangan', function ($row) {
                    $file = asset('storage/surat_keterangan/' . $row->surat_keterangan);
                    $surat_keterangan = ' <a type="button" href="' . $file . '" target="_blank"
                            class="btn btn-sm btn-primary waves-effect waves-light"><i class="mdi mdi-eye"></i>
                            &nbsp;Lihat
                        </a>';
                    // $surat_keterangan =  "<embed type='application/pdf' src='$file' width='600' height='400'>";
                    if ($row->surat_keterangan != NULL) {
                        return $surat_keterangan;
                    }
                })
                ->addColumn('nomor_referensi', function ($row) {
                    $nomor_referensi = $row->nomor_referensi;
                    return $nomor_referensi;
                })
                ->addColumn('jabatan_referensi', function ($row) {
                    $jabatan_referensi = $row->jabatan_referensi;
                    return $jabatan_referensi;
                })
                ->addColumn('option', function ($row) {
                    $btn =

                        '<button type="button" id="btn_edit_riwayat"
                            data-id_riwayat="' . $row->id_riwayat . '"
                            data-nama_perusahaan="' . $row->nama_perusahaan . '"
                            data-alamat_perusahaan="' . $row->alamat_perusahaan . '"
                            data-posisi="' . $row->posisi . '"
                            data-gaji="' . $row->gaji . '"
                            data-tanggal_masuk="' . $row->tanggal_masuk . '"
                            data-tanggal_keluar="' . $row->tanggal_keluar . '"
                            data-alasan_keluar="' . $row->alasan_keluar . '"
                            data-old_file="' . $row->surat_keterangan . '"
                            data-nomor_referensi="' . $row->nomor_referensi . '"
                            data-jabatan_referensi="' . $row->jabatan_referensi . '"
                            class="btn btn-sm btn-primary">
                            <i class="mdi mdi-pencil"></i>
                        </button>
                        <button type="button" id="btn_delete_riwayat"
                            data-id_riwayat="' . $row->id_riwayat . '"
                            data-surat_keterangan="' . $row->surat_keterangan . '"
                            class="btn btn-sm btn-danger">
                            <i class="mdi mdi-delete"></i>
                        </button>
                        ';
                    return $btn;
                })
                ->rawColumns(['nama_perusahaan', 'alamat_perusahaan', 'posisi', 'gaji', 'tanggal_masuk', 'tanggal_keluar', 'alasan_keluar', 'surat_keterangan', 'nomor_referensi', 'jabatan_referensi', 'option'])
                ->make(true);
        }
    }
    public function riwayat_post(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_karyawan' => 'required',
                'nama_perusahaan' => 'required',
                'alamat_perusahaan' => 'required',
                'alasan_keluar' => 'required',
                'posisi' => 'required',
                'gaji' => 'required',
                'tanggal_masuk' => 'required',
                'surat_keterangan' => 'mimes:pdf|max:5000',
                // 'nomor_referensi' => 'numeric',
            ],
            [
                'required' => ':attribute Tidak boleh kosong!',
                'mimes' => ':attribute Harus berupa PDF!',
                'max' => ':attribute Maksimal 5 MB!',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }
        try {
            // Memasukkan file
            if ($request->surat_keterangan != null) {
                $file = $request->file('surat_keterangan')->store('surat_keterangan');
                $file_save = basename($file);
            }
            // dd($request->file('surat_keterangan'));
            // $original_name = pathinfo($request->surat_keterangan->getClientOriginalName(), PATHINFO_FILENAME);
            // $extension = '.' . $request->surat_keterangan->extension();

            // $sk_name = time() . '_' . $original_name . $extension;
            //memasukkan data
            $riwayat = new KaryawanRiwayat();
            $riwayat->id_riwayat = Uuid::uuid4();
            $riwayat->id_karyawan = $request->id_karyawan;
            $riwayat->nama_perusahaan = $request->nama_perusahaan;
            $riwayat->alamat_perusahaan = $request->alamat_perusahaan;
            $riwayat->posisi = $request->posisi;
            $riwayat->gaji = $request->gaji;
            $riwayat->tanggal_masuk = $request->tanggal_masuk;
            if ($request->surat_keterangan != null) {
                $riwayat->surat_keterangan = $file_save;
            }
            $riwayat->tanggal_keluar = $request->tanggal_keluar;
            $riwayat->alasan_keluar = $request->alasan_keluar;
            $riwayat->nomor_referensi = $request->nomor_referensi;
            $riwayat->jabatan_referensi = $request->jabatan_referensi;
            $riwayat->created_at = date('Y-m-d H:i:s');
            $riwayat->save();
            // $first_image = Carousel::limit('1', 'asc')->first();
            // $get_data = Carousel::limit('1', 'asc')->get();
            $data_riwayat = KaryawanRiwayat::select()->where('id_karyawan', $request->id_karyawan)->count();
            return response()->json([
                'code' => 200,
                'data_riwayat' => $data_riwayat,
                // 'data2' => $get_data2,
                'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function riwayat_update(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'nama_perusahaan' => 'required',
                'alamat_perusahaan' => 'required',
                'posisi' => 'required',
                'alasan_keluar' => 'required',
                'gaji' => 'required',
                'tanggal_masuk' => 'required',
                'surat_keterangan' => 'mimes:pdf|max:5000',
                // 'nomor_referensi' => 'numeric',
            ],
            [
                'required' => ':attribute Tidak boleh kosong!',
                'mimes' => ':attribute Harus berupa PDF!',
                'max' => ':attribute Maksimal 5 MB!',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }
        try {
            if ($request->surat_keterangan != null) {
                if ($request->old_file != null) {
                    if (Storage::disk('surat_keterangan')->exists($request->old_file)) {
                        Storage::disk('surat_keterangan')->delete($request->old_file);
                    }
                }
                $file = $request->file('surat_keterangan')->store('surat_keterangan');
                $surat_keterangan = basename($file);
            } else {
                $surat_keterangan = $request->old_file;
            }
            // $file->move($destinationPath, $filename);
            // if ($request->surat_keterangan != NULL || $request->surat_keterangan != '') {
            //     if (File::exist($oldImagePath)) {
            //         File::delete($oldImagePath);
            //     }
            // }

            $riwayat = KaryawanRiwayat::where('id_riwayat', $request->id_riwayat)->first();
            // dd($riwayat);
            $riwayat->nama_perusahaan = $request->nama_perusahaan;
            $riwayat->alamat_perusahaan = $request->alamat_perusahaan;
            $riwayat->posisi = $request->posisi;
            $riwayat->gaji = $request->gaji;
            $riwayat->tanggal_masuk = $request->tanggal_masuk;
            $riwayat->tanggal_keluar = $request->tanggal_keluar;
            $riwayat->alasan_keluar = $request->alasan_keluar;
            $riwayat->nomor_referensi = $request->nomor_referensi;
            $riwayat->surat_keterangan = $surat_keterangan;
            $riwayat->jabatan_referensi = $request->jabatan_referensi;
            $riwayat->updated_at = date('Y-m-d H:i:s');
            $riwayat->save();
            // $first_image = Carousel::limit('1', 'asc')->first();
            // $get_data = Carousel::limit('1', 'asc')->get();
            // $get_data2 = Carousel::select()->where('id_carousel', '!=', $first_image->id_carousel)->orderBy('id_carousel', 'DESC')->get();
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data2' => $get_data2,
                'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function delete_riwayat(Request $request, $id)
    {
        // dd($request->all());
        try {
            // $file = $request->file('surat_keterangan')->delete('/storage/surat_keterangan/');
            if ($request->surat_keterangan != NULL) {

                Storage::disk('surat_keterangan')->delete($request->surat_keterangan);
            }
            // dd($request->id_riwayat);
            KaryawanRiwayat::where('id_riwayat', $request->id_riwayat)->delete();
            // $first_image = Carousel::limit('1', 'asc')->first();
            // $get_data = Carousel::limit('1', 'asc')->get();
            $data_riwayat = KaryawanRiwayat::select()->where('id_karyawan', $id)->count();
            return response()->json([
                'code' => 200,
                'data_riwayat' => $data_riwayat,
                // 'data2' => $get_data2,
                'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
