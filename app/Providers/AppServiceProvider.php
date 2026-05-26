<?php

namespace App\Providers;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
{
    // Cek agar tidak error saat menjalankan php artisan migrate di awal
    if (Schema::hasTable('pengaturan')) {
        // Ambil data pertama, jika kosong otomatis buat data default awal
        $setting = Pengaturan::firstOrCreate(['id' => 1], [
            'nama_sistem' => 'SPP Digital',
            'slogan_sistem' => 'Management System',
            'telepon_sekolah' => '081234567890',
            'email_sekolah' => 'info@sekolah.sch.id',
            'alamat_sekolah' => 'Jl. Pendidikan No. 1'
        ]);
        
        // Bagikan ke seluruh file blade dengan nama variabel $gSetting
        View::share('gSetting', $setting);
    }
}
}
