<?php

namespace App\Http\Controllers;

use App\Models\Holding;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HoldingController extends Controller
{
    public function index($holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        return view('admin.holding.holding', [
            'title' => 'Master Holding',
            'holding' => $holding,
            'master_holding' => Holding::all()
        ]);
    }
    public function datatable(Request $request, $holding)
    {
        // $table = Titik::get();
        $table = Holding::orderBy('id', 'ASC')->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)

                ->addColumn('option', function ($row) {

                    $btn = '<button id="btn_edit_holding" data-id="' . $row->id . '" data-code="' . $row->holding_code . '" data-holding="' . $row->holding_name . '" data-category="' . $row->holding_category . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_holding" data-id="' . $row->id . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['option'])
                ->make(true);
        }
    }
}
