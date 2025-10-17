<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

/**
 * Controller for managing users under the operator namespace.
 *
 * Operators have permission to manage users much like administrators,
 * however the route prefixes and view paths differ. All methods
 * mirror the behaviour of the Admin\UserController but operate on
 * `/operator/*` URLs and load views from the `operator` directory.
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
        'users' => User::all(), // ✅ plural
        'content' => 'operator/user/index',
    ];
    return view('operator.layouts.wrapper', $data);
}

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah User',
            'content' => 'operator/user/create',
        ];
        return view('operator.layouts.wrapper', $data);
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
        return redirect('/operator/user')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $data = [
            'user' => User::findOrFail($id),
            'title' => 'Edit User',
            'content' => 'operator/user/create',
        ];
        return view('operator.layouts.wrapper', $data);
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
        'old_password' => 'nullable', // wajib kalau ganti password
        'password' => 'nullable',
        're_password' => 'same:password',
        'role' => 'required',
    ]);

    // Cek password lama hanya jika user mau ganti password
    if ($request->filled('password')) {
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Password lama salah']);
        }
        $data['password'] = Hash::make($request->password);
    } else {
        // Kalau tidak ganti, tetap simpan password lama
        $data['password'] = $user->password;
    }

    unset($data['re_password']);
    unset($data['old_password']);

    $user->update($data);

    Alert::success('Sukses', 'User berhasil diperbarui');
    return redirect('/operator/user')->with('success', 'User berhasil diperbarui.');
}


    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        Alert::success('Sukses', 'User berhasil dihapus');
        return redirect('/operator/user')->with('success', 'User berhasil dihapus.');
    }
}