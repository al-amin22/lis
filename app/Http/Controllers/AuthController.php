<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            // Cek role user
            if (Auth::user()->role === 'admin') {
                return redirect()->route('pasien.index');
            }

            if (Auth::user()->role === 'pengguna') {
                return redirect()->route('user.index');
            }

            // Default jika role tidak dikenali
            return redirect()->route('login')->withErrors([
                'email' => 'Role tidak dikenali!',
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau Password salah!',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
