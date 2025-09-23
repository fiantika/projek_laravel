<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class SesiController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'emailnya diisi dulu yak',
            'password.required' => 'pw nya isi juga laa',
        ]);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($infologin)) {
            if(Auth::user()->role == 'operator'){
                return redirect('admin/operator');
            } elseif(Auth::user()->role == 'keuangan'){
                return redirect('admin/keuangan');
            } 
        } else {
            return redirect('')->withErrors('salah ini salah -_-')->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('');
    }
}
