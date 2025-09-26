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
            'user'  => User::all(),
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
            // Restrict roles to operator or keuangan. The admin role has been removed.
            'role' => 'required|in:operator,keuangan',
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
            'password' => 'nullable',
            're_password' => 'same:password',
            // Restrict roles to operator or keuangan when updating
            'role' => 'required|in:operator,keuangan',
        ]);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        } else {
            $data['password'] = $user->password;
        }
        unset($data['re_password']);
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