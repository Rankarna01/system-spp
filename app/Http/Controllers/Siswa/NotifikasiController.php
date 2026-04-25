<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua notifikasi milik user ini
        $notifikasi = Notifikasi::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Fitur Auto-Read: Ubah status notifikasi yang belum dibaca menjadi sudah dibaca
        Notifikasi::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('siswa.notifikasi.index', compact('notifikasi'));
    }
}