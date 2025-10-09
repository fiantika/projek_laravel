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

        // Hitung stok masuk dan keluar per hari untuk grafik tren
        $stokHistories = \App\Models\StokHistory::selectRaw('DATE(created_at) as date, type, SUM(qty) as total')
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();
        // Koleksi semua tanggal unik
        $dates = $stokHistories->pluck('date')->unique()->values()->all();
        // Siapkan array nilai default untuk setiap tanggal
        $stokInData = [];
        $stokOutData = [];
        foreach ($dates as $d) {
            // total masuk untuk tanggal ini
            $in = $stokHistories->firstWhere(fn($h) => $h->date == $d && $h->type === 'in');
            $out = $stokHistories->firstWhere(fn($h) => $h->date == $d && $h->type === 'out');
            $stokInData[] = $in ? (int) $in->total : 0;
            $stokOutData[] = $out ? (int) $out->total : 0;
        }

        // Hitung produk terlaris untuk pie chart
        $topProdukData = \App\Models\TransaksiDetail::selectRaw('produk_name, SUM(qty) as total')
            ->groupBy('produk_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        $topProdukLabels = $topProdukData->pluck('produk_name')->all();
        $topProdukQty    = $topProdukData->pluck('total')->map(fn($v) => (int) $v)->all();

        $data = [
            'title'             => 'Dashboard Operator',
            'totalProduk'       => $totalProduk,
            'totalKategori'     => $totalKategori,
            'totalUser'         => $totalUser,
            'totalTransaksi'    => $totalTransaksi,
            'labels'            => $labels,
            'stockData'         => $stockData,
            'soldData'          => $soldData,
            'dates'             => $dates,
            'stokInData'        => $stokInData,
            'stokOutData'       => $stokOutData,
            'topProdukLabels'   => $topProdukLabels,
            'topProdukQty'      => $topProdukQty,
            'content'           => 'operator/dashboard/index',
        ];
        return view('operator.layouts.wrapper', $data);
    }
}