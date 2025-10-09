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
        Schema::create('stok_histories', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel produk
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            // Jumlah stok yang berubah
            $table->integer('qty');
            // Tipe pergerakan: 'in' untuk stok masuk, 'out' untuk stok keluar
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_histories');
    }
};
