<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    //
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')->get();
        // dd($logs);
        // Filter data berdasarkan jenis aktivitas dan kata kunci pencarian
        if ($request->has('activity') && $request->activity != '') {
            $logs->where('activity', $request->activity);
        }

        if ($request->has('search') && $request->search != '') {
            $logs->where('description', 'like', '%' . $request->search . '%');
        }

        $holding = request()->segment(count(request()->segments()));
        return view(
            'admin.activity-logs.index',
            [
                'logs' => $logs,
                'holding' => $holding,
                'title' => 'Activity Logs',
            ]
        );
    }
    public function datatable(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $holding = request()->segment(count(request()->segments()));
        if (!empty($request->activity)) {
            $table = ActivityLog::with('user')->where('activity', $request->activity)->get();
        } else {
            $table = ActivityLog::with('user')->get();
        }
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('name', function ($row) {
                    $name = $row->user->name;
                    return $name;
                })
                ->addColumn('waktu', function ($row) {
                    $waktu = date('d-m-Y H:i:s', strtotime($row->created_at));
                    return $waktu;
                })
                ->rawColumns(['name', 'waktu'])
                ->make(true);
        }
    }
}
