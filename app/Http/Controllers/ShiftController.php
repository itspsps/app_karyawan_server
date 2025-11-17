<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ActivityLog;
use App\Models\Holding;
use App\Models\Menu;
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
    public function index($holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $roleId = Auth::user();
        $menus = Menu::whereIn('id', function ($query) use ($roleId) {
            $query->select('menu_id')
                ->from('role_menus')
                ->where('role_id', $roleId->role);
        })
            ->whereNull('parent_id') // menu utama
            ->with('children')
            ->where('kategori', 'web')      // load submenunya
            ->orderBy('sort_order')
            ->get();
        $roleId = Auth::user();
        $menus = Menu::whereIn('id', function ($query) use ($roleId) {
            $query->select('menu_id')
                ->from('role_menus')
                ->where('role_id', $roleId->role);
        })
            ->whereNull('parent_id') // menu utama
            ->with('children')
            ->where('kategori', 'web')      // load submenunya
            ->orderBy('sort_order')
            ->get();
        return view('admin.shift.index', [
            'title' => 'Master Shift',
            'holding' => $holding,
            'shift' => Shift::all(),
            'menus' => $menus
        ]);
    }
    public function datatable(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $table = Shift::all();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('option', function ($row) use ($holding) {
                    $btn = '<button id="btn_edit_shift" data-id="' . $row->id . '" data-shift="' . $row->nama_shift . '" data-terlambat="' . $row->jam_terlambat . '" data-pulangcepat="' . $row->jam_pulang_cepat . '" data-jammasuk="' . $row->jam_masuk . '" data-jamkeluar="' . $row->jam_keluar . '" data-holding="' . $holding->id . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_shift" data-id="' . $row->id . '" data-holding="' . $holding->id . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
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
        $holding = Holding::where('holding_code', $request->holding)->first();
        try {
            $validatedData = $request->validate([
                'nama_shift' => 'required|max:255',
                'jam_min_masuk' => 'required',
                'jam_masuk' => 'required',
                'jam_keluar' => 'required',
                'jam_terlambat' => 'required',
                'jam_pulang_cepat' => 'required'
            ], [
                'nama_shift.required' => 'Nama shift wajib diisi',
                'jam_min_masuk.required' => 'Jam min masuk wajib diisi',
                'jam_masuk.required' => 'Jam masuk wajib diisi',
                'jam_keluar.required' => 'Jam keluar wajib diisi',
                'jam_terlambat.required' => 'Jam terlambat wajib diisi',
                'jam_pulang_cepat.required' => 'Jam pulang cepat wajib diisi'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'code'   => 422,
                'message' => $e->errors()
            ], 422);
        }

        Shift::create([
            'nama_shift' => $validatedData['nama_shift'],
            'jam_min_masuk' => $validatedData['jam_min_masuk'],
            'jam_masuk' => $validatedData['jam_masuk'],
            'jam_keluar' => $validatedData['jam_keluar'],
            'jam_terlambat' => $validatedData['jam_terlambat'],
            'jam_pulang_cepat' => $validatedData['jam_pulang_cepat']
        ]);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'create',
            'description' => 'Menambahkan data master shift dengan nama shift ' . $validatedData['nama_shift']
        ]);
        return response()->json([
            'code' => 200,
            'message' => 'data berhasil ditambahkan'
        ]);
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
    public function update(Request $request, $holding)
    {
        $holding = Holding::where('id', $holding)->first();
        $validatedData = $request->validate([
            'nama_shift_update' => 'required|max:255',
            'jam_masuk_update' => 'required',
            'jam_kerja_update' => 'required',
            'jam_keluar_update' => 'required'
        ]);

        Shift::where('id', $request->id_shift)->update([
            'nama_shift' => $validatedData['nama_shift_update'],
            'jam_masuk' => $validatedData['jam_masuk_update'],
            'jam_kerja' => $validatedData['jam_kerja_update'],
            'jam_keluar' => $validatedData['jam_keluar_update'],
        ]);
        if (Auth::user()->is_admin == 'user') {
            $user_activity = Auth::user()->karyawan_id;
        } else {
            $user_activity = Auth::user()->id;
        }
        ActivityLog::create([
            'user_id' => $user_activity,
            'object_id' => $request->id_shift,
            'kategory_activity' => 'SHIFT',
            'activity' => 'Update Master Shift',
            'description' => 'Mengubah data master shift dengan nama shift ' . $request->nama_shift
        ]);
        return redirect('/shift/' . $holding->holding_code)->with('success', 'data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $holding)
    {
        $holding = Holding::where('id', $holding)->first();
        if ($holding) {
            $holding = $holding->holding_code;
            $delete = Shift::find($id);
            $delete->delete();
            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'delete',
                'description' => 'Menghapus data master shift dengan nama shift ' . $delete->nama_shift
            ]);
            return response()->json([
                'code' => 200,
                'message' => 'Data Berhasil di Delete'
            ]);
        } else {
            return response()->json([
                'code' => 404,
                'message' => 'Holding tidak ditemukan'
            ]);
        }
    }
}
