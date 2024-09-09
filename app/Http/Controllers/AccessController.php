<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Lokasi;
use App\Models\Provincies;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class AccessController extends Controller
{
    public function index()
    {

        $holding = request()->segment(count(request()->segments()));
        if ($holding == 'sp') {
            $kontrak_kerja = 'SP';
        } else if ($holding == 'sps') {
            $kontrak_kerja = 'SPS';
        } else if ($holding == 'sip') {
            $kontrak_kerja = 'SIP';
        }
        return view('admin.access.index', [
            // return view('karyawan.index', [
            'title' => 'Karyawan',
            "data_departemen" => Departemen::all(),
            'holding' => $holding,
            'data_user' => User::where('kontrak_kerja', $kontrak_kerja)->where('is_admin', 'user')->get(),
            "data_departemen" => Departemen::all(),
            "data_jabatan" => Jabatan::all(),
            "karyawan_laki" => User::where('gender', 'Laki-Laki')->where('kontrak_kerja', $holding)->count(),
            "karyawan_perempuan" => User::where('gender', 'Perempuan')->where('kontrak_kerja', $holding)->count(),
            "karyawan_office" => User::where('gender', 'Laki-Laki')->where('kontrak_kerja', $holding)->count(),
            "karyawan_shift" => User::where('gender', 'Perempuan')->where('kontrak_kerja', $holding)->count(),
        ]);
    }
    public function datatable(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $table = User::where('kontrak_kerja', $holding)->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('departemen', function ($row) use ($holding) {
                    $dept = Departemen::where('id', $row->dept_id)->value('nama_departemen');
                    return $dept;
                })
                ->addColumn('divisi', function ($row) use ($holding) {
                    $divisi = Divisi::where('id', $row->divisi_id)->value('nama_divisi');
                    return $divisi;
                })
                ->addColumn('jabatan', function ($row) use ($holding) {
                    $jabatan = Jabatan::where('id', $row->jabatan_id)->value('nama_jabatan');
                    return $jabatan;
                })
                ->addColumn('access', function ($row) use ($holding) {
                    if ($row->access_1 == 'on') {
                        $access =  'Mapping Shift Kuli';
                    } else {
                        $access = NULL;
                    }
                    return $access;
                })
                ->addColumn('option', function ($row) use ($holding) {
                    $btn = '<button id="btn_add_access_karyawan" data-id="' . $row->id . '" data-holding="' . $holding . '" class="btn btn-icon btn-primary waves-effect waves-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Access"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_karyawan" data-id="' . $row->id . '" data-holding="' . $holding . '" class="btn btn-icon btn-danger waves-effect waves-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Access"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['option', 'departemen', 'divisi', 'jabatan', 'access'])
                ->make(true);
        }
    }
    public function add_access(Request $request, $id)
    {
        $holding = request()->segment(count(request()->segments()));
        $user = User::with('Jabatan')
            ->with('Divisi')
            ->with('Divisi1')
            ->with('Divisi2')
            ->with('Divisi3')
            ->with('Divisi4')
            ->with('Jabatan1')
            ->with('Jabatan2')
            ->with('Jabatan3')
            ->with('Jabatan4')
            ->with('Departemen')
            ->where('kontrak_kerja', $holding)
            ->where('users.id', $id)
            ->first();
        return json_encode($user);
    }
    public function access_save_add(Request $request)
    {
        // dd($request->all());
        $holding = request()->segment(count(request()->segments()));
        $access = User::where('id', $request->id_karyawan)->update([
            'access_1' => $request->access_1,
        ]);
        Alert::success('Berhasil', 'Anda Berhasil Menyimpan Data');
        return redirect('/access/' . $holding);
    }
}
