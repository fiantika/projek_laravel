<h1 class="h3 mb-4 text-gray-800">Dashboard Admin</h1>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">Total Produk</h5>
                <p class="card-text">{{ $totalProduk ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">Total Transaksi</h5>
                <p class="card-text">{{ $totalTransaksi ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">Total User</h5>
                <p class="card-text">{{ $totalUser ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>