<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view("auth.login");
    }

    // Fungsi login
    public function login(LoginRequest $request){
        $credentials = $request->validated();
        if(Auth::attempt([
            "email" => $credentials["email"],
            "password" => $credentials["password"]
             ])){
            return redirect()->route('dashboard')->with('success', 'Berhasil login');
        }
        return redirect()->back()->with('error', 'email atau password salah');
    }

    // Fungsi logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('landing page')->with('success','Berhasil logout');
    }
}
