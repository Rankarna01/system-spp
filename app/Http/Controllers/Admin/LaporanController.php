<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $kpi = [
            'total_pemasukan' => Pembayaran::where('status', 'lunas')->sum('gross_amount'),
            'total_tunggakan' => Tagihan::whereIn('status', ['belum_bayar', 'menunggak'])->sum('nominal'),
            'siswa_lunas' => Tagihan::where('status', 'lunas')->distinct('siswa_id')->count('siswa_id'),
            'siswa_nunggak' => Tagihan::whereIn('status', ['belum_bayar', 'menunggak'])->distinct('siswa_id')->count('siswa_id'),
        ];

        $tahunIni = date('Y');
        $pemasukanPerBulan = Pembayaran::select(DB::raw('MONTH(waktu_transaksi) as bulan'), DB::raw('SUM(gross_amount) as total'))
            ->where('status', 'lunas')
            ->whereYear('waktu_transaksi', $tahunIni)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')->toArray();

        $chartPemasukan = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartPemasukan[] = $pemasukanPerBulan[$i] ?? 0;
        }

        $kelas = Kelas::all();

        return view('admin.laporan.index', compact('kpi', 'chartPemasukan', 'kelas', 'tahunIni'));
    }

    public function exportPdf(Request $request)
    {
        $jenis = $request->jenis_laporan;
        return back()->with('success', "Fitur cetak PDF untuk $jenis sedang dalam tahap integrasi library!");
    }

    public function exportExcel(Request $request)
    {
        $jenis = $request->jenis_laporan;
        return back()->with('success', "Fitur cetak Excel untuk $jenis sedang dalam tahap integrasi library!");
    }
}