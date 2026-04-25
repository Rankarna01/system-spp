<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, redirect sesuai role agar tidak bisa buka halaman login lagi
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Redirect sesuai role
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return back()->with('error', 'Email atau Password salah!')->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil keluar dari sistem.');
    }

    // Fungsi helper untuk menentukan arah redirect
    private function redirectBasedOnRole($role)
    {
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'kepsek') return redirect()->route('kepsek.dashboard');
        if ($role === 'siswa') return redirect()->route('siswa.dashboard');
        
        return redirect('/');
    }
}