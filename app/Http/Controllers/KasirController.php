<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KasirController extends Controller
{
    //
    public function index()
    {
        // Show the cashier dashboard using the kasir layout wrapper. The
        // `$content` variable tells the wrapper which view to include.
        $data = [
            'title' => 'Dashboard Kasir',
            'content' => 'kasir/dashboard/index',
        ];
        return view('kasir.layouts.wrapper', $data);
    }

}
