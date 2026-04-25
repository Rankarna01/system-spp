<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['tagihan.siswa.kelas', 'tagihan.sppMaster.tahunAjaran']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_id', 'like', "%$search%")
                  ->orWhereHas('tagihan.siswa', function($qSiswa) use ($search) {
                      $qSiswa->where('nama', 'like', "%$search%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('waktu_transaksi', [
                $request->tanggal_awal . ' 00:00:00', 
                $request->tanggal_akhir . ' 23:59:59'
            ]);
        }

        $transaksi = $query->latest('waktu_transaksi')->paginate(15)->withQueryString();

        return view('admin.riwayat.index', compact('transaksi'));
    }
}