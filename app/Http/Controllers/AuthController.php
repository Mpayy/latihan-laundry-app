<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
            $validate = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8'
            ]);

            if(Auth::attempt($validate)){
                $request->session()->regenerate();
                return redirect('/dashboard');
            }

            return redirect()->back()->withErrors([
                'email' => 'email atau password salah!!'
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->to('/');
    }
}
