<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KasirController extends Controller
{
    //
    public function index()
    {
        // Summarise transactional data for the cashier dashboard. We
        // calculate the number of transactions, the total quantity of
        // items sold, and total revenue for the current day. Recent
        // transactions are also loaded to provide a quick overview of
        // ongoing activity.
        $today = now()->toDateString();
        $totalTransaksi = \App\Models\Transaksi::whereDate('created_at', $today)->count();
        $totalItems = \App\Models\TransaksiDetail::whereDate('created_at', $today)->sum('qty');
        $totalPendapatan = \App\Models\Transaksi::whereDate('created_at', $today)->sum('total');
        $recentTransaksi = \App\Models\Transaksi::with(['user', 'details'])
            ->orderBy('created_at', 'DESC')
            ->take(5)
            ->get();

        $data = [
            'title' => 'Dashboard Kasir',
            'totalTransaksi' => $totalTransaksi,
            'totalItems' => $totalItems,
            'totalPendapatan' => $totalPendapatan,
            'recentTransaksi' => $recentTransaksi,
            'content' => 'kasir/dashboard/index',
        ];
        return view('kasir.layouts.wrapper', $data);
    }

}
