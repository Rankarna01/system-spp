<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('spp_master', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->integer('nominal');
            $table->integer('bulan'); // 1-12
            $table->date('tanggal_jatuh_tempo');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('spp_master'); }
};
