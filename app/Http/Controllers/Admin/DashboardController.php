<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // 1. STATISTIK SISWA
        $totalSiswa = Siswa::count();
        $siswaBaruBulanIni = Siswa::whereMonth('created_at', $now->month)
                                  ->whereYear('created_at', $now->year)
                                  ->count();

        // 2. STATISTIK PEMASUKAN
        $pemasukanBulanIni = Pembayaran::where('status', 'lunas')
                                       ->whereMonth('waktu_transaksi', $now->month)
                                       ->whereYear('waktu_transaksi', $now->year)
                                       ->sum('gross_amount');
                                       
        $pemasukanBulanLalu = Pembayaran::where('status', 'lunas')
                                        ->whereMonth('waktu_transaksi', $now->copy()->subMonth()->month)
                                        ->whereYear('waktu_transaksi', $now->copy()->subMonth()->year)
                                        ->sum('gross_amount');

        // Hitung persentase kenaikan/penurunan pemasukan
        $persentasePemasukan = 0;
        if ($pemasukanBulanLalu > 0) {
            $persentasePemasukan = (($pemasukanBulanIni - $pemasukanBulanLalu) / $pemasukanBulanLalu) * 100;
        }

        // 3. STATISTIK KEPATUHAN (Siswa Sudah Bayar vs Tunggakan)
        // Asumsi: Menghitung tagihan yang di-generate bulan ini
        $tagihanBulanIni = Tagihan::whereMonth('created_at', $now->month)
                                  ->whereYear('created_at', $now->year);
                                  
        $siswaSudahBayar = (clone $tagihanBulanIni)->where('status', 'lunas')->count();
        
        $persentaseSudahBayar = 0;
        if ($totalSiswa > 0) {
            $persentaseSudahBayar = ($siswaSudahBayar / $totalSiswa) * 100;
        }

        $siswaMenunggak = Tagihan::whereIn('status', ['belum_bayar', 'menunggak'])->distinct('siswa_id')->count('siswa_id');
        $totalNominalTunggakan = Tagihan::whereIn('status', ['belum_bayar', 'menunggak'])->sum('nominal');

        // 4. DATA GRAFIK LINE (Tren 6 Bulan Terakhir)
        $chartLabels = [];
        $chartPemasukanData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $bulanTarget = $now->copy()->subMonths($i);
            $chartLabels[] = $bulanTarget->translatedFormat('M'); // Contoh: Jan, Feb
            
            $totalPerBulan = Pembayaran::where('status', 'lunas')
                                       ->whereMonth('waktu_transaksi', $bulanTarget->month)
                                       ->whereYear('waktu_transaksi', $bulanTarget->year)
                                       ->sum('gross_amount');
            
            // Disimpan dalam skala Juta (karena di view ada tulisan 'Juta Rp')
            $chartPemasukanData[] = round($totalPerBulan / 1000000, 2); 
        }

        // 5. DATA GRAFIK DOUGHNUT (Status SPP)
        $totalTagihanStatus = Tagihan::count();
        $persenLunas = 0; $persenMenunggu = 0; $persenBelum = 0;
        
        if ($totalTagihanStatus > 0) {
            $persenLunas = round((Tagihan::where('status', 'lunas')->count() / $totalTagihanStatus) * 100);
            $persenMenunggu = round((Tagihan::where('status', 'menunggu')->count() / $totalTagihanStatus) * 100);
            $persenBelum = round((Tagihan::whereIn('status', ['belum_bayar', 'menunggak'])->count() / $totalTagihanStatus) * 100);
        }
        $chartStatusData = [$persenLunas, $persenMenunggu, $persenBelum];

        // 6. TRANSAKSI TERBARU (Table)
        $transaksiTerbaru = Pembayaran::with(['tagihan.siswa.kelas'])
                                      ->latest('waktu_transaksi')
                                      ->take(3)
                                      ->get();

        return view('admin.dashboard.index', compact(
            'totalSiswa', 'siswaBaruBulanIni', 
            'pemasukanBulanIni', 'persentasePemasukan',
            'siswaSudahBayar', 'persentaseSudahBayar',
            'siswaMenunggak', 'totalNominalTunggakan',
            'chartLabels', 'chartPemasukanData', 'chartStatusData',
            'transaksiTerbaru'
        ));
    }
}