<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

/**
 * Controller for managing products.
 *
 * This controller encapsulates CRUD operations for the Produk model. It handles
 * image uploads and cleans up old images on update/delete. View paths are
 * namespaced under the admin directory.
 */
class ProdukController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $data = [
            'title' => 'Manajemen Produk',
            'produk' => Produk::paginate(10),
            'content' => 'admin/produk/index',
        ];
        return view('admin.layouts.wrapper', $data);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Produk',
            'kategori' => Kategori::all(),
            'content' => 'admin/produk/create',
        ];
        return view('admin.layouts.wrapper', $data);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'kategori_id' => 'required',
            'harga' => 'required|integer',
            'gambar' => 'nullable|image',
        ]);
        // Handle image upload
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $fileName = time() . '_' . $gambar->getClientOriginalName();
            $storage = 'uploads/images/';
            $gambar->move($storage, $fileName);
            $data['gambar'] = $storage . $fileName;
        } else {
            $data['gambar'] = null;
        }
        Produk::create($data);
        Alert::success('Sukses', 'Produk berhasil ditambahkan');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $data = [
            'title' => 'Edit Produk',
            'produk' => Produk::findOrFail($id),
            'kategori' => Kategori::all(),
            'content' => 'admin/produk/create',
        ];
        return view('admin.layouts.wrapper', $data);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $data = $request->validate([
            'name' => 'required',
            'kategori_id' => 'required',
            'harga' => 'required|integer',
            'gambar' => 'nullable|image',
        ]);
        if ($request->hasFile('gambar')) {
            // delete old image if exists
            if ($produk->gambar) {
                @unlink($produk->gambar);
            }
            $gambar = $request->file('gambar');
            $fileName = time() . '_' . $gambar->getClientOriginalName();
            $storage = 'uploads/images/';
            $gambar->move($storage, $fileName);
            $data['gambar'] = $storage . $fileName;
        } else {
            $data['gambar'] = $produk->gambar;
        }
        $produk->update($data);
        Alert::success('Sukses', 'Produk berhasil diperbarui');
        return redirect()->back();
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        if ($produk->gambar) {
            @unlink($produk->gambar);
        }
        $produk->delete();
        Alert::success('Sukses', 'Produk berhasil dihapus');
        return redirect()->back();
    }
}