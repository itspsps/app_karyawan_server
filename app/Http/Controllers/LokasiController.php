<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\Holding;
use App\Models\Site;
use App\Models\Titik;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class LokasiController extends Controller
{
    public function index($holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        return view('admin.lokasi.index', [
            'title' => 'Setting Lokasi Kantor',
            'holding' => $holding,
            'data_site' => Site::where('site_holding_category', $holding->id)->get(),
            'lokasi' => Lokasi::all()
        ]);
    }
    public function get_lokasi(Request $request)
    {
        // dd($request->all());
        $data = Site::where('id', $request->value)->first();
        // dd($data);
        return json_encode($data);
    }
    public function tambah_lokasi($holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $site = Site::where('site_holding_category', $holding->id)->get();
        return view('admin.lokasi.tambah_lokasi', [
            'title' => 'Setting Lokasi Kantor',
            'holding' => $holding,
            'site' => $site,
            'data_lokasi' => Site::where('site_holding_category', $holding->id)->get(),
            'lokasi' => Site::all()
        ]);
    }
    public function datatable($holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        // $table = Titik::get();
        $table = Lokasi::with(['Site' => function ($query) use ($holding) {
            $query->where('site_holding_category', $holding->id);
        }])->whereHas('Site', function ($query) use ($holding) {
            $query->where('site_holding_category', $holding->id);
        })->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_lokasi', function ($row) use ($holding) {

                    return $row->nama_lokasi;
                })
                ->addColumn('nama_site', function ($row) use ($holding) {

                    return $row->Site->site_name;
                })
                ->addColumn('radius_lokasi', function ($row) use ($holding) {

                    return $row->radius_lokasi . ' M';
                })

                ->addColumn('lihat_maps', function ($row) use ($holding) {

                    $btn = '<button id="btn_lihat_lokasi" data-id="' . $row->id . '" data-site="' . $row->Site->site_name . '" data-lokasi="' . $row->nama_lokasi . '" data-lat="' . $row->lat_lokasi . '" data-long="' . $row->long_lokasi . '"  data-radius="' . $row->radius_lokasi . '" data-holding="' . $holding->id . '" type="button" class="btn btn-sm btn-success "><i class="menu-icon tf-icons mdi mdi-file-image-marker-outline"></i>Lihat Maps</button>';
                    return $btn;
                })
                ->addColumn('option', function ($row) use ($holding) {

                    $btn = '<button id="btn_edit_lokasi" data-sitename="' . $row->Site->site_name . '" data-id="' . $row->id . '" data-lokasi="' . $row->nama_lokasi . '" data-site="' . $row->site_id . '" data-lat="' . $row->lat_lokasi . '" data-long="' . $row->long_lokasi . '"  data-radius="' . $row->radius_lokasi . '" data-holding="' . $holding->id . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_lokasi" data-id="' . $row->id . '" data-holding="' . $holding->id . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['nama_lokasi', 'nama_site', 'radius_lokasi', 'lihat_maps', 'lat_lokasi', 'long_lokasi', 'radius_lokasi', 'option'])
                ->make(true);
        }
    }
    public function addLokasi(Request $request, $holding)
    {
        // dd($request->all());
        $holding = Holding::where('holding_code', $holding)->first();
        // dd($holding, $holding->holding_code);
        $validatedData = $request->validate([
            'nama_lokasi' => 'required',
            'lat_lokasi' => 'required',
            'long_lokasi' => 'required',
            'radius' => 'required',
            'site' => 'required'
        ]);
        Lokasi::create(
            [
                'site_id' => $validatedData['site'],
                'nama_lokasi' => $validatedData['nama_lokasi'],
                'lat_lokasi' => $validatedData['lat_lokasi'],
                'long_lokasi' => $validatedData['long_lokasi'],
                'radius_lokasi' => $validatedData['radius'],
            ]
        );
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Tambah',
            'description' => 'Menambah data Titik lokasi kantor'
        ]);
        return redirect('/lokasi/' . $holding->holding_code)->with('success', 'Lokasi Berhasil Ditambahkan');
    }
    public function updateLokasi(Request $request, $holding)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'nama_lokasi_update' => 'required',
            'long_lokasi_update' => 'required',
            'lat_lokasi_update' => 'required',
            'radius_update' => 'required',
            'site_update' => 'required'
        ]);
        $cek = Lokasi::where('id', $request->id_lokasi)->first();
        // dd($cek);
        if (!$cek) {
            return redirect()->back()->with('error', 'Lokasi tidak ditemukan');
        } else {
            Lokasi::where('id', $request->id_lokasi)->update(
                [
                    'nama_lokasi' => $validatedData['nama_lokasi_update'],
                    'site_id' => Site::where('id', $validatedData['site_update'])->value('id'),
                    'lat_lokasi' => $validatedData['lat_lokasi_update'],
                    'long_lokasi' => $validatedData['long_lokasi_update'],
                    'radius_lokasi' => $validatedData['radius_update'],
                ]
            );
            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'update',
                'description' => 'Mengubah data lokasi kantor'
            ]);
            return redirect()->back()->with('success', 'Lokasi Berhasil Diupdate');
        }
    }

    public function deleteLokasi($id)
    {
        $query = Titik::where('id', $id)->delete();
        return json_encode($query);
    }
    public function updateRadiusLokasi(Request $request, $id)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $validatedData = $request->validate([
            'radius' => 'required',
        ]);

        Lokasi::where('id', $id)->update($validatedData);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'update',
            'description' => 'Mengubah data radius lokasi kantor'
        ]);
        return redirect('/lokasi-kantor/' . $holding)->with('success', 'Lokasi Berhasil Diupdate');
    }
}
