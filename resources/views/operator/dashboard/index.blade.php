
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('stokChart').getContext('2d');
    const stokChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [
                {
                    label: 'Stok',
                    data: {!! json_encode($stockData) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: false,
                },
                {
                    label: 'Terjual',
                    data: {!! json_encode($soldData) !!},
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1,
                    fill: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
