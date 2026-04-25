<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('generate_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['semua', 'kelas', 'manual']);
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            $table->string('keterangan');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('generate_logs'); }
};
