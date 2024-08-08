<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.shift.index', [
            'title' => 'Master Shift',
            'holding' => $holding,
            'shift' => Shift::all()
        ]);
    }
    public function datatable(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $table = Shift::all();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('option', function ($row) use ($holding) {
                    $btn = '<button id="btn_edit_shift" data-id="' . $row->id . '" data-shift="' . $row->nama_shift . '" data-jammasuk="' . $row->jam_masuk . '" data-jamkeluar="' . $row->jam_keluar . '" data-holding="' . $holding . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_shift" data-id="' . $row->id . '" data-holding="' . $holding . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['option'])
                ->make(true);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('shift.create', [
            'title' => 'Tambah Data Master Shift',
            'holding' => $holding,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $validatedData = $request->validate([
            'nama_shift' => 'required|max:255',
            'jam_masuk' => 'required',
            'jam_keluar' => 'required'
        ]);

        Shift::create($validatedData);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'create',
            'description' => 'Menambahkan data master shift dengan nama shift ' . $request->nama_shift
        ]);
        return redirect('/shift/' . $holding)->with('success', 'Data Berhasil di Tambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $holding = request()->segment(count(request()->segments()));
        return view("shift.edit", [
            'title' => 'Edit Shift',
            'holding' => $holding,
            'shift' => Shift::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $validatedData = $request->validate([
            'nama_shift_update' => 'required|max:255',
            'jam_masuk_update' => 'required',
            'jam_keluar_update' => 'required'
        ]);

        Shift::where('id', $request->id_shift)->update([
            'nama_shift' => $validatedData['nama_shift_update'],
            'jam_masuk' => $validatedData['jam_masuk_update'],
            'jam_keluar' => $validatedData['jam_keluar_update'],
        ]);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'update',
            'description' => 'Mengubah data master shift dengan nama shift ' . $request->nama_shift
        ]);
        return redirect('/shift/' . $holding)->with('success', 'Data Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $holding = request()->segment(count(request()->segments()));
        $delete = Shift::find($id);
        $delete->delete();
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'delete',
            'description' => 'Menghapus data master shift dengan nama shift ' . $delete->nama_shift
        ]);
        return redirect('/shift/' . $holding)->with('success', 'Data Berhasil di Delete');
    }
}
