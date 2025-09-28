<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
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
        // Load transactions along with the user and detail relationships.
        // Including the details here allows the index view to display
        // product names and quantities without executing additional
        // queries per row. This also ensures eager loading of related
        // models, preventing the N+1 query problem when iterating in
        // the Blade template.
        $data = [
            'title' => 'Manajemen Transaksi',
            'transaksi' => Transaksi::with(['user', 'details'])->orderBy('created_at', 'DESC')->paginate(10),
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
        // Capture payment amount if provided and persist it on the transaction.
        $dibayarkan = request('dibayarkan');
        if ($dibayarkan !== null) {
            // Cast to integer to avoid string arithmetic. Only update when
            // an explicit value is provided in the request.
            $transaksi->dibayarkan = (int) $dibayarkan;
            $transaksi->save();
        }
        // Compute change (kembalian). If dibayarkan is not set, it will
        // default to zero. Never display negative change.
        $paid = $transaksi->dibayarkan ?? 0;
        $kembalian = $paid - $transaksi->total;
        if ($kembalian < 0) {
            $kembalian = 0;
        }

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
            'dibayarkan' => $transaksi->dibayarkan,
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

        // Recalculate the subtotal on the server to avoid trusting
        // potentially manipulated client data. Always multiply the
        // quantity by the product's price.
        $subtotal = $produk->harga * $qty;

        if (!$td) {
            $data = [
                'produk_id' => $produk_id,
                'produk_name' => $request->produk_name,
                'transaksi_id' => $transaksi_id,
                'qty' => $qty,
                'subtotal' => $subtotal,
            ];
            TransaksiDetail::create($data);
        } else {
            $new_qty = $td->qty + $qty;
            $td->update([
                'qty' => $new_qty,
                'subtotal' => $td->subtotal + $subtotal,
            ]);
        }
        // Update the total on the transaction with the newly calculated subtotal
        $transaksi->update([
            'total' => $transaksi->total + $subtotal,
        ]);
        // Reduce stock if exists
        if (isset($produk->stok)) {
            $produk->update([
                'stok' => $produk->stok - $qty,
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
    public function complete($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $detailCount = TransaksiDetail::where('transaksi_id', $id)->count();
        if ($detailCount === 0) {
            return redirect()->back()->with('error', 'Transaksi kosong, tambahkan produk terlebih dahulu');
        }
        // Ensure payment has been made and is sufficient. If not, prevent
        // completion to avoid pending transactions with unpaid totals.
        if ($transaksi->dibayarkan < $transaksi->total) {
            return redirect()->back()->with('error', 'Pembayaran belum cukup. Harap input jumlah yang dibayarkan.');
        }
        $transaksi->update([
            'status' => 'selesai',
        ]);
        return redirect('/kasir/transaksi')->with('success', 'Transaksi berhasil diselesaikan');
    }
}