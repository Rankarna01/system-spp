<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk Admin
     */
    public function index()
    {
        // Catatan: Nanti di sini kita akan menambahkan query database 
        // untuk mengambil data KPI seperti total siswa, total pemasukan, 
        // jumlah tunggakan, dll untuk dikirim ke view.
        
        // Untuk saat ini, kita return view-nya terlebih dahulu
        return view('admin.dashboard.index');
    }
}