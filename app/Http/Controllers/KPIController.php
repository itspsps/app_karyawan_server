<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KPIController extends Controller
{
    public function index()
    {
        $holding = request()->segment(count(request()->segments()));
        $Auth   = Auth::User()->id;
        //dd(date('Y-m-d'));
        $from1  = date('2024-01-01');
        $to1    = date('2024-01-31');
        $from2  = date('2024-01-01');
        $to2    = date('2024-01-29');
        $from3  = date('2024-01-01');
        $to3    = date('2024-01-31');
        $from4  = date('2024-01-01');
        $to4    = date('2024-01-30');
        $from5  = date('2024-01-01');
        $to5    = date('2024-01-31');
        $from6  = date('2024-01-01');
        $to6    = date('2024-01-30');
        $from7  = date('2024-01-01');
        $to7    = date('2024-01-31');
        $from8  = date('2024-01-01');
        $to8    = date('2024-01-31');
        $from9  = date('2024-01-01');
        $to9    = date('2024-01-30');
        $from10  = date('2024-01-01');
        $to10    = date('2024-01-31');
        $from11  = date('2024-01-01');
        $to11    = date('2024-01-30');
        $from12  = date('2024-01-01');
        $to12    = date('2024-01-31');
        $data01 = MappingShift::where('user_id', $Auth)->whereBetween('tanggal', [$from1, $to1])->get();
        $data02 = MappingShift::where('user_id', $Auth)->whereBetween('tanggal', [$from1, $to1])->get();
        $data03 = MappingShift::where('user_id', $Auth)->whereBetween('tanggal', [$from1, $to1])->get();
        $data04 = MappingShift::where('user_id', $Auth)->whereBetween('tanggal', [$from1, $to1])->get();
        $data05 = MappingShift::where('user_id', $Auth)->whereBetween('tanggal', [$from1, $to1])->get();
        $data06 = MappingShift::where('user_id', $Auth)->whereBetween('tanggal', [$from1, $to1])->get();
        $data07 = MappingShift::where('user_id', $Auth)->whereBetween('tanggal', [$from1, $to1])->get();
        $data08 = MappingShift::where('user_id', $Auth)->whereBetween('tanggal', [$from1, $to1])->get();
        $data09 = MappingShift::where('user_id', $Auth)->whereBetween('tanggal', [$from1, $to1])->get();
        $data10 = MappingShift::where('user_id', $Auth)->whereBetween('tanggal', [$from1, $to1])->get();
        $data11 = MappingShift::where('user_id', $Auth)->whereBetween('tanggal', [$from1, $to1])->get();
        $data12 = MappingShift::where('user_id', $Auth)->whereBetween('tanggal', [$from1, $to1])->get();
        return view('kpi.index', [
            'data01' => $data01,
            'title' => 'KPI',
            'holding' => $holding,
        ]);
    }
}
