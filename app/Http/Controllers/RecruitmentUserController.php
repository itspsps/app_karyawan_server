<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\Bagian;
use App\Models\Divisi;
use App\Models\User;
use App\Models\Recruitment;
use App\Models\RecruitmentUser;
use App\Models\RecruitmentInterview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use DB;

class RecruitmentUserController extends Controller
{
    public function konfirmasi($email)
    {
        $url = request()->segment(count(request()->segments()));
        $data = DB::table('recruitment_user')->join(
                'recruitment_interview', 'recruitment_user.id', '=',
                'recruitment_interview.recruitment_userid')->first();
        if($data->status_interview == 0){
            RecruitmentInterview::where('id', $data->id)->where('status_interview', 0)->update([
                'status_interview' => 1,
            ]);
        }
        // dd($data);
        return 'HALAMAN DASHBOARD USER';
    }

    public function tidak_konfirmasi($email)
    {
        $url = request()->segment(count(request()->segments()));
        dd($url);
    }

}
