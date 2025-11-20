<?php

namespace App\Http\Controllers;

use App\Models\Holding;
use App\Models\Menu;
use App\Models\RoleUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class HoldingController extends Controller
{
    public function index($holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $get_role = RoleUsers::where('role_user_id', Auth::user()->id)->pluck('role_menu_id')->toArray();
        // dd($get_role);
        if (count($get_role) == 0) {
            $roleId = null;
        } else {
            $roleId = $get_role;
        }
        if ($roleId == null) {
            $menus = collect();
        } else {
            $menus = Menu::whereIn('id', function ($query) use ($roleId) {
                $query->select('menu_id')
                    ->from('role_menus')
                    ->whereIn('role_id', $roleId);
            })
                ->whereNull('parent_id') // menu utama
                ->with('children')
                ->where('kategori', 'web')      // load submenunya
                ->orderBy('sort_order')
                ->get();
        }
        return view('admin.holding.holding', [
            'title' => 'Master Holding',
            'holding' => $holding,
            'menus' => $menus,
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
