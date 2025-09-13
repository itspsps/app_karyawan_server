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
        $table = FingerMachine::all();
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
                    $btn = '<button id="btn_edit_shift" data-id="' . $row->Id . '" data-name="' . $row->Name . '" data-ip="' . $row->Ip . '" data-port="' . $row->Port . '" data-isactive="' . $row->IsActive . '" data-holding="' . $holding->id . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_shift" data-id="' . $row->id . '" data-holding="' . $holding->id . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['option', 'status'])
                ->make(true);
        }
    }
    public function store(Request $request)
    {
        $holding = Holding::where('holding_code', $request->holding)->first();
        try {
            $validatedData = $request->validate([
                'nama_mesin' => 'required|max:255',
                'ip_mesin'   => 'required|ip',
                'port_mesin' => 'required|numeric'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'code'   => 422,
                'errors' => $e->errors()
            ], 422);
        }

        FingerMachine::create($validatedData);

        return response()->json([
            'code' => 200,
            'message' => 'data berhasil ditambahkan'
        ]);
    }
}
