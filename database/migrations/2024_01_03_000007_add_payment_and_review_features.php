<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom ke pendaftaran_penjual (hanya yang belum ada)
        Schema::table('pendaftaran_penjual', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftaran_penjual', 'password')) {
                $table->string('password')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('pendaftaran_penjual', 'terima_transfer')) {
                $table->boolean('terima_transfer')->default(false)->after('jenis_makanan');
            }
            if (!Schema::hasColumn('pendaftaran_penjual', 'nama_bank')) {
                $table->string('nama_bank')->nullable()->after('terima_transfer');
            }
            if (!Schema::hasColumn('pendaftaran_penjual', 'nomor_rekening')) {
                $table->string('nomor_rekening')->nullable()->after('nama_bank');
            }
            if (!Schema::hasColumn('pendaftaran_penjual', 'nama_pemilik_rekening')) {
                $table->string('nama_pemilik_rekening')->nullable()->after('nomor_rekening');
            }
        });

        // Tambah kolom ke penjual_profiles (hanya yang belum ada)
        Schema::table('penjual_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('penjual_profiles', 'nama_bank')) {
                $table->string('nama_bank')->nullable()->after('foto_warung');
            }
            if (!Schema::hasColumn('penjual_profiles', 'nomor_rekening')) {
                $table->string('nomor_rekening')->nullable()->after('nama_bank');
            }
            if (!Schema::hasColumn('penjual_profiles', 'nama_pemilik_rekening')) {
                $table->string('nama_pemilik_rekening')->nullable()->after('nomor_rekening');
            }
            if (!Schema::hasColumn('penjual_profiles', 'terima_transfer')) {
                $table->boolean('terima_transfer')->default(false)->after('nama_pemilik_rekening');
            }
        });

        // Tambah kolom ke pesanan (hanya yang belum ada)
        Schema::table('pesanan', function (Blueprint $table) {
            if (!Schema::hasColumn('pesanan', 'bukti_transfer')) {
                $table->string('bukti_transfer')->nullable()->after('status_pembayaran');
            }
            if (!Schema::hasColumn('pesanan', 'status_verifikasi_transfer')) {
                $table->enum('status_verifikasi_transfer', ['menunggu','diterima','ditolak'])
                      ->nullable()->after('bukti_transfer');
            }
            if (!Schema::hasColumn('pesanan', 'catatan_verifikasi')) {
                $table->text('catatan_verifikasi')->nullable()->after('status_verifikasi_transfer');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_penjual', function (Blueprint $table) {
            $table->dropColumn(['password','terima_transfer','nama_bank','nomor_rekening','nama_pemilik_rekening']);
        });
        Schema::table('penjual_profiles', function (Blueprint $table) {
            $table->dropColumn(['nama_bank','nomor_rekening','nama_pemilik_rekening','terima_transfer']);
        });
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn(['bukti_transfer','status_verifikasi_transfer','catatan_verifikasi']);
        });
    }
};