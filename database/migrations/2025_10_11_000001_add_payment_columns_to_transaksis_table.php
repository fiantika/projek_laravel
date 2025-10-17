<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Tambahkan kolom dibayarkan dan kembalian untuk mencatat
            // pembayaran pada transaksi. Kolom dibuat nullable agar
            // transaksi lama tetap valid sampai pembayaran dilakukan.
            $table->bigInteger('dibayarkan')->nullable()->after('status');
            $table->bigInteger('kembalian')->nullable()->after('dibayarkan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['dibayarkan', 'kembalian']);
        });
    }
};