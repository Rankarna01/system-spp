<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

// Import Semua Controller Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TagihanController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\PaymentCallbackController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\Admin\AkunSiswaController;
use App\Http\Controllers\Admin\PengaturanController;


// Import Role Siswa PWA Mobile
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
use App\Http\Controllers\Siswa\TagihanController as SiswaTagihan;
use App\Http\Controllers\Siswa\RiwayatController as SiswaRiwayat;
use App\Http\Controllers\Siswa\NotifikasiController as SiswaNotifikasi;
use App\Http\Controllers\Siswa\ProfileController as SiswaProfile;

//Import Role kepsek 
use App\Http\Controllers\Kepsek\DashboardController as KepsekDashboard;
use App\Http\Controllers\Kepsek\LaporanController as KepsekLaporan;


// 1. Pintu Masuk Utama (Pengecekan Otomatis)
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'kepsek') return redirect()->route('kepsek.dashboard');
        if ($role === 'siswa') return redirect()->route('siswa.dashboard');
    }
    return redirect()->route('login');
});

// 2. Route Auth (Tamu)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 3. Route Area Member (Sudah Login)
Route::middleware(['auth'])->group(function () {

    // --- AREA ADMIN ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Manajemen Siswa (Route diringkas jadi 4 baris aja!)
        Route::prefix('manajemen-siswa')->group(function () {
            Route::resource('jurusan', JurusanController::class)->except(['create', 'edit', 'show']);
            Route::resource('akun', AkunSiswaController::class)->except(['create', 'edit', 'show']);
            Route::resource('kelas', KelasController::class)->except(['create', 'edit', 'show']);
            Route::resource('tahun', TahunAjaranController::class)->except(['create', 'edit', 'show']);
            Route::resource('siswa', SiswaController::class)->except(['create', 'edit', 'show']);
        });
        Route::prefix('manajemen-tagihan')->name('tagihan.')->group(function () {
            Route::get('/', [TagihanController::class, 'index'])->name('index');
            Route::post('/master', [TagihanController::class, 'storeMaster'])->name('master.store');
            Route::post('/generate', [TagihanController::class, 'generate'])->name('generate');
            Route::delete('/master/{id}', [TagihanController::class, 'destroyMaster'])->name('master.destroy');
        });

        Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
            Route::get('/', [PembayaranController::class, 'index'])->name('index');
            Route::post('/generate-va/{tagihan_id}', [PembayaranController::class, 'generateVa'])->name('generate_va');
        });

        Route::post('/payment-callback', [PaymentCallbackController::class, 'receive']); //callback langsung unutk nontifikasi bayar digunakan ketika sudah production
        Route::get('/riwayat-transaksi', [RiwayatController::class, 'index'])->name('riwayat.index');
        Route::post('/pembayaran/cek-status/{order_id}', [PembayaranController::class, 'cekStatusMidtrans'])->name('pembayaran.cek_status');

        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::post('/export-pdf', [LaporanController::class, 'exportPdf'])->name('export.pdf');
            Route::post('/export-excel', [LaporanController::class, 'exportExcel'])->name('export.excel');
        });

        Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
            Route::get('/', [NotifikasiController::class, 'index'])->name('index');
            Route::post('/blast', [NotifikasiController::class, 'blast'])->name('blast');
        });

        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan/update', [PengaturanController::class, 'update'])->name('pengaturan.update');
    });

    // --- AREA KEPALA SEKOLAH ---
   Route::middleware(['auth', 'role:kepsek'])->prefix('kepsek')->name('kepsek.')->group(function () {
    Route::get('/dashboard', [KepsekDashboard::class, 'index'])->name('dashboard');
    Route::get('/laporan', [KepsekLaporan::class, 'index'])->name('laporan.index');
    // Tambahkan rute cetak ini:
    Route::get('/laporan/cetak', [KepsekLaporan::class, 'cetak'])->name('laporan.cetak');
    // ...
});

    // --- AREA SISWA ---
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');
    Route::get('/tagihan', [SiswaTagihan::class, 'index'])->name('tagihan.index');
    Route::get('/riwayat', [SiswaRiwayat::class, 'index'])->name('riwayat.index');
    Route::get('/notifikasi', [SiswaNotifikasi::class, 'index'])->name('notifikasi.index');
    Route::get('/profil', [SiswaProfile::class, 'index'])->name('profil.index');
    Route::post('/profil/update-password', [SiswaProfile::class, 'updatePassword'])->name('profil.update_password');
});


});
