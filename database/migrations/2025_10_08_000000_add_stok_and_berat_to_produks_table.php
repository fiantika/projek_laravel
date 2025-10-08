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
        Schema::table('produks', function (Blueprint $table) {
            if (!Schema::hasColumn('produks', 'stok')) {
                $table->integer('stok')->default(0)->after('harga');
            }
            if (!Schema::hasColumn('produks', 'berat')) {
                $table->decimal('berat', 8, 2)->nullable()->after('stok');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            if (Schema::hasColumn('produks', 'berat')) {
                $table->dropColumn('berat');
            }
            if (Schema::hasColumn('produks', 'stok')) {
                $table->dropColumn('stok');
            }
        });
    }
};
