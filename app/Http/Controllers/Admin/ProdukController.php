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
            'stok' => 'required|integer|min:0',
            'berat' => 'nullable|numeric|min:0',
            'gambar' => 'nullable|image',
        ]);
        // Assign stok and berat from request
        $data['stok'] = $request->input('stok', 0);
        $data['berat'] = $request->input('berat');
        // Handle image upload
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $fileName = time() . '_' . $gambar->getClientOriginalName();
            // Determine public upload directory
            $destination = public_path('uploads/images');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $gambar->move($destination, $fileName);
            $data['gambar'] = 'uploads/images/' . $fileName;
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
            'stok' => 'required|integer|min:0',
            'berat' => 'nullable|numeric|min:0',
            'gambar' => 'nullable|image',
        ]);
        // Assign stok and berat from request (update)
        $data['stok'] = $request->input('stok', $produk->stok);
        $data['berat'] = $request->input('berat', $produk->berat);
        if ($request->hasFile('gambar')) {
            // delete old image if exists
            if ($produk->gambar) {
                @unlink(public_path($produk->gambar));
            }
            $gambar = $request->file('gambar');
            $fileName = time() . '_' . $gambar->getClientOriginalName();
            // Determine public upload directory
            $destination = public_path('uploads/images');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $gambar->move($destination, $fileName);
            $data['gambar'] = 'uploads/images/' . $fileName;
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
            @unlink(public_path($produk->gambar));
        }
        $produk->delete();
        Alert::success('Sukses', 'Produk berhasil dihapus');
        return redirect()->back();
    }
}