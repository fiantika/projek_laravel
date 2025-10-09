<div class="row p-2">
    <div class="col-md-6 col-sm-12 mb-3">
        <div class="card">
            <div class="card-body">
                <h5><b>Tambah Stok Produk</b></h5>
                <form action="{{ url('/operator/stok') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="produk_id" class="form-label">Produk</label>
                        <select name="produk_id" id="produk_id" class="form-control" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produks as $produk)
                                <option value="{{ $produk->id }}">{{ $produk->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="qty" class="form-label">Jumlah Stok Masuk</label>
                        <input type="number" name="qty" id="qty" class="form-control" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Stok</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h5><b>Riwayat Stok</b></h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Tipe</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($histories as $history)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $history->produk ? $history->produk->name : '-' }}</td>
                            <td>{{ $history->qty }}</td>
                            <td>
                                @if($history->type === 'in')
                                    <span class="badge bg-success">Masuk</span>
                                @else
                                    <span class="badge bg-danger">Keluar</span>
                                @endif
                            </td>
                            <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $histories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>