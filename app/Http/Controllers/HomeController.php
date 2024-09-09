<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        dd('ok');
        if (!auth()->check()) {
            return view('auth.login', [
                "title" => "Log In"
            ]);
        } else {
            if (auth()->user()->is_admin == 'admin') {
                return redirect('dashboard/holding');
            } else {
                return redirect('home');
            }
        }
    }
}
