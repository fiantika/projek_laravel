
<div class="row p-2">
    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h5><b>{{ $title }}</b></h5>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Form Pilih Produk -->
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Pilih Produk</label>
                    </div>
                    <div class="col-md-8">
                        <form method="GET">
                            <div class="d-flex">
                                <select name="produk_id" class="form-control" required>
                                    <option value="">--{{ isset($p_detail) ? $p_detail->name : 'Pilih Produk' }}--</option>
                                    @foreach ($produk as $item)
                                        <option value="{{ $item->id }}" {{ request('produk_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->id.' - '. $item->name }} (Stok: {{ $item->stok }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary ml-2">Pilih</button>
                            </div>
                        </form> 
                    </div>
                </div>

                <!-- Form Tambah ke Transaksi -->
                @if ($p_detail)
                <form action="/admin/transaksi/detail/create" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="transaksi_id" value="{{ $transaksi_id }}">
                    <input type="hidden" name="produk_id" value="{{ $p_detail->id }}">
                    <input type="hidden" name="produk_name" value="{{ $p_detail->name }}">
                    <input type="hidden" name="subtotal" value="{{ $subtotal }}">

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label>Nama Produk</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" value="{{ $p_detail->name }}" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label>Harga Satuan</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" value="Rp. {{ number_format($p_detail->harga, 0, ',', '.') }}" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label>Stok Tersedia</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" value="{{ $p_detail->stok }}" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label>Quantity</label>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex">
                                <a href="?produk_id={{ request('produk_id') }}&act=min&qty={{ $qty }}" class="btn btn-secondary">
                                    <i class="fas fa-minus"></i>
                                </a>
                                <input type="number" value="{{ $qty }}" class="form-control mx-2" name="qty" min="1" max="{{ $p_detail->stok }}">
                                <a href="?produk_id={{ request('produk_id') }}&act=plus&qty={{ $qty }}" class="btn btn-secondary">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4"></div>
                        <div class="col-md-8">
                            <h5 class="text-primary">Subtotal: Rp. {{ number_format($subtotal, 0, ',', '.') }}</h5>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4"></div>
                        <div class="col-md-8">
                            <a href="/admin/transaksi" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                </form>
                @else
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> Silakan pilih produk terlebih dahulu untuk menambahkan ke transaksi.
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Keranjang Belanja -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Qty</th> 
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksi_detail as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->produk_name }}</td>
                                <td>{{ $item->qty }}</td> 
                                <td>Rp. {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                <td>
                                    <a href="/admin/transaksi/detail/delete?id={{ $item->id }}" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin ingin menghapus item ini?')">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Keranjang kosong</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Total: <span class="text-success">Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</span></h5>
                    </div>
                    <div class="col-md-6 text-right">
                        @if(count($transaksi_detail) > 0)
                            <a href="/admin/transaksi/detail/selesai/{{ $transaksi_id }}" 
                               class="btn btn-success"
                               onclick="return confirm('Selesaikan transaksi ini?')">
                                <i class="fas fa-check"></i> Selesaikan
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Pembayaran -->
@if(count($transaksi_detail) > 0)
<div class="row p-2">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-calculator"></i> Kalkulator Pembayaran</h5>
            </div>
            <div class="card-body">
                <form method="GET">
                    <input type="hidden" name="produk_id" value="{{ request('produk_id') }}">
                    
                    <div class="form-group">
                        <label>Total Belanja</label>
                        <input type="text" value="Rp. {{ number_format($transaksi->total, 0, ',', '.') }}" class="form-control" disabled>
                    </div>

                    <div class="form-group">
                        <label>Dibayarkan</label>
                        <input type="number" name="dibayarkan" value="{{ $dibayarkan }}" class="form-control" min="{{ $transaksi->total }}">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-calculator"></i> Hitung Kembalian
                    </button>
                </form>

                @if($dibayarkan > 0)
                <hr>
                <div class="form-group">
                    <label>Uang Kembalian</label>
                    <input type="text" 
                           value="Rp. {{ number_format($kembalian, 0, ',', '.') }}" 
                           class="form-control {{ $kembalian < 0 ? 'is-invalid' : 'is-valid' }}" 
                           disabled>
                    @if($kembalian < 0)
                        <div class="invalid-feedback">Uang yang dibayarkan kurang!</div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
</x-side-bar>