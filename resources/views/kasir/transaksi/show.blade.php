<div class="row p-2">

    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">

                <div class="row mt-1">
                    <div class="col-md-4">
                        <label for="">Nama Kasir</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" value="{{ $transaksi->user->name ?? '-' }}" disabled>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-md-4">
                        <label for="">Tanggal Transaksi</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" value="{{ $transaksi->created_at->format('d-m-Y H:i') }}" disabled>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">

            <h5>Daftar Produk</h5>
            <table class="table">
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Qty</th> 
                    <th>Subtotal</th>
                </tr>

                @foreach ($transaksi->details as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->produk->nama ?? $item->produk_name ?? '-' }}</td>
                    <td>{{ $item->qty }}</td> 
                    <td>{{ 'Rp. '.format_rupiah($item->subtotal) }}</td>
                </tr>
                @endforeach
            </table>

            </div>
        </div>
    </div>

</div>

<div class="row p-2">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">

                <div class="form-group">
                    <label for="">Total Belanja</label>
                    <input type="number" value="{{ $transaksi->total }}" class="form-control" disabled>
                </div>

                <div class="form-group">
                    <label for="">Dibayarkan</label>
                    <input type="text" value="{{ 'Rp. '.format_rupiah($transaksi->dibayarkan ?? 0) }}" class="form-control" disabled>
                </div>

                <div class="form-group">
                    <label for="">Uang Kembalian</label>
                    <input type="text" value="{{ 'Rp. '.format_rupiah($transaksi->kembalian ?? (($transaksi->dibayarkan ?? 0) - $transaksi->total)) }}" class="form-control" disabled>
                </div>

                <a href="/kasir/transaksi" class="btn btn-info"><i class="fas fa-arrow-left"></i> Kembali</a>

            </div>
        </div>
    </div>
</div>
