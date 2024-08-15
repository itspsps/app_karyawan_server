<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\Titik;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class LokasiController extends Controller
{
    public function index()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.lokasi.index', [
            'title' => 'Setting Lokasi Kantor',
            'holding' => $holding,
            'data_lokasi' => Lokasi::where('kategori_kantor', $holding)->get(),
            'lokasi' => Lokasi::all()
        ]);
    }
    public function get_lokasi(Request $request)
    {
        // dd($request->all());
        $data = Lokasi::where('kategori_kantor', $request->holding)->where('lokasi_kantor', $request->value)->first();
        return json_encode($data);
    }
    public function tambah_lokasi()
    {
        $holding = request()->segment(count(request()->segments()));
        $lokasi_kantor = Lokasi::where('kategori_kantor', $holding)->get();
        return view('admin.lokasi.tambah_lokasi', [
            'title' => 'Setting Lokasi Kantor',
            'holding' => $holding,
            'lokasi_kantor' => $lokasi_kantor,
            'data_lokasi' => Lokasi::where('kategori_kantor', $holding)->get(),
            'lokasi' => Lokasi::all()
        ]);
    }
    public function datatable(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        // $table = Titik::get();
        $table = Titik::with(['Lokasi' => function ($query) use ($holding) {
            $query->where('kategori_kantor', $holding);
        }])->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('lokasi_kantor', function ($row) use ($holding) {
                    if ($row->Lokasi == '') {
                        return NULL;
                    } else {
                        return $row->Lokasi->lokasi_kantor;
                    }
                })

                ->addColumn('lihat_maps', function ($row) use ($holding) {
                    if ($row->Lokasi == '') {
                        return NULL;
                    } else {
                        $btn = '<button id="btn_lihat_lokasi" data-id="' . $row->id . '" data-lokasi="' . $row->Lokasi->lokasi_kantor . '" data-nama_titik="' . $row->nama_titik . '" data-lat="' . $row->lat_titik . '" data-long="' . $row->long_titik . '"  data-radius="' . $row->radius_titik . '" data-holding="' . $holding . '" type="button" class="btn btn-sm btn-success "><i class="menu-icon tf-icons mdi mdi-file-image-marker-outline"></i>Lihat Maps</button>';
                        return $btn;
                    }
                })
                ->addColumn('option', function ($row) use ($holding) {
                    if ($row->Lokasi == '') {
                        return NULL;
                    } else {
                        $btn = '<button id="btn_edit_lokasi" data-id="' . $row->id . '" data-lokasi="' . $row->Lokasi->lokasi_kantor . '" data-nama_titik="' . $row->nama_titik . '" data-lat="' . $row->lat_titik . '" data-long="' . $row->long_titik . '"  data-radius="' . $row->radius_titik . '" data-holding="' . $holding . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                        $btn = $btn . '<button type="button" id="btn_delete_lokasi" data-id="' . $row->id . '" data-holding="' . $holding . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                        return $btn;
                    }
                })
                ->rawColumns(['lokasi_kantor', 'lihat_maps', 'nama_titik', 'lat_titik', 'long_titik', 'radius_titik', 'option'])
                ->make(true);
        }
    }
    public function addLokasi(Request $request)
    {
        // dd($request->all());
        $holding = request()->segment(count(request()->segments()));
        $validatedData = $request->validate([
            'lokasi_kantor' => 'required',
            'nama_titik' => 'required',
            'lat_titik' => 'required',
            'long_titik' => 'required',
            'radius' => 'required'
        ]);
        Titik::create(
            [
                'lokasi_id' => Lokasi::where('lokasi_kantor', $validatedData['lokasi_kantor'])->value('id'),
                'nama_titik' => $validatedData['nama_titik'],
                'lat_titik' => $validatedData['lat_titik'],
                'long_titik' => $validatedData['long_titik'],
                'radius_titik' => $validatedData['radius'],
            ]
        );
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Tambah',
            'description' => 'Menambah data Titik lokasi kantor'
        ]);
        return redirect('/lokasi-kantor/' . $holding)->with('success', 'Lokasi Berhasil Ditambahkan');
    }
    public function updateLokasi(Request $request)
    {
        // dd($request->all());
        $holding = request()->segment(count(request()->segments()));
        $validatedData = $request->validate([
            'lokasi_kantor_update' => 'required',
            'nama_titik_update' => 'required',
            'kategori_kantor_update' => 'required',
            'lat_kantor_update' => 'required',
            'long_kantor_update' => 'required',
            'radius_update' => 'required'
        ]);

        Titik::where('id', $request->id_lokasi)->update(
            [
                'nama_titik' => $validatedData['nama_titik_update'],
                'lokasi_id' => Lokasi::where('lokasi_kantor', $validatedData['lokasi_kantor_update'])->value('id'),
                'lat_titik' => $validatedData['lat_kantor_update'],
                'long_titik' => $validatedData['long_kantor_update'],
                'radius_titik' => $validatedData['radius_update'],
            ]
        );
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'update',
            'description' => 'Mengubah data lokasi kantor'
        ]);
        return redirect('/lokasi-kantor/' . $holding)->with('success', 'Lokasi Berhasil Diupdate');
    }

    public function deleteLokasi($id)
    {
        $query = Titik::where('id', $id)->delete();
        return json_encode($query);
    }
    public function updateRadiusLokasi(Request $request, $id)
    {
        $holding = request()->segment(count(request()->segments()));
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
