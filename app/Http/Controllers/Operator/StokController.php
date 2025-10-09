<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\StokHistory;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

/**
 * Controller untuk manajemen stok (stok masuk).
 *
 * Operator dapat menambahkan stok baru ke produk melalui halaman ini.
 * Setiap penambahan stok akan dicatat ke tabel stok_histories dengan type 'in'.
 */
class StokController extends Controller
{
    /**
     * Menampilkan form penambahan stok serta riwayat stok.
     */
    public function index()
    {
        $data = [
            'title' => 'Manajemen Stok',
            'produks' => Produk::all(),
            'histories' => StokHistory::with('produk')->orderBy('created_at', 'desc')->paginate(10),
            'content' => 'operator/stok/index',
        ];
        return view('operator.layouts.wrapper', $data);
    }

    /**
     * Menangani penyimpanan penambahan stok.
     */
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'qty' => 'required|integer|min:1',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        // Tambah stok produk
        $produk->stok += $request->qty;
        $produk->save();

        // Catat riwayat stok masuk
        StokHistory::create([
            'produk_id' => $produk->id,
            'qty' => $request->qty,
            'type' => 'in',
        ]);

        Alert::success('Sukses', 'Stok berhasil ditambahkan');
        return redirect()->back();
    }
}
