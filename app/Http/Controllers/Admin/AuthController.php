<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $request->session()->regenerate();

            // Session swal untuk notif animasi
            $swal = [
                'icon' => 'success',
                'title' => 'Login Berhasil!',
                'text' => 'Selamat datang, ' . $user->name
            ];

            Log::info('User logged in:', ['email' => $user->email, 'role' => $user->role]);

            switch ($user->role) {
                case 'operator':
                    return redirect('/operator/dashboard')->with('swal', $swal);
                case 'keuangan':
                    return redirect('/kasir/dashboard')->with('swal', $swal);
                case 'admin':
                    return redirect('/admin/dashboard')->with('swal', $swal);
                default:
                    Auth::logout();
                    return redirect('/login');
            }
        }

        Log::warning('Login failed for email:', ['email' => $request->email]);
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    }
}
