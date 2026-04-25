<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifikasiController extends Controller
{
    public function index()
    {
        $riwayat = Notifikasi::with('user')->latest()->take(20)->get();
        return view('admin.notifikasi.index', compact('riwayat'));
    }

    public function blast(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'pesan' => 'required|string',
            'jenis' => 'required|in:tagihan_baru,reminder,info',
            'target' => 'required|in:semua_siswa' 
        ]);

        $siswas = User::where('role', 'siswa')->get();

        if ($siswas->isEmpty()) {
            return back()->with('error', 'Tidak ada akun siswa yang ditemukan untuk dikirimi notifikasi.');
        }

        DB::beginTransaction();
        try {
            $dataInsert = [];
            $now = now();

            foreach ($siswas as $siswa) {
                $dataInsert[] = [
                    'user_id' => $siswa->id,
                    'judul' => $request->judul,
                    'pesan' => $request->pesan,
                    'jenis' => $request->jenis,
                    'is_read' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            Notifikasi::insert($dataInsert);

            DB::commit();
            return back()->with('success', 'Berhasil mengirim notifikasi ke ' . count($siswas) . ' siswa!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim notifikasi: ' . $e->getMessage());
        }
    }
}