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
        $data = [
            // Use the operator dashboard view.  The wrapper will load
            // `operator/dashboard/index.blade.php` into the content slot.
            'content' => 'operator/dashboard/index',
        ];
        return view('operator.layouts.wrapper', $data);
    }
}