<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class BankController extends Controller
{
    public function index()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('bank.index', [
            'holding' => $holding,
            'title' => 'Master Bank',
            // 'data_departemen' => Departemen::all()
        ]);
    }

    public function create()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('bank.create', [
            'holding' => $holding,
            'title' => 'Tambah Data Bank'
        ]);
    }


}
