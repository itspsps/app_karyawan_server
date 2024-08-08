<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\Console\Input\Input;

class authController extends Controller
{
    public function index()
    {
        return view('auth.login', [
            "title" => "Log In"
        ]);
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
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

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
            Alert::success('Berhasil', 'Selamat Datang ' . $data->name);
            return redirect('/dashboard/holding')->with('Berhasil', 'Selamat Datang ' . $data->name);
        } else if (Auth::guard('web')->attempt(array($fieldType => $credentials['username'], 'password' => $credentials['password'], 'is_admin' => 'user'), $remember)) {
            // dd('user');
            Alert::success('Berhasil', 'Selamat Datang ' . $data->name);
            return redirect('/home')->with('Berhasil', 'Selamat Datang ' . $data->name);
        } else {
            // dd('gagal');
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
