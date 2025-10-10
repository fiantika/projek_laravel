<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\StokHistory;
use Illuminate\Http\Request;

/**
 * Controller for managing transactions and their details.
 *
 * This controller combines the responsibilities of the former
 * `AdminTransaksiController` and `AdminTransaksiDetailController`. It has
 * been adapted for the cashier prefix (`/kasir`) and uses the kasir
 * layout and view paths. Only the transaction owner or users with the
 * `keuangan` role are allowed to edit or delete a transaction.
 */
class TransaksiController extends Controller
{
    /**
     * Display a listing of the transactions.
     */
    public function index()
    {
        // Only display completed transactions on the listing. Pending drafts
        // should not appear in the table so that unfinished transactions do
        // not clutter the list. Transactions with status "pending" are
        // therefore filtered out here.
        $data = [
            'title' => 'Manajemen Transaksi',
            'transaksi' => Transaksi::with('user')
                ->where('status', 'selesai')
                ->orderBy('created_at', 'DESC')
                ->paginate(10),
            'content' => 'kasir/transaksi/index',
        ];
        return view('kasir.layouts.wrapper', $data);
    }

    /**
     * Create a new transaction and redirect to the edit form.
     */
    public function create()
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $data = [
            'user_id' => auth()->id(),
            'kasir_name' => auth()->user()->name,
            'total' => 0,
            'status' => 'pending',
        ];
        $transaksi = Transaksi::create($data);
        return redirect('/kasir/transaksi/' . $transaksi->id . '/edit');
    }

    /**
     * Show the details of a transaction.
     */
    public function show($id)
    {
        $transaksi = Transaksi::with(['user', 'details.produk'])->findOrFail($id);
        $data = [
            'title' => 'Detail Transaksi #' . $transaksi->id,
            'transaksi' => $transaksi,
            'content' => 'kasir/transaksi/show',
        ];
        return view('kasir.layouts.wrapper', $data);
    }

    /**
     * Edit the specified transaction (add/remove items, compute totals).
     */
    public function edit($id)
    {
        // Fetch all products. If stock management is present, filter by stok>0.
        $produkQuery = Produk::query();
        try {
            if (\Schema::hasColumn('produks', 'stok')) {
                $produkQuery->where('stok', '>', 0);
            }
        } catch (\Throwable $e) {
            // Ignore if schema lookup fails
        }
        $produk = $produkQuery->get();
        $produk_id = request('produk_id');
        $act = request('act');
        $qty = request('qty');
        $p_detail = null;
        $subtotal = 0;
        if ($produk_id) {
            $p_detail = Produk::find($produk_id);
            if ($p_detail) {
                $qty = $qty ? (int) $qty : 1;
                if ($act == 'min') {
                    $qty = max(1, $qty - 1);
                } elseif ($act == 'plus') {
                    $qty = $qty + 1;
                }
                // If stok exists and qty exceeds it, cap the qty
                if (isset($p_detail->stok) && $qty > $p_detail->stok) {
                    $qty = $p_detail->stok;
                }
                $subtotal = $qty * $p_detail->harga;
            }
        } else {
            $qty = 1;
        }
        $transaksi = Transaksi::findOrFail($id);
        // Ownership check: allow owners or admin (role keuangan) to edit
        if ($transaksi->user_id != auth()->id() && auth()->user()->role != 'keuangan') {
            return redirect('/kasir/transaksi')->with('error', 'Akses ditolak');
        }
        $dibayarkan = request('dibayarkan', 0);
        $kembalian = $dibayarkan - $transaksi->total;
        $transaksi_detail = TransaksiDetail::where('transaksi_id', $id)->get();
        $data = [
            'title' => 'Tambah Transaksi #' . $id,
            'produk' => $produk,
            'p_detail' => $p_detail,
            'qty' => $qty,
            'subtotal' => $subtotal,
            'transaksi_id' => $id,
            'transaksi_detail' => $transaksi_detail,
            'transaksi' => $transaksi,
            'kembalian' => $kembalian,
            'dibayarkan' => $dibayarkan,
            'content' => 'kasir/transaksi/create',
        ];
        return view('kasir.layouts.wrapper', $data);
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        // Only owners or admin (keuangan) can delete
        if ($transaksi->user_id != auth()->id() && auth()->user()->role != 'keuangan') {
            return redirect('/kasir/transaksi')->with('error', 'Akses ditolak');
        }
        // Delete all details first and restore stock if applicable
        $details = TransaksiDetail::where('transaksi_id', $id)->get();
        foreach ($details as $detail) {
            $produk = Produk::find($detail->produk_id);
            if ($produk && isset($produk->stok)) {
                $produk->update([
                    'stok' => $produk->stok + $detail->qty,
                ]);
                // Catat stok masuk karena transaksi dibatalkan
                StokHistory::create([
                    'produk_id' => $produk->id,
                    'qty' => $detail->qty,
                    'type' => 'in',
                ]);
            }
            $detail->delete();
        }
        $transaksi->delete();
        return redirect('/kasir/transaksi')->with('success', 'Transaksi berhasil dihapus');
    }

    /**
     * Add an item to the specified transaction.
     */
    public function addDetail(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'transaksi_id' => 'required|exists:transaksis,id',
            'qty' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
        ]);
        $produk_id = $request->produk_id;
        $transaksi_id = $request->transaksi_id;
        $qty = $request->qty;
        $produk = Produk::findOrFail($produk_id);
        // Check stock if exists
        if (isset($produk->stok) && $produk->stok < $qty) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $produk->stok);
        }
        $td = TransaksiDetail::where('produk_id', $produk_id)
            ->where('transaksi_id', $transaksi_id)
            ->first();
        $transaksi = Transaksi::findOrFail($transaksi_id);
        if (!$td) {
            $data = [
                'produk_id' => $produk_id,
                'produk_name' => $request->produk_name,
                'transaksi_id' => $transaksi_id,
                'qty' => $qty,
                'subtotal' => $request->subtotal,
            ];
            TransaksiDetail::create($data);
        } else {
            $new_qty = $td->qty + $qty;
            $td->update([
                'qty' => $new_qty,
                'subtotal' => $request->subtotal + $td->subtotal,
            ]);
        }
        // Update total on transaction
        $transaksi->update([
            'total' => $transaksi->total + $request->subtotal,
        ]);
        // Reduce stock if exists
        if (isset($produk->stok)) {
            $produk->update([
                'stok' => $produk->stok - $qty,
            ]);
            // Catat stok keluar
            StokHistory::create([
                'produk_id' => $produk->id,
                'qty' => $qty,
                'type' => 'out',
            ]);
        }
        return redirect('/kasir/transaksi/' . $transaksi_id . '/edit')->with('success', 'Item berhasil ditambahkan');
    }

    /**
     * Remove a detail item from a transaction.
     */
    public function removeDetail(Request $request)
    {
        $id = $request->id;
        $td = TransaksiDetail::findOrFail($id);
        $transaksi = Transaksi::findOrFail($td->transaksi_id);
        $produk = Produk::find($td->produk_id);
        if ($produk && isset($produk->stok)) {
            $produk->update([
                'stok' => $produk->stok + $td->qty,
            ]);
            // Catat stok masuk karena item dihapus dari transaksi
            StokHistory::create([
                'produk_id' => $produk->id,
                'qty' => $td->qty,
                'type' => 'in',
            ]);
        }
        $transaksi->update([
            'total' => $transaksi->total - $td->subtotal,
        ]);
        $td->delete();
        return redirect()->back()->with('success', 'Item berhasil dihapus');
    }

    /**
     * Mark a transaction as completed.
     */
    public function complete(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        // Pastikan transaksi memiliki item sebelum dapat diselesaikan.
        $detailCount = TransaksiDetail::where('transaksi_id', $id)->count();
        if ($detailCount === 0) {
            return redirect()->back()->with('error', 'Transaksi kosong, tambahkan produk terlebih dahulu');
        }
        // Ambil nominal pembayaran dari request. Parameter ini dikirim dari
        // halaman edit melalui query string (?bayar=) atau form. Nilai
        // default null menunjukkan belum ada pembayaran.
        $bayar = $request->input('bayar');
        // Jika nominal pembayaran tidak diisi, transaksi tidak boleh
        // diselesaikan.
        if ($bayar === null || $bayar === '') {
            return redirect()->back()->with('error', 'Transaksi belum dibayar');
        }
        // Jika jumlah uang yang dibayarkan kurang dari total belanja,
        // jangan izinkan transaksi disimpan. Tampilkan pesan kesalahan.
        if ((float) $bayar < (float) $transaksi->total) {
            return redirect()->back()->with('error', 'Uang yang dibayarkan kurang dari total belanja');
        }
        // Tandai transaksi selesai dan simpan nominal bayar serta kembalian
        // jika kolom tersebut tersedia pada tabel. Jika kolom tidak
        // tersedia, nilai ini diabaikan oleh Eloquent (unguarded fields).
        // Tandai transaksi selesai. Nilai dibayarkan dan kembalian hanya
        // digunakan untuk validasi dan tidak disimpan ke tabel karena
        // kolom tersebut tidak tersedia pada skema transaksis.
        $transaksi->update([
            'status' => 'selesai',
        ]);
        return redirect('/kasir/transaksi')->with('success', 'Transaksi berhasil diselesaikan');
    }

    /**
     * Batalkan transaksi yang belum selesai (status pending).
     * Menghapus transaksi dan mengembalikan stok seperti pada destroy().
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($id)
    {
        return $this->destroy($id);
    }
}