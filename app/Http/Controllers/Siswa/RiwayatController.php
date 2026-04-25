<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cari data Siswa berdasarkan nama (Logic yang sama)
        $siswa = Siswa::where('nama', $user->name)->first();
        if (!$siswa) {
            $siswa = Siswa::first(); // Fallback untuk testing
        }

        // Ambil riwayat pembayaran milik siswa ini, urutkan dari yang paling baru
        $riwayat = Pembayaran::with(['tagihan'])
            ->whereHas('tagihan', function($query) use ($siswa) {
                $query->where('siswa_id', $siswa->id);
            })
            ->orderBy('waktu_transaksi', 'desc')
            ->get();

        return view('siswa.riwayat.index', compact('riwayat'));
    }
}