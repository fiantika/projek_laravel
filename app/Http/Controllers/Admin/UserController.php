<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

/**
 * Controller for managing application users.
 *
 * This controller centralizes all CRUD operations for the User model.
 * Routes exclude the `show` action, since users are displayed via the index table.
 */
class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $data = [
            'title' => 'Manajemen User',
            'user' => User::all(),
            'content' => 'admin/user/index',
        ];
        return view('admin.layouts.wrapper', $data);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah User',
            'content' => 'admin/user/create',
        ];
        return view('admin.layouts.wrapper', $data);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            're_password' => 'required|same:password',
            'role' => 'required',
        ]);
        $data['password'] = Hash::make($data['password']);
        unset($data['re_password']);
        User::create($data);
        Alert::success('Sukses', 'User berhasil ditambahkan');
        return redirect('/admin/user')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $data = [
            'user' => User::findOrFail($id),
            'title' => 'Edit User',
            'content' => 'admin/user/create',
        ];
        return view('admin.layouts.wrapper', $data);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable',
            're_password' => 'same:password',
            'role' => 'required',
        ]);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        } else {
            $data['password'] = $user->password;
        }
        unset($data['re_password']);
        $user->update($data);
        Alert::success('Sukses', 'User berhasil diperbarui');
        return redirect('/admin/user')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        Alert::success('Sukses', 'User berhasil dihapus');
        return redirect('/admin/user')->with('success', 'User berhasil dihapus.');
    }
}