<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

/**
 * Controller for managing product categories.
 *
 * This controller groups all CRUD operations on the Kategori model. It replaces
 * the earlier `AdminKategoriController` and is namespaced under Admin for
 * clarity. Blade view paths are updated to the new directory structure.
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
            'content' => 'admin/kategori/index',
        ];
        return view('admin.layouts.wrapper', $data);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori',
            'content' => 'admin/kategori/create',
        ];
        return view('admin.layouts.wrapper', $data);
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
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $data = [
            'title' => 'Edit Kategori',
            'kategori' => Kategori::findOrFail($id),
            'content' => 'admin/kategori/create',
        ];
        return view('admin.layouts.wrapper', $data);
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
        return redirect()->back();
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        Alert::success('Sukses', 'Kategori berhasil dihapus');
        return redirect()->back();
    }
}