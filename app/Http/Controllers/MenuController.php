<?php

namespace App\Http\Controllers;

use App\Models\Holding;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
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
        $get_menus = Menu::whereNull('parent_id')->with('children')->where('kategori', 'web')->orderBy('sort_order')->get();
        $parenPts = Menu::whereNull('parent_id')->where('kategori', 'web')->orderBy('sort_order')->get();
        // dd($get_menus);
        return view('admin.menu.index', compact('holding', 'menus', 'get_menus', 'parenPts'));
    }
    public function save_all_change(Request $request)
    {
        dd($request->all());
        $menus = Menu::all();
        foreach ($menus as $menu) {
            $menu->save();
        }
        return response()->json(['status' => 'success']);
    }
}
