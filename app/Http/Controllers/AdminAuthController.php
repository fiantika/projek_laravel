<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{
    //
    function index()
    {
        return view('admin.auth.login');
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            
            Log::info('User logged in:', ['email' => $user->email, 'role' => $user->role]);
            
            if ($user->role === 'operator') {
                Log::info('Redirecting to operator dashboard');
                return redirect('/operator/dashboard');
            } elseif ($user->role === 'keuangan') {
                Log::info('Redirecting to keuangan dashboard');
                return redirect('/keuangan/dashboard');
            } else {
                Log::info('Unknown role, redirecting to home', ['role' => $user->role]);
                return redirect('/');
            }
        }

        Log::info('Login failed for email:', ['email' => $request->email]);
        return redirect()->back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('login');
    }
}
