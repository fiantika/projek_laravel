<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk menyimpan riwayat pergerakan stok.
 *
 * Setiap penambahan atau pengurangan stok dicatat di tabel ini agar
 * dashboard operator dapat menampilkan tren stok masuk dan keluar per hari.
 */
class StokHistory extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'produk_id',
        'qty',
        'type',
    ];

    /**
     * Relasi ke model Produk.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
