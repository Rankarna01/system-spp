<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->text('pesan');
            $table->enum('jenis', ['tagihan_baru', 'reminder', 'info'])->default('info');
            $table->boolean('is_read')->default(false); 
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('notifikasi'); }
};