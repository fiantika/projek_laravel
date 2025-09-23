<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            're_password' => 'required|same:password',
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);
        Alert::success('Success Title', 'Data berhasil ditambahkan');
        return redirect('/admin/user')->with('success', 'Data berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        Alert::success('Success Title', 'Data Telah Dihapus');
        return redirect('/admin/user')->with('success', 'Data berhasil dihapus.');
    }

    public function edit($id)
    {
        $data = [
            'user' => User::find($id),
            'content' => 'admin.user.create'
        ];
        return view('admin.layouts.wrapper', $data);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable', 
            're_password' => 'same:password',
        ]);
        
        if ($request->password != '') {
            $data['password'] = Hash::make($request->password);
        } else {
            $data['password'] = $user->password;
        }

        $user->update($data);
        Alert::success('Success Title', 'Data Berhasil di Edit');
        return redirect('/admin/user')->with('success', 'Data berhasil diedit.');
    }

    public function index()
    {
        $data = [
            'content' => 'admin/dashboard'
        ];
        return view('admin.layouts.wrapper', $data);
    }

    

    // Method untuk menampilkan daftar user (untuk halaman /admin/user)
    public function show($id = null)
    {
        if ($id) {
            // Show single user
            $data = [
                'title' => 'Detail User',
                'user' => User::find($id),
                'content' => 'admin.user.show'
            ];
        } else {
            // Show all users
            $data = [
                'title' => 'Manajemen User',
                'users' => User::get(),
                'content' => 'admin.user.index'
            ];
        }
        return view('admin.layouts.wrapper', $data);
    }

    // Backward compatibility methods
    public function operator()
    { 
        return redirect('/admin/dashboard');
    }

    public function keuangan()
    {
        return redirect('/admin/dashboard');
    }
}