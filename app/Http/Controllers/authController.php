<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\Console\Input\Input;

class authController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return view('auth.login', [
                "title" => "Log In"
            ]);
        } else {
            if (auth()->user()->is_admin == 'admin') {
                return redirect('dashboard/holding');
            } else if (auth()->user()->is_admin == 'superadmin') {
                return redirect('dashboard/holding');
            } else {
                return redirect('home');
            }
        }
    }

    public function register()
    {
        return view('auth.register', [
            "title" => "Register Account"
        ]);
    }

    public function registerProses(Request $request)
    {
        $validatedData = $request->validate([
            "name" => "required|max:255",
            "email" => "required|email:dns|unique:users",
            "password" => "required|confirmed|min:6",
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);
        $request->session()->flash('success', 'Registrasi Berhasil! Silahkan Login.');
        return redirect('/');
    }

    public function loginProses(Request $request)
    {
        // $rules = ['username' => 'required','password' => 'required'];
        // $validator = validator()->make(request()->all(), $rules);
        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator);
        // }
        $remember = $request['remember'] ? true : false;
        // dd($remember);
        $customMessages = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute tidak boleh sama',
            'email' => ':attribute format email salah',
            'min' => ':attribute Kurang',
            'max' => ':attribute Melebihi Batas Maksimal'
        ];
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], $customMessages);
        // dd('ok');
        $fieldType = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $array = $credentials['username'];
        // $oke = json_encode($array);
        // dd($array);
        if ($fieldType == "username") {
            $data = User::where('username', $array)->first();
        } else {
            $data = User::where('email', $array)->first();
        }
        if (Auth::guard('web')->attempt(array($fieldType => $credentials['username'], 'password' => $credentials['password'], 'is_admin' => 'admin'), $remember)) {
            // dd('admin');
            // dd(Auth::guard('web'));
            if (Auth::guard('web')->user()->status_aktif == 'NON AKTIF') {
                Auth::logout();
                $request->session()->flash('user_nonaktif');
                return redirect('/');
            } else {
                // dd('ok');
                Alert::success('Berhasil', 'Selamat Datang');
                return redirect('/dashboard/holding')->with('Berhasil', 'Selamat Datang');
            }
        } else if (Auth::guard('web')->attempt(array($fieldType => $credentials['username'], 'password' => $credentials['password'], 'is_admin' => 'hrd'), $remember)) {
            // dd('superadmin');
            // dd(Auth::guard('web'));
            if (Auth::guard('web')->user()->user_aktif == 'NON AKTIF') {
                Auth::logout();
                $request->session()->flash('user_nonaktif');
                return redirect('/');
            } else {
                // dd('ok');
                // dd(Auth::user());
                Alert::success('Berhasil', 'Selamat Datang');
                return redirect('/dashboard/holding')->with('Berhasil', 'Selamat Datang');
            }
        } else if (Auth::guard('web')->attempt(array($fieldType => $credentials['username'], 'password' => $credentials['password'], 'is_admin' => 'user'), $remember)) {
            // dd('user');
            // dd(Auth::guard('web')->user()->status_aktif);
            $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
            if (Auth::user()->status_aktif == 'NON AKTIF') {
                Auth::logout();
                $request->session()->flash('user_nonaktif');
                return redirect('/');
            } else if ($user_karyawan == NULL) {
                Auth::logout();
                $request->session()->flash('karyawan_null');
                return redirect('/');
            } else if ($user_karyawan->status_aktif == 'NON AKTIF') {
                Auth::logout();
                $request->session()->flash('karyawan_nonaktif');
                return redirect('/');
            } else {
                Alert::success('Berhasil', 'Selamat Datang');
                return redirect('/home')->with('Berhasil', 'Selamat Datang');
            }
        } else {

            $request->session()->flash('login_error');
            return redirect('/');
        }

        $request->session()->flash('login_error');
        return back()->with('loginError', 'Login Gagal!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flash('logout_success');
        return redirect('/');
    }
}
