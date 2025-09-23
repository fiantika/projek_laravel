<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use App\Models\Produk;

class AdminTransaksiDetailController extends Controller
{
    public function create(Request $request)
    {
        // Validasi input
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'transaksi_id' => 'required|exists:transaksi,id',
            'qty' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $produk_id = $request->produk_id;
        $transaksi_id = $request->transaksi_id;
        $qty = $request->qty;

        // Cek stok produk
        $produk = Produk::findOrFail($produk_id);
        if ($produk->stok < $qty) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $produk->stok);
        }

        $td = TransaksiDetail::whereProdukId($produk_id)->whereTransaksiId($transaksi_id)->first();
        $transaksi = Transaksi::findOrFail($transaksi_id);

        if ($td == null) {
            // Tambah item baru
            $data = [
                'produk_id' => $produk_id,
                'produk_name' => $request->produk_name,
                'transaksi_id' => $transaksi_id,
                'qty' => $qty,
                'subtotal' => $request->subtotal,
            ];
            TransaksiDetail::create($data);

            // Update total transaksi
            $transaksi->update([
                'total' => $request->subtotal + $transaksi->total
            ]);

            // Kurangi stok
            $produk->update([
                'stok' => $produk->stok - $qty
            ]);
        } else {
            // Update item yang sudah ada
            $new_qty = $td->qty + $qty;
            
            // Cek stok lagi untuk qty baru
            if ($produk->stok < $qty) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi untuk penambahan qty');
            }

            $td->update([
                'qty' => $new_qty,
                'subtotal' => $request->subtotal + $td->subtotal,
            ]);

            // Update total transaksi
            $transaksi->update([
                'total' => $request->subtotal + $transaksi->total
            ]);

            // Kurangi stok
            $produk->update([
                'stok' => $produk->stok - $qty
            ]);
        }

        return redirect('/admin/transaksi/' . $transaksi_id . '/edit')->with('success', 'Item berhasil ditambahkan');
    }

    public function delete()
    {
        $id = request('id');
        $td = TransaksiDetail::findOrFail($id);

        $transaksi = Transaksi::findOrFail($td->transaksi_id);
        
        // Kembalikan stok
        $produk = Produk::findOrFail($td->produk_id);
        $produk->update([
            'stok' => $produk->stok + $td->qty
        ]);

        // Update total transaksi
        $transaksi->update([
            'total' => $transaksi->total - $td->subtotal,
        ]);

        // Hapus detail
        $td->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus');
    }

    public function done($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        // Validasi transaksi tidak kosong
        $detail_count = TransaksiDetail::where('transaksi_id', $id)->count();
        if ($detail_count == 0) {
            return redirect()->back()->with('error', 'Transaksi kosong, tambahkan produk terlebih dahulu');
        }

        $transaksi->update([
            'status' => 'selesai'
        ]);

        return redirect('/admin/transaksi')->with('success', 'Transaksi berhasil diselesaikan');
    }
}
