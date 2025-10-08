<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;

/**
 * Controller responsible for displaying the operator dashboard.
 *
 * The operator dashboard shares the same underlying data as the
 * administrative dashboard but is namespaced separately. This
 * separation allows us to present a distinct view to users with the
 * `operator` role and prevents accidental access to admin routes.
 */
class DashboardController extends Controller
{
    /**
     * Show the dashboard view for operators.
     */
    public function index()
    {
        // Compute basic statistics for the operator dashboard
        $totalProduk    = \App\Models\Produk::count();
        $totalKategori  = \App\Models\Kategori::count();
        $totalUser      = \App\Models\User::count();
        $totalTransaksi = \App\Models\Transaksi::count();

        // Prepare chart data: labels = product names, stockData = current stock,
        // soldData = total quantity sold across all transactions
        $labels    = [];
        $stockData = [];
        $soldData  = [];
        $produkAll = \App\Models\Produk::all();
        foreach ($produkAll as $p) {
            $labels[]    = $p->name;
            $stockData[] = $p->stok ?? 0;
            $soldQty     = \App\Models\TransaksiDetail::where('produk_id', $p->id)->sum('qty');
            $soldData[]  = $soldQty;
        }

        $data = [
            'title'          => 'Dashboard Operator',
            'totalProduk'    => $totalProduk,
            'totalKategori'  => $totalKategori,
            'totalUser'      => $totalUser,
            'totalTransaksi' => $totalTransaksi,
            'labels'         => $labels,
            'stockData'      => $stockData,
            'soldData'       => $soldData,
            'content'        => 'operator/dashboard/index',
        ];
        return view('operator.layouts.wrapper', $data);
    }
}