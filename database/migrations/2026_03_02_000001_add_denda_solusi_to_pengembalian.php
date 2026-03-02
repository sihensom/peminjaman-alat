<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->unsignedBigInteger('denda')->default(0)->after('received_by')->comment('Denda dalam Rupiah');
            $table->string('solusi', 100)->nullable()->after('denda')->comment('Solusi untuk alat rusak/hilang');
        });
    }

    public function down(): void
    {
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->dropColumn(['denda', 'solusi']);
        });
    }
};
