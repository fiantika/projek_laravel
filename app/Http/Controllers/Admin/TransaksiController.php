<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\StokHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TransaksiController extends Controller
{
    /**
     * Display a listing of completed transactions.
     */
    public function index()
    {
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

        $transaksi = Transaksi::create([
            'user_id' => auth()->id(),
            'kasir_name' => auth()->user()->name,
            'total' => 0,
            'status' => 'pending',
        ]);

        return redirect('/kasir/transaksi/' . $transaksi->id . '/edit');
    }

    /**
     * Show the details of a transaction.
     */
    public function show($id)
    {
        $transaksi = Transaksi::with(['user', 'details.produk'])->findOrFail($id);

        return view('kasir.layouts.wrapper', [
            'title' => 'Detail Transaksi #' . $transaksi->id,
            'transaksi' => $transaksi,
            'content' => 'kasir/transaksi/show',
        ]);
    }

    /**
     * Edit the specified transaction (add/remove items, compute totals).
     */
    public function edit($id)
    {
        // Ambil semua produk
        $produkQuery = Produk::query();
        try {
            if (Schema::hasColumn('produks', 'stok')) {
                $produkQuery->where('stok', '>', 0);
            }
        } catch (\Throwable $e) {
            // Abaikan jika cek schema gagal
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

                if ($act == 'min') $qty = max(1, $qty - 1);
                elseif ($act == 'plus') $qty += 1;

                if (isset($p_detail->stok) && $qty > $p_detail->stok) {
                    $qty = $p_detail->stok;
                }

                $subtotal = $qty * $p_detail->harga;
            }
        } else {
            $qty = 1;
        }

        $transaksi = Transaksi::findOrFail($id);

        // Ownership check
        if ($transaksi->user_id != auth()->id() && auth()->user()->role != 'keuangan') {
            return redirect('/kasir/transaksi')->with('error', 'Akses ditolak');
        }

        $dibayarkan = request('dibayarkan', 0);
        $kembalian = $dibayarkan - $transaksi->total;
        $transaksi_detail = TransaksiDetail::where('transaksi_id', $id)->get();

        return view('kasir.layouts.wrapper', [
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
        ]);
    }

    /**
     * Remove the specified transaction.
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->user_id != auth()->id() && auth()->user()->role != 'keuangan') {
            return redirect('/kasir/transaksi')->with('error', 'Akses ditolak');
        }

        TransaksiDetail::where('transaksi_id', $id)->delete();
        $transaksi->delete();

        return redirect('/kasir/transaksi')->with('success', 'Transaksi berhasil dihapus');
    }

    /**
     * Cancel a pending transaction.
     */
    public function cancel($id)
    {
        return $this->destroy($id);
    }

    /**
     * Add a detail item to a transaction.
     */
    public function addDetail(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'transaksi_id' => 'required|exists:transaksis,id',
            'qty' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        $transaksi = Transaksi::findOrFail($request->transaksi_id);

        if (isset($produk->stok) && $produk->stok < $request->qty) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $produk->stok);
        }

        $td = TransaksiDetail::where('produk_id', $produk->id)
            ->where('transaksi_id', $transaksi->id)
            ->first();

        if (!$td) {
            TransaksiDetail::create([
                'produk_id' => $produk->id,
                'produk_name' => $request->produk_name,
                'transaksi_id' => $transaksi->id,
                'qty' => $request->qty,
                'subtotal' => $request->subtotal,
            ]);
        } else {
            $td->update([
                'qty' => $td->qty + $request->qty,
                'subtotal' => $td->subtotal + $request->subtotal,
            ]);
        }

        $transaksi->update(['total' => $transaksi->total + $request->subtotal]);

        return redirect('/kasir/transaksi/' . $transaksi->id . '/edit')->with('success', 'Item berhasil ditambahkan');
    }

    /**
     * Remove a detail item from a transaction.
     */
    public function removeDetail(Request $request)
    {
        $td = TransaksiDetail::findOrFail($request->id);
        $transaksi = Transaksi::findOrFail($td->transaksi_id);

        $transaksi->update(['total' => $transaksi->total - $td->subtotal]);
        $td->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus');
    }

    /**
     * Mark a transaction as completed (validasi bayar, update stok, hitung kembalian).
     */
    public function complete(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $detailCount = TransaksiDetail::where('transaksi_id', $id)->count();

        if ($detailCount === 0) {
            return redirect()->back()->with('error', 'Transaksi kosong, tambahkan produk terlebih dahulu');
        }

        $bayar = $request->input('bayar');

        if ($bayar === null || $bayar === '') {
            return redirect()->back()->with('error', 'Transaksi belum dibayar');
        }

        if ((float)$bayar < (float)$transaksi->total) {
            return redirect()->back()->with('error', 'Uang yang dibayarkan kurang dari total belanja');
        }

        $kembalian = (float)$bayar - (float)$transaksi->total;

        // Update stok dan log history
        $details = TransaksiDetail::where('transaksi_id', $id)->get();
        foreach ($details as $detail) {
            $produk = Produk::find($detail->produk_id);
            if ($produk && isset($produk->stok)) {
                $produk->update(['stok' => $produk->stok - $detail->qty]);

                StokHistory::create([
                    'produk_id' => $produk->id,
                    'qty' => $detail->qty,
                    'type' => 'out',
                ]);
            }
        }

        $transaksi->update([
            'status' => 'selesai',
            'dibayarkan' => $bayar,
            'kembalian' => $kembalian,
        ]);

        return redirect()->route('kasir.transaksi.index')->with('swal', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Transaksi telah diselesaikan.'
        ]);
    }
}
