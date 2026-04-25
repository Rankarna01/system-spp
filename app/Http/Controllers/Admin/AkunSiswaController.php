<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunSiswaController extends Controller
{
    public function index()
    {
        // Ambil semua user yang memiliki role 'siswa'
        $akun = User::where('role', 'siswa')->latest()->get();
        
        // Ambil data siswa yang namanya belum ada di tabel users (belum punya akun)
        $akunTerdaftar = User::where('role', 'siswa')->pluck('name');
        $siswaBelumPunyaAkun = Siswa::whereNotIn('nama', $akunTerdaftar)->get();

        return view('admin.manajemen-siswa.akun.index', compact('akun', 'siswaBelumPunyaAkun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        // Cari biodata siswa
        $siswa = Siswa::findOrFail($request->siswa_id);

        // Buat Akun User
        User::create([
            'name' => $siswa->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa'
        ]);

        return back()->with('success', 'Akun login untuk ' . $siswa->nama . ' berhasil dibuat!');
    }

    public function update(Request $request, string $id)
    {
        // Fungsi update di sini khusus untuk Reset Password
        $request->validate([
            'password' => 'required|min:6'
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password untuk akun ' . $user->name . ' berhasil di-reset!');
    }

    public function destroy(string $id)
    {
        // Menghapus akun login (TIDAK menghapus biodata siswa)
        User::findOrFail($id)->delete();
        return back()->with('success', 'Akun login berhasil dihapus! Biodata siswa tetap aman.');
    }
}