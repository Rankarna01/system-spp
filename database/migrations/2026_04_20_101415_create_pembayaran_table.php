<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_id')->constrained('tagihan')->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->integer('gross_amount');
            $table->string('payment_type')->default('bank_transfer'); 
            $table->string('bank'); 
            $table->string('va_number')->nullable(); 
            $table->enum('status', ['belum_bayar', 'menunggu', 'lunas', 'gagal', 'kadaluarsa'])->default('belum_bayar');
            $table->timestamp('waktu_transaksi')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pembayaran'); }
};
