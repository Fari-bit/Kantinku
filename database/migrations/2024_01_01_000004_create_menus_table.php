<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjual_id')->constrained('users')->onDelete('cascade');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 10, 2);
            $table->string('foto')->nullable();
            $table->string('kategori')->default('makanan'); // makanan, minuman, snack
            $table->integer('stok')->default(100);
            $table->boolean('tersedia')->default(true);
            $table->integer('terjual')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('jumlah_rating')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
