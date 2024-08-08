<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
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
    public function datatable(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $table = Lokasi::where('kategori_kantor', $holding)->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('option', function ($row) use ($holding) {
                    $btn = '<button id="btn_edit_lokasi" data-id="' . $row->id . '" data-lokasi="' . $row->lokasi_kantor . '" data-lat="' . $row->lat_kantor . '" data-long="' . $row->long_kantor . '"  data-radius="' . $row->radius . '" data-holding="' . $holding . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_lokasi" data-id="' . $row->id . '" data-holding="' . $holding . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['option'])
                ->make(true);
        }
    }
    public function addLokasi(Request $request)
    {
        // dd($request->all());
        $holding = request()->segment(count(request()->segments()));
        $validatedData = $request->validate([
            'lokasi_kantor' => 'required',
            'lat_kantor' => 'required',
            'long_kantor' => 'required',
            'radius' => 'required'
        ]);
        Lokasi::insert(
            [
                'kode_kantor' => Uuid::uuid4(),
                'kategori_kantor' => $holding,
                'lokasi_kantor' => $validatedData['lokasi_kantor'],
                'lat_kantor' => $validatedData['lat_kantor'],
                'long_kantor' => $validatedData['long_kantor'],
                'radius' => $validatedData['radius'],
            ]
        );
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Tambah',
            'description' => 'Menambah data lokasi kantor'
        ]);
        return redirect('/lokasi-kantor/' . $holding)->with('success', 'Lokasi Berhasil Ditambahkan');
    }
    public function updateLokasi(Request $request)
    {
        // dd($request->all());
        $holding = request()->segment(count(request()->segments()));
        $validatedData = $request->validate([
            'lokasi_kantor_update' => 'required',
            'kategori_kantor_update' => 'required',
            'lat_kantor_update' => 'required',
            'long_kantor_update' => 'required',
            'radius_update' => 'required'
        ]);

        Lokasi::where('id', $request->id_lokasi)->update(
            [
                'kategori_kantor' => $validatedData['kategori_kantor_update'],
                'lat_kantor' => $validatedData['lat_kantor_update'],
                'lokasi_kantor' => $validatedData['lokasi_kantor_update'],
                'long_kantor' => $validatedData['long_kantor_update'],
                'radius' => $validatedData['radius_update'],
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
        $query = Lokasi::where('id', $id)->delete();
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
