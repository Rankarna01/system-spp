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
        // (Isi fungsi index biarkan SAMA PERSIS seperti kodemu sebelumnya)
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();
        $jenis = $request->jenis ?? 'keuangan';
        $dataLaporan = [];
        $totalNominal = 0;

        if ($jenis == 'keuangan') {
            $query = Pembayaran::with(['tagihan.siswa.kelas.jurusan'])->where('status', 'lunas');
            if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
                $query->whereBetween('waktu_transaksi', [$request->tanggal_awal . ' 00:00:00', $request->tanggal_akhir . ' 23:59:59']);
            }
            if ($request->filled('kelas_id')) {
                $query->whereHas('tagihan.siswa', function($q) use ($request) { $q->where('kelas_id', $request->kelas_id); });
            }
            if ($request->filled('jurusan_id')) {
                $query->whereHas('tagihan.siswa.kelas', function($q) use ($request) { $q->where('jurusan_id', $request->jurusan_id); });
            }
            $totalNominal = $query->sum('gross_amount');
            $dataLaporan = $query->latest('waktu_transaksi')->paginate(15)->withQueryString();

        } else {
            $query = Tagihan::with(['siswa.kelas.jurusan'])->whereIn('status', ['belum_bayar', 'menunggak']);
            if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
                $query->whereBetween('jatuh_tempo', [$request->tanggal_awal, $request->tanggal_akhir]);
            }
            if ($request->filled('kelas_id')) {
                $query->whereHas('siswa', function($q) use ($request) { $q->where('kelas_id', $request->kelas_id); });
            }
            if ($request->filled('jurusan_id')) {
                $query->whereHas('siswa.kelas', function($q) use ($request) { $q->where('jurusan_id', $request->jurusan_id); });
            }
            $totalNominal = $query->sum('nominal');
            $dataLaporan = $query->orderBy('jatuh_tempo', 'asc')->paginate(15)->withQueryString();
        }

        return view('kepsek.laporan.index', compact('jenis', 'dataLaporan', 'totalNominal', 'kelas', 'jurusan'));
    }

    // ====================================================
    // FUNGSI BARU: CETAK LAPORAN RESMI DENGAN KOP SURAT
    // ====================================================
    public function cetak(Request $request)
    {
        $jenis_asli = $request->jenis ?? 'keuangan';
        
        // Sesuaikan nama variabel agar cocok dengan file print.blade.php milik Admin
        $jenis = $jenis_asli == 'keuangan' ? 'pemasukan_bulanan' : 'tunggakan';
        $judulLaporan = $jenis_asli == 'keuangan' ? 'Laporan Riwayat Pemasukan Lunas' : 'Laporan Daftar Tunggakan Siswa';

        if ($jenis_asli == 'keuangan') {
            $query = Pembayaran::with(['tagihan.siswa.kelas.jurusan'])->where('status', 'lunas');

            if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
                $query->whereBetween('waktu_transaksi', [$request->tanggal_awal . ' 00:00:00', $request->tanggal_akhir . ' 23:59:59']);
            }
            if ($request->filled('kelas_id')) {
                $query->whereHas('tagihan.siswa', function($q) use ($request) { $q->where('kelas_id', $request->kelas_id); });
            }
            if ($request->filled('jurusan_id')) {
                $query->whereHas('tagihan.siswa.kelas', function($q) use ($request) { $q->where('jurusan_id', $request->jurusan_id); });
            }
            // Tarik semua data tanpa paginate
            $data = $query->latest('waktu_transaksi')->get();

        } else {
            $query = Tagihan::with(['siswa.kelas.jurusan'])->whereIn('status', ['belum_bayar', 'menunggak']);

            if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
                $query->whereBetween('jatuh_tempo', [$request->tanggal_awal, $request->tanggal_akhir]);
            }
            if ($request->filled('kelas_id')) {
                $query->whereHas('siswa', function($q) use ($request) { $q->where('kelas_id', $request->kelas_id); });
            }
            if ($request->filled('jurusan_id')) {
                $query->whereHas('siswa.kelas', function($q) use ($request) { $q->where('jurusan_id', $request->jurusan_id); });
            }
            // Tarik semua data tanpa paginate
            $data = $query->orderBy('jatuh_tempo', 'asc')->get();
        }

        $bulanMulai = $request->tanggal_awal;
        $bulanSelesai = $request->tanggal_akhir;

        // KITA PINJAM VIEW PRINT MILIK ADMIN! (Lebih hemat kode)
        return view('admin.laporan.print', compact('data', 'jenis', 'judulLaporan', 'bulanMulai', 'bulanSelesai'));
    }
}