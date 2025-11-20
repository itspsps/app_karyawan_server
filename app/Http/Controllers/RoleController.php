<?php

namespace App\Http\Controllers;

use App\Models\Holding;
use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleMenu;
use App\Models\RoleUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use ParagonIE\Sodium\Core\Curve25519\H;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        return view('admin.role.index', [
            'title' => 'Role Management',
            'holding' => $holding,
            'menus' => $menus
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable($holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $table = Role::with(['menus' => function ($q) {
            $q->orderBy('sort_order', 'asc');
        }])->get();
        if (request()->ajax()) {
            return datatables()->of($table)
                ->addColumn('role_name', function ($row) use ($holding) {
                    if ($row->role_name != null) {
                        $role_name = $row->role_name;
                    } else {
                        $role_name = '-';
                    }
                    return $role_name;
                })
                ->addColumn('description', function ($row) use ($holding) {
                    if ($row->role_description != null) {
                        $role_description = $row->role_description;
                    } else {
                        $role_description = '-';
                    }
                    return $role_description;
                })
                ->addColumn('list_menu', function ($row) use ($holding) {
                    $menus = $row->menus->pluck('name')->toArray();
                    $menuList = '';
                    foreach ($menus as $menu) {
                        $menuList .= '<li>' . $menu . '</li>';
                    }
                    return '<ul>' . $menuList . '</ul>';
                })
                ->addColumn('action', function ($row) use ($holding) {
                    $btn = '<button  data-id="' . $row->id . '" data-role_name="' . $row->role_name . '" data-description="' . $row->role_description . '" class="btn_edit_role btn btn-warning btn-sm"><i class="mdi mdi-pencil"></i></button>';
                    $btn = $btn . '<button id="btn_delete_role" data-id="' . $row->id . '"  class="btn btn-danger btn-sm"><i class="mdi mdi-trash-can"></i></button>';
                    return $btn;
                })
                ->rawColumns(['role_name', 'description', 'action', 'list_menu'])
                ->make(true);
        }
    }
    public function datatable_menu($holding, Request $request)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $table = Menu::all();
        if (request()->ajax()) {
            return datatables()->of($table)
                ->addColumn('nama_menu', function ($row) use ($holding) {
                    if ($row->name != null) {
                        $name = $row->name;
                    } else {
                        $name = '-';
                    }
                    return $name;
                })
                ->addColumn('kategori', function ($row) use ($holding) {
                    if ($row->kategori != null) {
                        $kategori = $row->kategori;
                    } else {
                        $kategori = '-';
                    }
                    return $kategori;
                })
                ->addColumn('option', function ($row) use ($request) {
                    $role_menu = RoleMenu::where('role_id', $request->id)->where('menu_id', $row->id)->first();
                    if ($role_menu != null) {
                        $checked = 'checked';
                    } else {
                        $checked = '';
                    }
                    $btn = ' <div class="form-check mb-2"><input class="form-check-input" type="checkbox" ' . $checked . ' id="menu_id" name="menu_id[]" value="' . $row->id . '"><label class="form-check-label" for="menu_id"> Buka Access</label></div>';
                    return $btn;
                })
                ->rawColumns(['nama_menu', 'option', 'kategori'])
                ->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function role_save_add(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'role_description' => 'nullable|string|max:500',
        ]);

        $role = Role::create([
            'role_name' => $request->role_name,
            'role_description' => $request->role_description,
        ]);

        if ($request->has('menu_id')) {
            // dd($request->has('menu_id'), $request->all());
            $menuData = [];
            foreach ($request->menu_id as $menuId) {
                $menuData[$menuId] = [
                    'can_view' => in_array('can_view_' . $menuId, $request->all()) ? 1 : 0,
                    'can_create' => in_array('can_create_' . $menuId, $request->all()) ? 1 : 0,
                    'can_edit' => in_array('can_edit_' . $menuId, $request->all()) ? 1 : 0,
                    'can_delete' => in_array('can_delete_' . $menuId, $request->all()) ? 1 : 0,
                ];
            }
            $role->menus()->sync($menuData);
        }

        return redirect()->back()->with('success', 'Role berhasil ditambahkan.');
    }
    public function role_save_update(Request $request)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(), [
            'role_name_update' => 'required|string|max:255',
            'role_description_update' => 'nullable|string|max:500',
        ], [
            'role_name_update.required' => 'Nama role harus diisi.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        $delete = Role::where('id', $request->id_role)->delete();
        $delete = RoleMenu::where('role_id', $request->id_role)->delete();
        $role = Role::create([
            'role_name' => $request->role_name_update,
            'role_description' => $request->role_description_update,
        ]);

        if ($request->has('menu_id')) {
            $menuData = [];
            foreach ($request->menu_id as $menuId) {
                $menuData[$menuId] = [
                    'can_view' => in_array('can_view_' . $menuId, $request->all()) ? 1 : 0,
                    'can_create' => in_array('can_create_' . $menuId, $request->all()) ? 1 : 0,
                    'can_edit' => in_array('can_edit_' . $menuId, $request->all()) ? 1 : 0,
                    'can_delete' => in_array('can_delete_' . $menuId, $request->all()) ? 1 : 0,
                ];
            }
            // dd($menuData, $request->all());
            $role->menus()->sync($menuData);
        }

        return redirect()->back()->with('success', 'Role berhasil Diubah.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
