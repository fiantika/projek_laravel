<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

/**
 * Controller for managing products in the operator area.
 *
 * This controller provides CRUD functionality for the Produk
 * model but uses the operator namespace and view paths. All
 * behaviour mirrors the Admin\ProdukController except that
 * redirects and views point to `/operator` routes.
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
        'produk' => Produk::with('kategori')->paginate(10),
        'content' => 'operator/produk/index',
    ];
    return view('operator.layouts.wrapper', $data);
}


    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Produk',
            'kategori' => Kategori::all(),
            'content' => 'operator/produk/create',
        ];
        return view('operator.layouts.wrapper', $data);
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
            'berat' => 'nullable|numeric|min:0',
            'gambar' => 'nullable|image',
        ]);
        // Assign stok and berat from request
        // Assign stok and berat from request
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
        return redirect('/operator/produk')->with('success', 'Produk berhasil ditambahkan');
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
            'content' => 'operator/produk/create',
        ];
        return view('operator.layouts.wrapper', $data);
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
            'berat' => 'nullable|numeric|min:0',
            'gambar' => 'nullable|image',
        ]);
        // Assign stok and berat from request (update)
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
        return redirect('/operator/produk')->with('success', 'Produk berhasil diperbarui');
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
        return redirect('/operator/produk')->with('success', 'Produk berhasil dihapus');
    }
}