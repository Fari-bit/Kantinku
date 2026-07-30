<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjual_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_warung');
            $table->string('deskripsi')->nullable();
            $table->string('foto_warung')->nullable();
            $table->string('nomor_stand')->nullable();
            $table->boolean('is_open')->default(true);
            $table->time('jam_buka')->default('07:00');
            $table->time('jam_tutup')->default('14:00');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjual_profiles');
    }
};
