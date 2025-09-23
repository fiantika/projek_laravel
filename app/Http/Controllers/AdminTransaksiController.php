<?php

namespace App\Http\Controllers;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use App\Models\User;

class AdminTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Manajemen Transaksi', 
            'transaksi' => Transaksi::with('user')->orderBy('created_at', 'DESC')->paginate(10),
            'content' => 'admin/transaksi/index'
        ];
        return view('admin.layouts.wrapper', $data); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Validasi user login
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $data = [
            'user_id' => auth()->user()->id,
            'kasir_name' => auth()->user()->name,
            'total' => 0,
            'status' => 'pending'
        ];
        
        $transaksi = Transaksi::create($data);
        return redirect('/admin/transaksi/'.$transaksi->id.'/edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Method untuk future development
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::with(['user', 'details.produk'])->findOrFail($id);

        $data = [
            'title' => 'Detail Transaksi #' . $transaksi->id,
            'transaksi' => $transaksi,
            'content' => 'admin.transaksi.show'
        ];

        return view('admin.layouts.wrapper', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produk = Produk::where('
        ', '>', 0)->get();
        
        $produk_id = request('produk_id');
        $act = request('act');
        $qty = request('qty');

        $p_detail = null;
        $subtotal = 0;

        if ($produk_id) {
            $p_detail = Produk::find($produk_id);

            if ($p_detail) {
                $qty = $qty ? (int)$qty : 1;

                if ($act == 'min') {
                    $qty = max(1, $qty - 1);
                } else if ($act == 'plus') {
                    $qty = $qty + 1;
                }

                // Validasi stok
                if ($qty > $p_detail->stok) {
                    $qty = $p_detail->stok;
                }

                $subtotal = $qty * $p_detail->harga;
            }
        } else {
            $qty = 1;
        }

        $transaksi = Transaksi::findOrFail($id);
        
        // Validasi ownership
        if ($transaksi->user_id != auth()->user()->id && auth()->user()->role != 'admin') {
            return redirect('/admin/transaksi')->with('error', 'Akses ditolak');
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
            'content' => 'admin/transaksi/create',
        ];

        return view('admin.layouts.wrapper', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Method untuk future development
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        // Validasi ownership
        if ($transaksi->user_id != auth()->user()->id && auth()->user()->role != 'admin') {
            return redirect('/admin/transaksi')->with('error', 'Akses ditolak');
        }

        // Hapus detail transaksi terlebih dahulu
        TransaksiDetail::where('transaksi_id', $id)->delete();
        
        // Hapus transaksi
        $transaksi->delete();
        
        return redirect('/admin/transaksi')->with('success', 'Transaksi berhasil dihapus');
    }
}
