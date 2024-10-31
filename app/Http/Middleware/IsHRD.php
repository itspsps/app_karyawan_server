<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class IsHRD
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
        // dd(Auth::user());
        if (Auth::user()->is_admin != "hrd") {
            // dd('ok');
            Alert::warning('warning', 'Auth Access User Anda Diabatasi');
            return redirect()->back()->with('warning', 'Auth Access User Anda Diabatasi');
        }
        return $next($request);
    }
}
