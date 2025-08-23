<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Imports\KaryawanImport;
use App\Imports\UsersImport;
use App\Imports\UserUpdateImport;
use App\Models\ActivityLog;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Lokasi;
use App\Models\User;
use App\Models\UserNonActive;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class UserKaryawanController extends Controller
{
    public function index_users()
    {

        $holding = request()->segment(count(request()->segments()));
        $karyawan = Karyawan::where('karyawans.status_aktif', 'AKTIF')
            ->where('karyawans.kontrak_kerja', $holding)
            ->orderBy('karyawans.name', 'ASC')
            ->get();
        $data_user = User::Join('karyawans', 'users.karyawan_id', 'karyawans.id')->where('kontrak_kerja', $holding)->where('user_aktif', 'AKTIF')->get();
        return view('admin.karyawan.index_users', [
            // return view('karyawan.index', [
            'title' => 'Karyawan',
            "data_departemen" => Departemen::orderBy('nama_departemen', 'ASC')->where('holding', $holding)->get(),
            'holding' => $holding,
            'karyawan' => $karyawan,
            'data_user' => $data_user,
            "data_jabatan" => Jabatan::orderBy('nama_jabatan', 'ASC')->where('holding', $holding)->get(),
            "data_lokasi" => Lokasi::orderBy('lokasi_kantor', 'ASC')->get(),
            "karyawan_laki" => User::Join('karyawans', 'users.karyawan_id', 'karyawans.id')->where('karyawans.gender', "1")->where('kontrak_kerja', $holding)->where('user_aktif', 'AKTIF')->count(),
            "karyawan_perempuan" => User::Join('karyawans', 'users.karyawan_id', 'karyawans.id')->where('karyawans.gender', "2")->where('kontrak_kerja', $holding)->where('user_aktif', 'AKTIF')->count(),
            "karyawan_office" => User::Join('karyawans', 'users.karyawan_id', 'karyawans.id')->where('karyawans.kategori', 'Karyawan Bulanan')->where('kontrak_kerja', $holding)->where('user_aktif', 'AKTIF')->count(),
            "karyawan_shift" => User::Join('karyawans', 'users.karyawan_id', 'karyawans.id')->where('karyawans.kategori', 'Karyawan Harian')->where('kontrak_kerja', $holding)->where('user_aktif', 'AKTIF')->count(),
        ]);
    }


    public function prosesTambahUser(Request $request)
    {
        $rules = [
            'nama_karyawan'         => 'required',
            'username'              => 'required|min:4|unique:users,username|alpha_dash',
            'password'              => 'required|min:6',
            'level'                 => 'required',
        ];


        $customMessages = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute tidak boleh sama',
            'email' => ':attribute format salah',
            'min' => ':attribute Kurang',
            'max' => ':attribute Melampaui Batas Maksimal'
        ];
        $validasi = Validator::make($request->all(), $rules, $customMessages);
        if ($validasi->fails()) {
            $errors = $validasi->errors()->first();
            // dd($errors);
            Alert::error('Gagal', $errors);
            return back()->withInput();
        }
        // dd($request->nama_karyawan);
        $user                = new User();
        $user->karyawan_id   = Karyawan::where('id', $request->nama_karyawan)->value('id');
        $user->username      = $request->username;
        $user->password      = Hash::make($request->password);
        $user->password_show = $request->password;
        $user->is_admin      = $request->level;
        $user->user_aktif    = 'AKTIF';
        $user->save();


        return redirect()->back()->with('success', 'Data Berhasil di Simpan');
    }
    public function datatable_users_bulanan(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $table = User::With(['Karyawan' => function ($query) use ($holding) {

            $query->with('Divisi');
            $query->with('Jabatan');
            $query->where('kontrak_kerja', $holding);
            $query->where('kategori', 'Karyawan Bulanan');
        }])->Join('karyawans', 'karyawans.id', 'users.karyawan_id')
            ->where('karyawans.kategori', 'Karyawan Bulanan')
            ->where('karyawans.kontrak_kerja', $holding)
            ->select('users.*')
            ->orderBy('id', 'DESC')
            // ->limit(2)
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('name', function ($row) use ($holding) {
                    if ($row->Karyawan == null || $row->Karyawan == '') {
                        $name = '-';
                    } else {
                        if ($row->Karyawan->status_aktif == 'NON AKTIF') {
                            $name = '<span style="color:red; text-decoration: line-through;">' . $row->Karyawan->name . '</span>';
                            $name = $name . '<br><span class="badges bg-label-danger">NON AKTIF</span>';
                        } else {
                            $name = '<a href=../karyawan/shift/' . $row->Karyawan->id . '/' . $holding . '>
                            <span class="badge bg-label-success">' . $row->Karyawan->name . '</span>
                            </a>';
                        }
                    }
                    return $name;
                })
                ->addColumn('nomor_identitas_karyawan', function ($row) use ($holding) {
                    if ($row->Karyawan == null || $row->Karyawan == '') {
                        $nomor_identitas_karyawan = '-';
                    } else {
                        if ($row->Karyawan->status_aktif == 'NON AKTIF') {
                            $nomor_identitas_karyawan = '<span style="color:red; text-decoration: line-through;">' . $row->Karyawan->nomor_identitas_karyawan . '</span>';
                            $nomor_identitas_karyawan = $nomor_identitas_karyawan . '<br><span class="badges bg-label-danger">NON AKTIF</span>';
                        } else {
                            $nomor_identitas_karyawan = $row->Karyawan->nomor_identitas_karyawan;
                        }
                    }
                    return $nomor_identitas_karyawan;
                })
                ->addColumn('nama_divisi', function ($row) use ($holding) {
                    if ($row->Karyawan == null || $row->Karyawan == '') {
                        $divisi = '-';
                    } else {
                        if ($row->Karyawan->divisi_id == '' || $row->Karyawan->divisi_id == null) {
                            $divisi = '-';
                        } else {
                            if ($row->Karyawan->Divisi == null) {
                                $divisi = '-';
                            } else {
                                $divisi = $row->Karyawan->Divisi->nama_divisi;
                            }
                        }
                    }
                    return $divisi;
                })
                ->addColumn('nama_jabatan', function ($row) use ($holding) {
                    if ($row->Karyawan == null || $row->Karyawan == '') {
                        $jabatan = '-';
                    } else {
                        if ($row->Karyawan->jabatan_id == '' || $row->Karyawan->jabatan_id == '-') {
                            $jabatan = '-';
                        } else {
                            if ($row->Karyawan->Jabatan == null) {
                                $jabatan = '-';
                            } else {
                                $jabatan = $row->Karyawan->Jabatan->nama_jabatan;
                            }
                        }
                    }
                    return $jabatan;
                })
                ->addColumn('akses', function ($row) use ($holding) {
                    if ($row->is_admin == '' || $row->is_admin == '-') {
                        $akses = '-';
                    } else {
                        $akses = $row->is_admin;
                    }
                    return $akses;
                })
                ->addColumn('user_aktif', function ($row) use ($holding) {
                    if ($row->user_aktif == '' || $row->user_aktif == '-') {
                        $user_aktif = '-';
                    } else {
                        if ($row->user_aktif == 'AKTIF') {
                            $user_aktif = '<button type="button" class="btn btn-sm btn-icon btn-outline-success waves-effect"><span class="tf-icons mdi mdi-checkbox-marked-circle-outline"></span></button>';
                        } else {
                            $user_aktif = '<button type="button" class="btn btn-sm btn-icon btn-outline-danger waves-effect"><span class="tf-icons mdi mdi-minus-circle-outline"></span></button>';
                        }
                    }
                    return $user_aktif;
                })
                ->addColumn('status', function ($row) use ($holding) {
                    if ($row->user_aktif == '' || $row->user_aktif == '-') {
                        $status = '-';
                    } else if ($row->user_aktif == 'AKTIF') {
                        $status = '<span class="badge bg-label-success">' . $row->user_aktif . '</span>';
                    } else if ($row->user_aktif == 'NON AKTIF') {
                        $status = '<span class="badge bg-label-danger">' . $row->user_aktif . '</span>';
                    }
                    return $status;
                })
                ->addColumn('option', function ($row) use ($holding) {
                    if ($row->Karyawan == null) {
                        $jabatan = NULL;
                        $divisi = NULL;
                        $foto = NULL;
                        $tgl_mulai_kontrak = NULL;
                        $tgl_selesai_kontrak = NULL;
                        $name = NULL;
                        $penempatan_kerja = NULL;
                        $kontrak_kerja = NULL;
                        $bagian = NULL;
                        $status_aktif = NULL;
                    } else {
                        $foto = $row->Karyawan->foto;
                        $tgl_mulai_kontrak = $row->Karyawan->tgl_mulai_kontrak;
                        $tgl_selesai_kontrak = $row->Karyawan->tgl_selesai_kontrak;
                        $name = $row->Karyawan->name;
                        $penempatan_kerja = $row->Karyawan->penempatan_kerja;
                        $kontrak_kerja = $row->Karyawan->kontrak_kerja;
                        $status_aktif = $row->Karyawan->status_aktif;
                        if ($row->Karyawan->bagian_id == '' || $row->Karyawan->bagian_id == null) {
                            $bagian = NULL;
                        } else {
                            if ($row->Karyawan->Bagian == null) {
                                $bagian = NULL;
                            } else {
                                $bagian = $row->Karyawan->Bagian->nama_bagian;
                            }
                        }
                        if ($row->Karyawan->jabatan_id == '' || $row->Karyawan->jabatan_id == null) {
                            $jabatan = NULL;
                        } else {
                            if ($row->Karyawan->Jabatan == null) {
                                $jabatan = NULL;
                            } else {
                                $jabatan = $row->Karyawan->Jabatan->nama_jabatan;
                            }
                        }
                        if ($row->Karyawan->divisi_id == '' || $row->Karyawan->divisi_id == null) {
                            $divisi = NULL;
                        } else {
                            if ($row->Karyawan->Divisi == null) {
                                $divisi = NULL;
                            } else {
                                $divisi = $row->Karyawan->Divisi->nama_divisi;
                            }
                        }
                    }
                    $btn = '<button id="btn_edit_password" data-id="' . $row->id . '" data-holding="' . $holding . '" type="button" class="btn btn-icon btn-secondary waves-effect waves-light"><span class="tf-icons mdi mdi-key-outline"></span></button>';
                    if ($row->user_aktif == 'AKTIF') {
                        $btn1 = '<button type="button" id="btn_non_aktif_karyawan"  data-status_aktif="' . $status_aktif . '" data-foto="' . $foto . '" data-id="' . $row->id . '" data-tgl_mulai_kontrak="' . $tgl_mulai_kontrak . '" data-tgl_selesai_kontrak="' . $tgl_selesai_kontrak . '" data-nama="' . $name . '" data-divisi="' . $divisi . '" data-jabatan="' . $jabatan . '" data-bagian="' . $bagian . '"  data-holding="' . $holding . '" data-penempatan_kerja="' . $penempatan_kerja . '" data-kontrak_kerja="' . $kontrak_kerja . '"  class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-account-off"></span></button>';
                    } else {
                        $btn1 = '<button type="button" id="btn_aktif_karyawan"  data-status_aktif="' . $status_aktif . '" data-foto="' . $foto . '" data-id="' . $row->id . '" data-tgl_mulai_kontrak="' . $tgl_mulai_kontrak . '" data-tgl_selesai_kontrak="' . $tgl_selesai_kontrak . '" data-nama="' . $name . '" data-divisi="' . $divisi . '" data-jabatan="' . $jabatan . '" data-bagian="' . $bagian . '"  data-holding="' . $holding . '" data-penempatan_kerja="' . $penempatan_kerja . '" data-kontrak_kerja="' . $kontrak_kerja . '"  class="btn btn-icon btn-success waves-effect waves-light"><span class="tf-icons mdi mdi-account-check-outline"></span></button>';
                    }
                    $btn = $btn . $btn1;
                    return $btn;
                })
                ->rawColumns(['nama_jabatan', 'nomor_identitas_karyawan', 'name', 'user_aktif', 'nama_divisi', 'akses', 'status', 'option'])
                ->make(true);
        }
    }
    public function datatable_users_harian(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $table = User::With(['Karyawan' => function ($query) use ($holding) {

            $query->with('Divisi');
            $query->with('Jabatan');
            $query->where('kontrak_kerja', $holding);
            $query->where('status_aktif', 'AKTIF');
            $query->where('kategori', 'Karyawan Harian');
        }])->Join('karyawans', 'karyawans.id', 'users.karyawan_id')
            ->where('user_aktif', 'AKTIF')
            ->where('karyawans.kategori', 'Karyawan Harian')
            ->where('karyawans.kontrak_kerja', $holding)
            ->select('users.*')
            ->orderBy('id', 'DESC')
            // ->limit(2)
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('name', function ($row) use ($holding) {
                    if ($row->Karyawan == null || $row->Karyawan == '') {
                        $name = '-';
                    } else {
                        if ($row->Karyawan->status_aktif == 'NON AKTIF') {
                            $name = '<span style="text-decoration: line-through;>' . $row->Karyawan->name . '</span>';
                        } else {
                            $name = '<span>' . $row->Karyawan->name . '</span>';
                        }
                    }
                    return $name;
                })
                ->addColumn('nomor_identitas_karyawan', function ($row) use ($holding) {
                    if ($row->Karyawan == null || $row->Karyawan == '') {
                        $nomor_identitas_karyawan = '-';
                    } else {
                        $nomor_identitas_karyawan = $row->Karyawan->nomor_identitas_karyawan;
                    }
                    return $nomor_identitas_karyawan;
                })
                ->addColumn('nama_divisi', function ($row) use ($holding) {
                    if ($row->Karyawan == null || $row->Karyawan == '') {
                        $divisi = '-';
                    } else {
                        if ($row->Karyawan->divisi_id == '' || $row->Karyawan->divisi_id == null) {
                            $divisi = '-';
                        } else {
                            if ($row->Karyawan->Divisi == null) {
                                $divisi = '-';
                            } else {
                                $divisi = $row->Karyawan->Divisi->nama_divisi;
                            }
                        }
                    }
                    return $divisi;
                })
                ->addColumn('nama_jabatan', function ($row) use ($holding) {
                    if ($row->Karyawan == null || $row->Karyawan == '') {
                        $jabatan = '-';
                    } else {
                        if ($row->Karyawan->jabatan_id == '' || $row->Karyawan->jabatan_id == '-') {
                            $jabatan = '-';
                        } else {
                            if ($row->Karyawan->Jabatan == null) {
                                $jabatan = '-';
                            } else {
                                $jabatan = $row->Karyawan->Jabatan->nama_jabatan;
                            }
                        }
                    }
                    return $jabatan;
                })
                ->addColumn('akses', function ($row) use ($holding) {
                    if ($row->is_admin == '' || $row->is_admin == '-') {
                        $akses = '-';
                    } else {
                        $akses = $row->is_admin;
                    }
                    return $akses;
                })
                ->addColumn('user_aktif', function ($row) use ($holding) {
                    if ($row->user_aktif == '' || $row->user_aktif == '-') {
                        $user_aktif = '-';
                    } else {
                        $user_aktif = '<button type="button" class="btn btn-sm btn-icon btn-outline-success waves-effect"><span class="tf-icons mdi mdi-checkbox-marked-circle-outline"></span></button>';
                    }
                    return $user_aktif;
                })
                ->addColumn('status', function ($row) use ($holding) {
                    if ($row->status_aktif == '' || $row->status_aktif == '-') {
                        $status = '-';
                    } else if ($row->status_aktif == 'AKTIF') {
                        $status = '<span class="badge bg-label-success">' . $row->status_aktif . '</span>';
                    } else if ($row->status_aktif == 'NON AKTIF') {
                        $status = '<span class="badge bg-label-danger">' . $row->status_aktif . '</span>';
                    }
                    return $status;
                })
                ->addColumn('option', function ($row) use ($holding) {
                    if ($row->Karyawan == null) {
                        $jabatan = NULL;
                        $divisi = NULL;
                        $foto = NULL;
                        $tgl_mulai_kontrak = NULL;
                        $tgl_selesai_kontrak = NULL;
                        $name = NULL;
                        $penempatan_kerja = NULL;
                        $kontrak_kerja = NULL;
                        $bagian = NULL;
                    } else {
                        $foto = $row->Karyawan->foto;
                        $tgl_mulai_kontrak = $row->Karyawan->tgl_mulai_kontrak;
                        $tgl_selesai_kontrak = $row->Karyawan->tgl_selesai_kontrak;
                        $name = $row->Karyawan->name;
                        $penempatan_kerja = $row->Karyawan->penempatan_kerja;
                        $kontrak_kerja = $row->Karyawan->kontrak_kerja;
                        if ($row->Karyawan->bagian_id == '' || $row->Karyawan->bagian_id == null) {
                            $bagian = NULL;
                        } else {
                            if ($row->Karyawan->Bagian == null) {
                                $bagian = NULL;
                            } else {
                                $bagian = $row->Karyawan->Bagian->nama_bagian;
                            }
                        }
                        if ($row->Karyawan->jabatan_id == '' || $row->Karyawan->jabatan_id == null) {
                            $jabatan = NULL;
                        } else {
                            if ($row->Karyawan->Jabatan == null) {
                                $jabatan = NULL;
                            } else {
                                $jabatan = $row->Karyawan->Jabatan->nama_jabatan;
                            }
                        }
                        if ($row->Karyawan->divisi_id == '' || $row->Karyawan->divisi_id == null) {
                            $divisi = NULL;
                        } else {
                            if ($row->Karyawan->Divisi == null) {
                                $divisi = NULL;
                            } else {
                                $divisi = $row->Karyawan->Divisi->nama_divisi;
                            }
                        }
                    }
                    $btn = '<button id="btn_edit_password" data-id="' . $row->id . '" data-holding="' . $holding . '" type="button" class="btn btn-icon btn-secondary waves-effect waves-light"><span class="tf-icons mdi mdi-key-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_non_aktif_karyawan" data-foto="' . $foto . '" data-id="' . $row->id . '" data-tgl_mulai_kontrak="' . $tgl_mulai_kontrak . '" data-tgl_selesai_kontrak="' . $tgl_selesai_kontrak . '" data-nama="' . $name . '" data-divisi="' . $divisi . '" data-jabatan="' . $jabatan . '" data-bagian="' . $bagian . '"  data-holding="' . $holding . '" data-penempatan_kerja="' . $penempatan_kerja . '" data-kontrak_kerja="' . $kontrak_kerja . '"  class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-account-remove-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['nama_jabatan', 'nomor_identitas_karyawan', 'name', 'user_aktif', 'nama_divisi', 'akses', 'status', 'option'])
                ->make(true);
        }
    }
    public function editpassword($id)
    {
        // dd(Karyawan::find($id));
        $jabatan = Jabatan::join('karyawans', function ($join) {
            $join->on('jabatans.id', '=', 'karyawans.jabatan_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan1_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan2_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan3_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan4_id');
        })
            ->Join('users', 'users.karyawan_id', 'karyawans.id')
            ->where('users.id', $id)
            ->select('karyawans.*', 'jabatans.*')
            ->get();
        $divisi = Divisi::join('karyawans', function ($join) {
            $join->on('divisis.id', '=', 'karyawans.divisi_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi1_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi2_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi3_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi4_id');
        })
            ->Join('users', 'users.karyawan_id', 'karyawans.id')
            ->where('users.id', $id)
            ->select('karyawans.*', 'divisis.*')
            ->get();
        // dd($divisi);
        $no = 1;
        $no1 = 1;
        $holding = request()->segment(count(request()->segments()));
        return view('admin.karyawan.edit_password_karyawan', [
            'title' => 'Edit Password',
            'holding' => $holding,
            'karyawan' => Karyawan::With('Divisi')->With('Jabatan')->Join('users', 'users.karyawan_id', 'karyawans.id')->where('users.id', $id)->first(),
            'jabatan_karyawan' => $jabatan,
            'divisi_karyawan' => $divisi,
            'no' => $no,
            'no1' => $no1,
        ]);
    }
    public function editPasswordProses(Request $request, $id)
    {
        // dd('ok');
        $holding = request()->segment(count(request()->segments()));
        if ($request->username == $request->username_old) {
            $rules = [
                'username' => 'required|max:255',
                'password' => 'required|min:6|max:18',
            ];
        } else {
            $rules = [
                'username' => 'required|unique:users|max:255',
                'password' => 'required|min:6|max:18',
            ];
        }
        $customMessages = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute tidak boleh sama',
            // 'email' => ':attribute format email salah',
            'min' => ':attribute Kurang',
        ];
        $validasi = Validator::make($request->all(), $rules, $customMessages);
        if ($validasi->fails()) {
            $errors = $validasi->errors()->first();
            // dd($errors);
            Alert::error('Gagal', $errors);
            return back()->withInput();
        }
        $validatedData = $request->validate($rules, $customMessages);
        User::where('id', $id)->update([
            'username' => $validatedData['username'],
            'password_show' => $validatedData['password'],
            'password' => Hash::make($validatedData['password'])
        ]);
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'activity' => 'update',
            'description' => 'Mengubah Ubah User karyawan ' . $request->name,
        ]);
        Alert::success('success', 'User Berhasil Diganti');
        return back();
    }
    public function non_aktif_proses(Request $request)
    {
        $update_user                = User::where('id', $request->id_nonactive)->first();
        $update_user->user_aktif    = 'NON AKTIF';
        $update_user->alasan        = $request->alasan_non_aktif;
        $update_user->update();


        return redirect()->back()->with('success', 'Data Berhasil di Simpan');
    }
    public function aktif_proses(Request $request)
    {
        $update_user                    = User::where('id', $request->id_active)->first();
        $update_user->user_aktif        = 'AKTIF';
        $update_user->alasan            = $request->alasan_aktif;
        $update_user->update();

        return redirect()->back()->with('success', 'Data Berhasil di Simpan');
    }

    public function ImportUser(Request $request)
    {
        // dd('ok');
        $holding = request()->segment(count(request()->segments()));
        try {
            Excel::import(new UsersImport, $request->file_excel);
            return redirect('/users/' . $holding)->with('success', 'Import User Sukses');
        } catch (\InvalidArgumentException $th) {
            // dd($th);
            return redirect()->back()->with('error', 'Import Error' . $th->getMessage());
        }
    }
    public function ImportUpdateUser(Request $request)
    {
        // dd('ok');
        $holding = request()->segment(count(request()->segments()));
        try {
            Excel::import(new UserUpdateImport, $request->file_excel_update);
            return redirect('/users/' . $holding)->with('success', 'Import User Sukses');
        } catch (\InvalidArgumentException $th) {
            // dd($th);
            return redirect('/users/' . $holding)->with('error', 'Import Error' . $th->getMessage());
        }
    }
    public function ExportUser(Request $request)
    {
        $date = date('YmdHis');
        $holding = request()->segment(count(request()->segments()));
        try {
            $query = Excel::download(new UserExport($holding), 'Data User Karyawan_' . $holding . '_' . $date . '.xlsx');
            return $query;
        } catch (\InvalidArgumentException $th) {
            // dd($th);
            return redirect()->back()->with('error', 'Import Error' . $th->getMessage());
        }
    }
    public function download_pdf_user_karyawan(Request $request)
    {
        set_time_limit(6000);
        $cek_holding = request()->segment(count(request()->segments()));
        if ($cek_holding == 'sp') {
            $holding = 'CV. SUMBER PANGAN';
        } else if ($cek_holding == 'sps') {
            $holding = 'PT. SURYA PANGAN SEMESTA';
        } else {
            $holding = 'CV. SURYA INTI PANGAN';
        }
        $date = date('YmdHis');
        $data = [
            'user' => User::Join('karyawans', 'karyawans.id', 'users.karyawan_id')->where('karyawans.kontrak_kerja', $cek_holding)->orderBy('karyawans.name', 'ASC')->get(),
            'holding' => $holding,
            'cek_holding' => $cek_holding,
        ];
        // dd($data);
        $pdf = PDF::loadView('admin/karyawan/cetak_pdf_user_karyawan', $data)->setPaper('F4', 'potrait');
        return $pdf->stream('DATA USER KARYAWAN' . $holding . '_' . $date . 'pdf');
    }
}
