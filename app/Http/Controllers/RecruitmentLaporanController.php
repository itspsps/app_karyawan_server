<?php

namespace App\Http\Controllers;

use App\Models\Holding;
use Illuminate\Http\Request;

class RecruitmentLaporanController extends Controller
{
    public function index($holding)
    {
        $holdings = Holding::where('holding_code', $holding)->first();
        return view('admin.recruitment-users.laporan.index', [
            'holding' => $holdings
        ]);
    }
    public function dt_laporan_recruitment() {}
}
