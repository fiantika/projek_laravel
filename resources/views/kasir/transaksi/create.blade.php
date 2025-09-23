<div class="row p-2">

    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">

                <div class="row mt-1">
                    <div class="col-md-4">
                        <label for="">Kode Produk</label>
                    </div>
                    <div class="col-md-8">
                       <form method="GET">
                           <div class="d-flex">
                               <select name="produk_id" class="form-control" id="">
                                    <option value="">--{{ isset($p_detail) ? $p_detail->name : 'Nama Produk' }}--</option>
                                    @foreach ($produk as $item)
                                         <option value="{{ $item->id }}">{{ $item->id.' - '. $item->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary">Pilih</button>
                            </div>
                        </form> 
                    </div>
                </div>

                    <form action="/admin/transaksi/detail/create" method="POST">
                    @csrf

                    <input type="hidden" name="transaksi_id" value="{{ $transaksi_id ?? '' }}">

                    @if ($p_detail)
                        <input type="hidden" name="produk_id" value="{{ $p_detail->id }}">
                        <input type="hidden" name="produk_name" value="{{ $p_detail->name }}">
                        <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                    @else
                        <div class="alert alert-warning mt-2">Silakan pilih produk terlebih dahulu</div>
                    @endif


                  <div class="row mt-1">
                    <div class="col-md-4">
                        <label for="">Nama Produk</label>
                    </div>
                    <div class="col-md-8">
                       <input type="text" value="{{ isset($p_detail) ? $p_detail->name : ''  }}" class="form-control" disabled name="nama_produk">
                    </div>
                </div>

                  <div class="row mt-1">
                    <div class="col-md-4">
                        <label for="">Harga Satuan</label>
                    </div>
                    <div class="col-md-8">
                       <input type="text" value="{{ isset($p_detail) ? $p_detail->harga : ''  }}" class="form-control" disabled name="harga_satuan">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-md-4">
                        <label for="">QTY</label>
                    </div>
                    <div class="col-md-8">
                       <div class="d-flex">
                        <a href="?produk_id={{ request('produk_id') }}&act=min&qty={{ $qty }}" class="btn btn-primary"><i class="fas fa-minus"></i></a>
                        <input type="number" value="{{ $qty }}" class="form-control" name="qty">
                        <a href="?produk_id={{ request('produk_id') }}&act=plus&qty={{ $qty }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                       </div>
                    </div>
                </div>

                 <div class="row mt-1">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-8">
                        <h5>Subtotal : Rp. {{ format_rupiah($subtotal) }}</h5>
                    </div>
                </div>

                 <div class="row mt-1">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-8">
                        <a href="/admin/transaksi" class="btn btn-info"><i class="fas fa-arrow-left"></i>Kembali</a>
                        <button type="submit" class="btn btn-primary">Tambah<i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>

                </form>

            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">

            <table class="table">
    <tr>
        <th>No</th>
        <th>Nama Produk</th>
        <th>Qty</th> 
        <th>Subtotal</th>
        <th>#</th>
    </tr>

    @foreach ($transaksi_detail as $index => $item)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $item->produk_name }}</td>
        <td>{{ $item->qty }}</td> 
        <td>{{ 'Rp. '.format_rupiah($item->subtotal) }}</td>
        <td>
            <a href="/admin/transaksi/detail/delete?id={{ $item->id }}"><i class="fas fa-times"></i></a>
        </td>
    </tr>
    @endforeach
</table>

        <a href="/admin/transaksi/detail/selesai/{{ Request::segment(3) }}" class="btn btn-success"><i class="fas fa-check"></i>Selesai</a>
        <a href="" class="btn btn-info"><i class="fas fa-file"></i>Pending</a>
        </div>
        </div>
    </div>

</div>

<div class="row p-2">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">

                <form action="" method="GET">
                    <div class="form-group">
                        <label for="">Total Belanja</label>
                        <input type="number" value="{{ $transaksi->total }}" name="total_belanja" class="total_belanja form-control" id="">
                    </div>

                    <div class="form-group">
                        <label for="">Dibayarkan</label>
                        <input type="number" name="dibayarkan" value="{{ request('dibayarkan') }}" class="dibayarkan form-control" id="">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Hitung</button>

                </form>

                <hr>

                <div class="form-group">
                    <label for="">Uang Kembalian</label>
                    <input type="number" value="{{ format_rupiah($kembalian) }}" disabled name="kembalian" class="dibayarkan form-control" id="">
                </div>

            </div>
        </div>
    </div>
</div>