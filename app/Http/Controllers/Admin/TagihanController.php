<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SppMaster;
use App\Models\Tagihan;
use App\Models\GenerateLog;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagihanController extends Controller
{
    // List Nama Bulan Helper
    private $namaBulan = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];

    public function index() {
        $activeTahun = TahunAjaran::where('is_active', 1)->first();
        if(!$activeTahun) {
            return back()->with('error', 'Tidak ada Tahun Ajaran yang aktif! Silakan aktifkan di Master Data.');
        }

        $masters = SppMaster::with('tahunAjaran')->where('tahun_ajaran_id', $activeTahun->id)->latest()->get();
        $kelas = Kelas::all();
        $logs = GenerateLog::with('kelas')->latest()->take(10)->get(); // Ambil 10 log terakhir
        
        // Cek total tagihan yg sudah digenerate bulan ini (berdasarkan master terakhir)
        $totalTagihan = Tagihan::count();

        return view('admin.manajemen-tagihan.index', compact('masters', 'kelas', 'logs', 'activeTahun', 'totalTagihan'));
    }

    // 1. Simpan Template / Master Tagihan
    public function storeMaster(Request $request) {
        $request->validate([
            'nominal' => 'required|numeric',
            'bulan' => 'required|numeric|min:1|max:12',
            'tanggal_jatuh_tempo' => 'required|date'
        ]);

        $activeTahun = TahunAjaran::where('is_active', 1)->first();

        // Cek apakah master bulan ini sudah ada
        $cek = SppMaster::where('tahun_ajaran_id', $activeTahun->id)->where('bulan', $request->bulan)->first();
        if($cek) return back()->with('error', 'Master SPP untuk bulan tersebut sudah ada di Tahun Ajaran ini!');

        SppMaster::create([
            'tahun_ajaran_id' => $activeTahun->id,
            'nominal' => $request->nominal,
            'bulan' => $request->bulan,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo
        ]);

        return back()->with('success', 'Master SPP berhasil dibuat!');
    }

    // 2. Mesin Bulk Generate Tagihan
    public function generate(Request $request) {
        $request->validate([
            'spp_master_id' => 'required',
            'tipe' => 'required|in:semua,kelas',
            'kelas_id' => 'required_if:tipe,kelas'
        ]);

        $master = SppMaster::with('tahunAjaran')->findOrFail($request->spp_master_id);
        $namaBulan = $this->namaBulan[$master->bulan];
        $tahunString = explode('/', $master->tahunAjaran->tahun)[0]; // Ambil tahun depannya saja dari 2024/2025

        // Mengambil data siswa sesuai filter
        $querySiswa = Siswa::where('tahun_ajaran_id', $master->tahun_ajaran_id);
        if ($request->tipe === 'kelas') {
            $querySiswa->where('kelas_id', $request->kelas_id);
        }
        $siswas = $querySiswa->get();

        if ($siswas->isEmpty()) return back()->with('error', 'Tidak ada data siswa ditemukan untuk kriteria tersebut.');

        $countGenerated = 0;
        
        // Mulai Transaksi Database agar aman
        DB::beginTransaction();
        try {
            foreach ($siswas as $siswa) {
                // firstOrCreate untuk mencegah duplikat tagihan pada siswa, master_spp, dan bulan yang sama
                $tagihan = Tagihan::firstOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'spp_master_id' => $master->id,
                    ],
                    [
                        'bulan' => $namaBulan,
                        'tahun' => $tahunString,
                        'nominal' => $master->nominal,
                        'jatuh_tempo' => $master->tanggal_jatuh_tempo,
                        'status' => 'belum_bayar'
                    ]
                );
                
                // Jika data benar-benar baru dibuat (bukan ngambil data lama yg sudah ada)
                if ($tagihan->wasRecentlyCreated) { $countGenerated++; }
            }

            // Catat Log
            $namaKelas = $request->tipe === 'kelas' ? Kelas::find($request->kelas_id)->nama_kelas : 'Semua Kelas';
            GenerateLog::create([
                'tipe' => $request->tipe,
                'kelas_id' => $request->tipe === 'kelas' ? $request->kelas_id : null,
                'keterangan' => "Berhasil generate $countGenerated tagihan untuk bulan $namaBulan ($namaKelas)."
            ]);

            DB::commit(); // Selesaikan transaksi
            return back()->with('success', "Berhasil men-generate $countGenerated tagihan baru. Siswa yang sudah memiliki tagihan ini di-skip.");

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika error
            return back()->with('error', 'Terjadi kesalahan sistem saat generate tagihan: ' . $e->getMessage());
        }
    }

    public function destroyMaster($id) {
        try {
            $master = SppMaster::findOrFail($id);
            
            // Hapus semua tagihan yang terkait dengan master ini terlebih dahulu
            Tagihan::where('spp_master_id', $master->id)->delete();
            
            // Hapus master SPP
            $master->delete();
            
            return back()->with('success', 'Master SPP beserta tagihan terkait berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus master SPP: ' . $e->getMessage());
        }
    }
}