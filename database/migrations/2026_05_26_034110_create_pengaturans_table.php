<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sistem')->default('SPP Digital');
            $table->string('slogan_sistem')->default('Management System');
            $table->string('logo')->nullable();
            $table->string('telepon_sekolah')->nullable();
            $table->string('email_sekolah')->nullable();
            $table->text('alamat_sekolah')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};