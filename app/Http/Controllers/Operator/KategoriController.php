<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

/**
 * Controller for managing categories for operator users.
 *
 * This controller mirrors the Admin\KategoriController but is
 * namespaced for the operator prefix and loads views from the
 * `operator/kategori` directory. Operators can perform CRUD on
 * categories using the same validation rules as administrators.
 */
class KategoriController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $data = [
            'title' => 'Manajemen Kategori',
            'kategori' => Kategori::paginate(10),
            'content' => 'operator/kategori/index',
        ];
        return view('operator.layouts.wrapper', $data);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori',
            'content' => 'operator/kategori/create',
        ];
        return view('operator.layouts.wrapper', $data);
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:kategoris',
        ]);
        Kategori::create($data);
        Alert::success('Sukses', 'Kategori berhasil ditambahkan');
        return redirect('/operator/kategori')->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $data = [
            'title' => 'Edit Kategori',
            'kategori' => Kategori::findOrFail($id),
            'content' => 'operator/kategori/create',
        ];
        return view('operator.layouts.wrapper', $data);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|unique:kategoris,name,' . $kategori->id,
        ]);
        $kategori->update($data);
        Alert::success('Sukses', 'Kategori berhasil diperbarui');
        return redirect('/operator/kategori')->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        Alert::success('Sukses', 'Kategori berhasil dihapus');
        return redirect('/operator/kategori')->with('success', 'Kategori berhasil dihapus');
    }
}