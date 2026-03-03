<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fix 1: Tambah enum values ke peminjaman.status
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('pending','disetujui','ditolak','dipinjam','dikembalikan','diajukan_kembali','dibatalkan') NOT NULL DEFAULT 'pending'");

        // Fix 2: Tambah kolom denda_status ke pengembalian untuk tracking pembayaran denda
        if (!Schema::hasColumn('pengembalian', 'denda_status')) {
            Schema::table('pengembalian', function (Blueprint $table) {
                $table->enum('denda_status', ['belum_bayar', 'lunas'])->default('belum_bayar')->after('denda');
                $table->enum('metode_bayar', ['cash', 'qris'])->nullable()->after('denda_status');
                $table->timestamp('tanggal_bayar')->nullable()->after('metode_bayar');
            });
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('pending','disetujui','ditolak','dipinjam','dikembalikan') NOT NULL DEFAULT 'pending'");

        Schema::table('pengembalian', function (Blueprint $table) {
            $table->dropColumn(['denda_status', 'metode_bayar', 'tanggal_bayar']);
        });
    }
};
