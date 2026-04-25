<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil data detail siswa
        $siswa = Siswa::with('kelas.jurusan')->where('nama', $user->name)->first();

        return view('siswa.profil.index', compact('user', 'siswa'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed', // Harus ada field password_confirmation di view
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password minimal harus 8 karakter.'
        ]);

        $user = Auth::user();

        // Cek apakah password lama sesuai
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini yang Anda masukkan salah!');
        }

        // Update ke password baru
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password Anda berhasil diperbarui!');
    }
}