<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\FingerMachine;
use App\Models\Holding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class FingerController extends Controller
{
    public function index($holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        return view('admin.finger.index', [
            'title' => 'Master Finger',
            'holding' => $holding
        ]);
    }
    public function datatable(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $table = FingerMachine::where('status', 1)->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('status', function ($row) {
                    if ($row->IsActive == 1) {
                        $status = '<button class="btn btn-sm btn-success">Active</button';
                    } else {
                        $status = '<button class="btn btn-sm btn-secondary">Non Active</button';
                    }
                    return $status;
                })
                ->addColumn('option', function ($row) use ($holding) {
                    $btn = '<button id="btn_edit_finger" data-id="' . $row->Id . '" data-name_finger="' . $row->Name . '" data-ip_mesin="' . $row->Ip . '" data-port_mesin="' . $row->Port . '" data-isactive="' . $row->IsActive . '" data-holding="' . $holding->id . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_finger" data-id="' . $row->Id . '" data-holding="' . $holding->id . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns([
                    'option',
                    'status'
                ])
                ->make(true);
        }
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $holding = Holding::where('holding_code', $request->holding)->first();
        try {
            $validatedData = $request->validate([
                'nama_mesin' => 'required|max:255',
                'ip_mesin'   => 'required|ip',
                'port_mesin' => 'required|numeric'
            ], [
                'nama_mesin.required' => 'Nama mesin harus diisi',
                'ip_mesin.required'   => 'IP mesin harus diisi',
                'port_mesin.required' => 'Port mesin harus diisi',
                'ip_mesin.ip'         => 'IP mesin harus valid',
                'port_mesin.numeric'  => 'Port mesin harus angka'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'code'   => 422,
                'errors' => $e->errors()
            ], 422);
        }

        FingerMachine::create([
            'Name' => $validatedData['nama_mesin'],
            'Ip' => $validatedData['ip_mesin'],
            'Port' => $validatedData['port_mesin'],
            'IsActive' => 1
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'data berhasil ditambahkan'
        ]);
    }
    public function update(Request $request)
    {
        $holding = Holding::where('holding_code', $request->holding)->first();
        try {
            $validatedData = $request->validate([
                'nama_mesin' => 'required|max:255',
                'ip_mesin'   => 'required|ip',
                'port_mesin' => 'required|numeric'
            ], [
                'nama_mesin.required' => 'Nama mesin harus diisi',
                'ip_mesin.required'   => 'IP mesin harus diisi',
                'port_mesin.required' => 'Port mesin harus diisi',
                'ip_mesin.ip'         => 'IP mesin harus valid',
                'port_mesin.numeric'  => 'Port mesin harus angka'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'code'   => 422,
                'errors' => $e->errors()
            ], 422);
        }

        FingerMachine::where('Id', $request->id)->update([
            'Name' => $validatedData['nama_mesin'],
            'Ip' => $validatedData['ip_mesin'],
            'Port' => $validatedData['port_mesin'],
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Data Berhasil Diupdate'
        ]);
    }
    public function destroy($id, $holding)
    {
        // dd($id, $holding);
        $holding = Holding::where('holding_code', $holding)->first();
        try {
            $data = FingerMachine::where('Id', $id)->update([
                'IsActive' => 0,
                'status' => 0
            ]);
            return response()->json([
                'code' => 200,
                'message' => 'Data Berhasil Dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Data Gagal Dihapus'
            ]);
        }
    }
}
