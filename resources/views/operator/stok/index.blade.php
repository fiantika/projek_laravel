<div style="min-height: 100vh; display: flex; gap: 20px; padding: 20px; flex-wrap: wrap;">

    <!-- Kiri: Tambah Stok -->
    <div style="flex: 1; min-width: 300px;">
        <div class="card shadow" style="border-radius: 12px;">
            <!-- Header -->
<div style="background: linear-gradient(135deg, #4e73df, #6f42c1); 
            color: white; 
            min-height: 60px; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            padding: 15px;">
    <h5 class="mb-0"><b>Tambah Stok Produk</b></h5>
</div>

            <div class="card-body">
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
                    <button type="submit" class="btn" 
                        style="background-color: white; color: #4e73df; font-weight: bold; border: none;">
                        <i class="fas fa-plus"></i> Tambah Stok
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kanan: Riwayat Stok -->
    <div style="flex: 2; min-width: 400px;">
        <div class="card shadow" style="border-radius: 12px;">
        <div style="background: linear-gradient(135deg, #4e73df, #6f42c1); 
            color: white; 
            min-height: 60px; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            padding: 15px;">
    <h5 class="mb-0"><b>Riwayat Stok</b></h5>
</div>

            <div class="card-body" style="overflow-x: auto;">
                <table class="table table-bordered table-hover" style="width: 100%;">
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
                <div class="d-flex justify-content-center mt-2">
                    {{ $histories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
