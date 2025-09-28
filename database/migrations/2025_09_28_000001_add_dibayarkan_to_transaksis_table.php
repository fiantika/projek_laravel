<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to add the `dibayarkan` column on the `transaksis` table.
 *
 * This column stores the amount of money paid by the customer for the
 * transaction. Storing the value as a big integer avoids floating point
 * rounding issues when dealing with currency. The column defaults to zero
 * for backwards compatibility with existing rows.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksis', 'dibayarkan')) {
                $table->bigInteger('dibayarkan')->default(0)->after('total');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (Schema::hasColumn('transaksis', 'dibayarkan')) {
                $table->dropColumn('dibayarkan');
            }
        });
    }
};