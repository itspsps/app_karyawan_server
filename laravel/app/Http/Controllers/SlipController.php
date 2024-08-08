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

class SlipController extends Controller
{
    public function index()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('id-card.index', [
            'title' => 'Id card',
            'holding' => $holding,
        ]);
    }
}
