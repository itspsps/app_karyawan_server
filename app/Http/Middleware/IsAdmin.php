<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            // dd('ok');
            return redirect('/');
        }
        // if (Auth::user()->is_admin != 'admin') {
        //     // dd(Auth::user());
        //     Alert::warning('warning', 'Auth Access User Anda Diabatasi');
        //     return redirect()->back()->with('warning', 'Auth Access User Anda Diabatasi');
        // }

        return $next($request);
    }
}
