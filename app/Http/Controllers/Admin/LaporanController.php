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
        $bulanMulai = $request->bulan_mulai;
        $bulanSelesai = $request->bulan_selesai;
        $kelasId = $request->kelas_id;

        $data = [];
        $judulLaporan = '';

        if ($jenis == 'pemasukan_bulanan') {
            $query = Pembayaran::with(['tagihan.siswa.kelas'])->where('status', 'lunas');
            if ($bulanMulai && $bulanSelesai) {
                $tanggalAwal = $bulanMulai . '-01 00:00:00';
                $tanggalAkhir = date('Y-m-t 23:59:59', strtotime($bulanSelesai . '-01'));
                $query->whereBetween('waktu_transaksi', [$tanggalAwal, $tanggalAkhir]);
            }
            $data = $query->orderBy('waktu_transaksi', 'asc')->get();
            $judulLaporan = 'Laporan Riwayat Pemasukan';

        } elseif ($jenis == 'tunggakan') {
            $query = Tagihan::with(['siswa.kelas'])->whereIn('status', ['belum_bayar', 'menunggak']);
            $data = $query->orderBy('jatuh_tempo', 'asc')->get();
            $judulLaporan = 'Laporan Daftar Tunggakan Siswa';

        } elseif ($jenis == 'rekap_kelas') {
            $query = Tagihan::with(['siswa.kelas']);
            if ($kelasId && $kelasId != 'semua') {
                $query->whereHas('siswa', function($q) use ($kelasId) {
                    $q->where('kelas_id', $kelasId);
                });
            }
            $data = $query->orderBy('siswa_id')->get();
            $judulLaporan = 'Laporan Rekapitulasi Tagihan per Kelas';
        }

        return view('admin.laporan.print', compact('data', 'jenis', 'judulLaporan', 'bulanMulai', 'bulanSelesai'));
    }

    public function exportExcel(Request $request)
    {
        $jenis = $request->jenis_laporan;
        $bulanMulai = $request->bulan_mulai;
        $bulanSelesai = $request->bulan_selesai;
        $kelasId = $request->kelas_id;

        $data = [];
        $judulLaporan = '';

        // Logika tarik data (Sama persis dengan PDF)
        if ($jenis == 'pemasukan_bulanan') {
            $query = Pembayaran::with(['tagihan.siswa.kelas'])->where('status', 'lunas');
            if ($bulanMulai && $bulanSelesai) {
                $tanggalAwal = $bulanMulai . '-01 00:00:00';
                $tanggalAkhir = date('Y-m-t 23:59:59', strtotime($bulanSelesai . '-01'));
                $query->whereBetween('waktu_transaksi', [$tanggalAwal, $tanggalAkhir]);
            }
            $data = $query->orderBy('waktu_transaksi', 'asc')->get();
            $judulLaporan = 'Laporan Riwayat Pemasukan';

        } elseif ($jenis == 'tunggakan') {
            $query = Tagihan::with(['siswa.kelas'])->whereIn('status', ['belum_bayar', 'menunggak']);
            $data = $query->orderBy('jatuh_tempo', 'asc')->get();
            $judulLaporan = 'Laporan Daftar Tunggakan Siswa';

        } elseif ($jenis == 'rekap_kelas') {
            $query = Tagihan::with(['siswa.kelas']);
            if ($kelasId && $kelasId != 'semua') {
                $query->whereHas('siswa', function($q) use ($kelasId) {
                    $q->where('kelas_id', $kelasId);
                });
            }
            $data = $query->orderBy('siswa_id')->get();
            $judulLaporan = 'Laporan Rekapitulasi Tagihan per Kelas';
        }

        // Trik export Excel native tanpa library eksternal
        $filename = "Laporan_SPP_" . $jenis . "_" . date('Ymd') . ".xls";
        
        return response(view('admin.laporan.print', compact('data', 'jenis', 'judulLaporan', 'bulanMulai', 'bulanSelesai')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}