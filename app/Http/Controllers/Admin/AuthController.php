<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Authentication controller for the back office.
 *
 * This class unifies the login and logout functionality for all roles.
 * After a successful login, users are redirected based on their role:
 * - `admin`: directed to the admin dashboard.
 * - `operator`: directed to the operator dashboard.
 * - `keuangan`: directed to the financial (kasir) dashboard.
 */
class AuthController extends Controller
{
    /**
     * Display the login form.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function doLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            Log::info('User logged in:', ['email' => $user->email, 'role' => $user->role]);
        // Redirect based on role. The "admin" role has been merged into the
        // operator role. Operators are sent to their dashboard and
        // financial (keuangan) users are sent to the kasir dashboard.
        if ($user->role === 'operator') {
            return redirect('/operator/dashboard');
        }
        if ($user->role === 'keuangan') {
            return redirect('/kasir/dashboard');
        }
        // Default fallback for unexpected roles
        return redirect('/login');
        }
        Log::warning('Login failed for email:', ['email' => $request->email]);
        return redirect()->back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    }
}