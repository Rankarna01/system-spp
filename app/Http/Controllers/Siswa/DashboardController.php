<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Notifikasi;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = Siswa::with('kelas.jurusan')->where('nama', $user->name)->first();
        if (!$siswa) {
            $siswa = Siswa::with('kelas.jurusan')->first(); 
        }

        $totalTagihan = 0;
        $tagihanTerakhir = null;

        if ($siswa) {
            $totalTagihan = Tagihan::where('siswa_id', $siswa->id)
                                   ->whereIn('status', ['belum_bayar', 'menunggak'])
                                   ->sum('nominal');
            $tagihanTerakhir = Tagihan::where('siswa_id', $siswa->id)->latest()->first();
        }

        $notifikasi = Notifikasi::where('user_id', $user->id)
                                ->latest()
                                ->take(3)
                                ->get();

        return view('siswa.dashboard.index', compact('siswa', 'totalTagihan', 'tagihanTerakhir', 'notifikasi'));
    }
}