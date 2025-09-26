<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Controller responsible for displaying the administrative dashboard.
 */
class DashboardController extends Controller
{
    /**
     * Show the dashboard view.
     */
    public function index()
    {
        $data = [
            'content' => 'admin/dashboard/index',
        ];
        return view('admin.layouts.wrapper', $data);
    }
}