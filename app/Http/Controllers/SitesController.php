<?php

namespace App\Http\Controllers;

use App\Models\Holding;
use App\Models\Menu;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SitesController extends Controller
{
    public function index($holding)
    {
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
        $holding = Holding::where('holding_code', $holding)->first();
        return view('admin.site.site', [
            'title' => 'Master Sites',
            'holding' => $holding,
            'menus' => $menus,
            'master_site' => Site::all()
        ]);
    }
    public function tambah_site($holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        $site = Site::where('site_holding_category', $holding)->get();
        return view('admin.site.tambah_site', [
            'title' => 'Tambah Site',
            'holding' => $holding,
        ]);
    }
    public function addSite(Request $request, $holding)
    {
        // dd($request->all());
        $holding = Holding::where('holding_code', $holding)->first();
        $validatedData = $request->validate([
            'id_holding' => 'required',
            'nama_site' => 'required',
            'alamat_site' => 'required',
            'status_site' => 'required',
        ]);
        Site::create(
            [
                'site_name' => $validatedData['nama_site'],
                'site_holding_category' => $validatedData['id_holding'],
                'site_alamat' => $validatedData['alamat_site'],
                'site_status' => $validatedData['status_site'],
            ]
        );

        return redirect('/site/' . $holding->holding_code)->with('success', 'Site Berhasil Ditambahkan');
    }
    public function datatable(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        // $table = Titik::get();
        $table = Site::with('Holding')->where('site_holding_category', $holding->id)->orderBy('id', 'ASC')->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)

                ->addColumn('holding', function ($row) {
                    $holding = $row->Holding->holding_name;
                    return $holding;
                })
                ->addColumn('lihat_maps', function ($row) use ($holding) {

                    $btn = '<button id="btn_lihat_lokasi" data-id="' . $row->id . '" data-lokasi="' . $row->site_name . '" data-nama_titik="' . $row->site_name . '" data-lat="' . $row->site_lat . '" data-long="' . $row->site_long . '"  data-radius="' . $row->site_radius . '" data-holding="' . $holding->id . '" type="button" class="btn btn-sm btn-success "><i class="menu-icon tf-icons mdi mdi-file-image-marker-outline"></i>Lihat Maps</button>';
                    return $btn;
                })
                ->addColumn('option', function ($row) {

                    $btn = '<button id="btn_edit_holding" data-id="' . $row->id . '" data-code="' . $row->holding_code . '" data-holding="' . $row->holding_name . '" data-category="' . $row->holding_category . '" type="button" class="btn btn-icon btn-warning waves-effect waves-light"><span class="tf-icons mdi mdi-pencil-outline"></span></button>';
                    $btn = $btn . '<button type="button" id="btn_delete_holding" data-id="' . $row->id . '" class="btn btn-icon btn-danger waves-effect waves-light"><span class="tf-icons mdi mdi-delete-outline"></span></button>';
                    return $btn;
                })
                ->rawColumns(['holding', 'option', 'lihat_maps'])
                ->make(true);
        }
    }
}
