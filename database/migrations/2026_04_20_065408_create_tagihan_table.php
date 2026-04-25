<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('spp_master_id')->constrained('spp_master')->onDelete('cascade');
            $table->string('bulan'); // Menyimpan nama bulan agar mudah (Januari, Feb dll)
            $table->string('tahun');
            $table->integer('nominal');
            $table->date('jatuh_tempo');
            $table->enum('status', ['belum_bayar', 'lunas', 'menunggak'])->default('belum_bayar');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tagihan'); }
};
