<div class="container-fluid">
    <!-- Greeting -->
    @if (auth()->check())
        <div class="alert alert-success">Halo {{ auth()->user()->name }}, selamat datang di halaman kasir!</div>
    @else
        <div class="alert alert-warning">Halo Guest, silakan login terlebih dahulu!</div>
    @endif

    <!-- Summary cards -->
    <div class="row mt-4">
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Transaksi Hari Ini</h5>
                    <p class="card-text h3 mb-0">{{ $totalTransaksi ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Barang Terjual Hari Ini</h5>
                    <p class="card-text h3 mb-0">{{ $totalItems ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Pendapatan Hari Ini</h5>
                    <p class="card-text h3 mb-0">Rp. {{ format_rupiah($totalPendapatan ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent transactions -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Transaksi Terbaru</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kasir</th>
                            <th>Produk</th>
                            <th>Barang</th>
                            <th>Total</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTransaksi as $index => $trx)
                            @php
                                $productNames = $trx->details->pluck('produk_name')->implode(', ');
                                $qty = $trx->details->sum('qty');
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $trx->user->name ?? '-' }}</td>
                                <td>{{ $productNames ?: '-' }}</td>
                                <td>{{ $qty ?: 0 }}</td>
                                <td>Rp. {{ format_rupiah($trx->total) }}</td>
                                <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada transaksi baru</td>
                            </tr>
                        @endforelse
                    </tbody>

                    <style>
                    .content-wrapper {
                        background: linear-gradient(135deg, hsl(250, 100%, 84%), hsl(250, 82%, 62%));
                    }
                    </style>

                </table>
            </div>
        </div>
    </div>
</div>