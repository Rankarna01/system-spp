<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class TagihanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cari data Siswa berdasarkan nama (Sama seperti logic di Dashboard)
        $siswa = Siswa::where('nama', $user->name)->first();
        if (!$siswa) {
            $siswa = Siswa::first(); // Fallback untuk testing
        }

        // Ambil semua tagihan milik siswa ini, urutkan dari jatuh tempo terdekat/terbaru
        $tagihan = Tagihan::with(['sppMaster', 'pembayaranAktif'])
            ->where('siswa_id', $siswa->id)
            ->orderBy('jatuh_tempo', 'desc')
            ->get();

        return view('siswa.tagihan.index', compact('tagihan'));
    }
}