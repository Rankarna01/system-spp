<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data untuk Dropdown Filter
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();

        // Tentukan jenis laporan yang sedang dibuka (Default: keuangan)
        $jenis = $request->jenis ?? 'keuangan';

        // Variabel untuk menyimpan hasil query
        $dataLaporan = [];
        $totalNominal = 0;

        if ($jenis == 'keuangan') {
            // === LOGIC LAPORAN KEUANGAN (PEMBAYARAN LUNAS) ===
            $query = Pembayaran::with(['tagihan.siswa.kelas.jurusan'])->where('status', 'lunas');

            // Filter Tanggal / Periode
            if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
                $query->whereBetween('waktu_transaksi', [
                    $request->tanggal_awal . ' 00:00:00', 
                    $request->tanggal_akhir . ' 23:59:59'
                ]);
            }

            // Filter Kelas
            if ($request->filled('kelas_id')) {
                $query->whereHas('tagihan.siswa', function($q) use ($request) {
                    $q->where('kelas_id', $request->kelas_id);
                });
            }

            // Filter Jurusan
            if ($request->filled('jurusan_id')) {
                $query->whereHas('tagihan.siswa.kelas', function($q) use ($request) {
                    $q->where('jurusan_id', $request->jurusan_id);
                });
            }

            // Ambil Total Keseluruhan (tanpa paginasi) untuk ringkasan
            $totalNominal = $query->sum('gross_amount');
            
            // Eksekusi Query dengan Paginasi
            $dataLaporan = $query->latest('waktu_transaksi')->paginate(15)->withQueryString();

        } else {
            // === LOGIC LAPORAN TUNGGAKAN ===
            $query = Tagihan::with(['siswa.kelas.jurusan'])->whereIn('status', ['belum_bayar', 'menunggak']);

            // Filter Periode (Jatuh Tempo)
            if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
                $query->whereBetween('jatuh_tempo', [
                    $request->tanggal_awal, 
                    $request->tanggal_akhir
                ]);
            }

            // Filter Kelas
            if ($request->filled('kelas_id')) {
                $query->whereHas('siswa', function($q) use ($request) {
                    $q->where('kelas_id', $request->kelas_id);
                });
            }

            // Filter Jurusan
            if ($request->filled('jurusan_id')) {
                $query->whereHas('siswa.kelas', function($q) use ($request) {
                    $q->where('jurusan_id', $request->jurusan_id);
                });
            }

            // Ambil Total Keseluruhan
            $totalNominal = $query->sum('nominal');

            // Eksekusi Query dengan Paginasi
            $dataLaporan = $query->orderBy('jatuh_tempo', 'asc')->paginate(15)->withQueryString();
        }

        return view('kepsek.laporan.index', compact(
            'jenis', 'dataLaporan', 'totalNominal', 'kelas', 'jurusan'
        ));
    }
}