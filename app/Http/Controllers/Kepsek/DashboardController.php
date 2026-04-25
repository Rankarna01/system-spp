<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. KPI Data
        $totalPemasukan = Pembayaran::where('status', 'lunas')->sum('gross_amount');
        $pemasukanBulanIni = Pembayaran::where('status', 'lunas')->whereMonth('waktu_transaksi', date('m'))->whereYear('waktu_transaksi', date('Y'))->sum('gross_amount');
        $totalTunggakan = Tagihan::whereIn('status', ['belum_bayar', 'menunggak'])->sum('nominal');
        $totalSiswaLunas = Tagihan::where('status', 'lunas')->where('bulan', date('n'))->count(); // Asumsi bulan pakai angka 1-12

        // 2. Line Chart: Pemasukan per Bulan (Tahun Berjalan)
        $pemasukanPerBulan = Pembayaran::select(
                DB::raw('MONTH(waktu_transaksi) as bulan'), 
                DB::raw('SUM(gross_amount) as total')
            )
            ->where('status', 'lunas')
            ->whereYear('waktu_transaksi', date('Y'))
            ->groupBy('bulan')
            ->pluck('total', 'bulan')->toArray();

        $chartLineData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartLineData[] = $pemasukanPerBulan[$i] ?? 0;
        }

        // 3. Doughnut Chart: Distribusi Status Tagihan Keseluruhan
        $chartDoughnutData = [
            Tagihan::where('status', 'lunas')->count(),
            Tagihan::where('status', 'menunggu')->count(),
            Tagihan::whereIn('status', ['belum_bayar', 'menunggak'])->count(),
        ];

        return view('kepsek.dashboard.index', compact(
            'totalPemasukan', 
            'pemasukanBulanIni', 
            'totalTunggakan',
            'totalSiswaLunas',
            'chartLineData',
            'chartDoughnutData'
        ));
    }
}