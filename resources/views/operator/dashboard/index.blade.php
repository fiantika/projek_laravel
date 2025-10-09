
<h1 class="h3 mb-4 text-gray-800">{{ $title ?? 'Dashboard' }}</h1>

<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">Total Produk</h5>
                <p class="card-text">{{ $totalProduk }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 mb-4">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">Total Kategori</h5>
                <p class="card-text">{{ $totalKategori }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 mb-4">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">Total User</h5>
                <p class="card-text">{{ $totalUser }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 mb-4">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">Total Transaksi</h5>
                <p class="card-text">{{ $totalTransaksi }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Grafik Stok vs Terjual per Produk</h6>
    </div>
    <div class="card-body">
        <canvas id="stokChart" height="100"></canvas>
    </div>
</div>

<!-- Grafik tren stok masuk/keluar -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Grafik Stok Masuk vs Keluar (Harian)</h6>
    </div>
    <div class="card-body">
        <canvas id="stokTrenChart" height="100"></canvas>
    </div>
</div>

<!-- Grafik produk terlaris -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Produk Terlaris</h6>
    </div>
    <div class="card-body">
        <canvas id="topProdukChart" height="100"></canvas>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik stok vs terjual per produk
    const stokCtx = document.getElementById('stokChart').getContext('2d');
    const stokChart = new Chart(stokCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [
                {
                    label: 'Stok',
                    data: {!! json_encode($stockData) !!},
                    tension: 0.1,
                    fill: false,
                },
                {
                    label: 'Terjual',
                    data: {!! json_encode($soldData) !!},
                    tension: 0.1,
                    fill: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Grafik stok masuk vs keluar per hari
    const trenCtx = document.getElementById('stokTrenChart').getContext('2d');
    const stokTrenChart = new Chart(trenCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [
                {
                    label: 'Stok Masuk',
                    data: {!! json_encode($stokInData) !!},
                    tension: 0.1,
                    fill: false,
                },
                {
                    label: 'Stok Keluar',
                    data: {!! json_encode($stokOutData) !!},
                    tension: 0.1,
                    fill: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Grafik produk terlaris (pie chart)
    const topCtx = document.getElementById('topProdukChart').getContext('2d');
    const topProdukChart = new Chart(topCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($topProdukLabels) !!},
            datasets: [
                {
                    data: {!! json_encode($topProdukQty) !!},
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });
</script>
