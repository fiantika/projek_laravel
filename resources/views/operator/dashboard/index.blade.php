<!-- Card Header Dashboard Operator -->
<div class="card shadow mb-4 header-shiny" style="border-radius: 16px; background: linear-gradient(135deg, #4e73df, #6f42c1); cursor: pointer;">
    <div class="card-body d-flex align-items-center justify-content-center" style="height: 70px; position: relative; overflow: hidden;">
        <h5 class="text-white mb-0">{{ $title ?? 'Dashboard Admin' }}</h5>
    </div>
</div>

<style>
.header-shiny::before {
    content: "";
    position: absolute;
    top: 0;
    left: -75%;
    width: 50%;
    height: 100%;
    background: linear-gradient(120deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.6) 50%, rgba(255,255,255,0) 100%);
    transform: skewX(-25deg);
    transition: none;
}

.header-shiny:hover::before {
    animation: shine 0.5s infinite;
}

@keyframes shine {
    0% {
        left: -75%;
    }
    100% {
        left: 125%;
    }
}
</style>


<!-- Row Statistik Operator Playful -->
<div class="row">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card playful-card shadow h-100" style="border-radius: 16px; background: linear-gradient(135deg, #4e73df); color: #fff;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative">
                <div class="icon" style="font-size: 1.8rem; margin-bottom: 5px;">
                    <i class="fas fa-box"></i>
                </div>
                <h6 class="card-title mb-1">Total Produk</h6>
                <p class="card-text fs-5 fw-bold">{{ $totalProduk }}</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card playful-card shadow h-100" style="border-radius: 16px; background: linear-gradient(135deg, #1cc88a, #17a673); color: #fff;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative">
                <div class="icon" style="font-size: 1.8rem; margin-bottom: 5px;">
                    <i class="fas fa-tags"></i>
                </div>
                <h6 class="card-title mb-1">Total Kategori</h6>
                <p class="card-text fs-5 fw-bold">{{ $totalKategori }}</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card playful-card shadow h-100" style="border-radius: 16px; background: linear-gradient(135deg, #f6c23e, #dda20a); color: #fff;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative">
                <div class="icon" style="font-size: 1.8rem; margin-bottom: 5px;">
                    <i class="fas fa-users"></i>
                </div>
                <h6 class="card-title mb-1">Total User</h6>
                <p class="card-text fs-5 fw-bold">{{ $totalUser }}</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card playful-card shadow h-100" style="border-radius: 16px; background: linear-gradient(135deg, #858796, #5a5c69); color: #fff;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative">
                <div class="icon" style="font-size: 1.8rem; margin-bottom: 5px;">
                    <i class="fas fa-receipt"></i>
                </div>
                <h6 class="card-title mb-1">Total Transaksi</h6>
                <p class="card-text fs-5 fw-bold">{{ $totalTransaksi }}</p>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 & Chart.js sudah termasuk sebelumnya -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
@if(session('swal'))
const swalData = @json(session('swal'));

// Buat overlay blur di background
const blurOverlay = document.createElement('div');
blurOverlay.classList.add('toast-blur-overlay');
document.body.appendChild(blurOverlay);

Swal.fire({
    icon: swalData.icon,
    title: swalData.title,
    text: swalData.text,
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
    didOpen: (toast) => {
        // Pastikan toast muncul di atas blur
        toast.style.zIndex = 9999;

        // Animasi meriah: scale up & rotate
        toast.animate([
            { transform: 'scale(0) rotate(-45deg)', opacity: 0 },
            { transform: 'scale(1.1) rotate(10deg)', opacity: 1 },
            { transform: 'scale(1) rotate(0deg)', opacity: 1 }
        ], { duration: 600, easing: 'ease-out' });

        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    },
    willClose: () => {
        // Hapus overlay blur saat toast hilang
        blurOverlay.remove();
    }
});
@endif
</script>





<style>
.playful-card {
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.playful-card::after {
    content: "";
    position: absolute;
    top: -50%;
    left: -75%;
    width: 200%;
    height: 200%;
    background: linear-gradient(120deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.6) 50%, rgba(255,255,255,0.2) 100%);
    transform: rotate(25deg) translateX(-100%);
    transition: transform 0.5s ease;
}

.playful-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.25);
}

.playful-card:hover::after {
    transform: rotate(25deg) translateX(100%);
}

.playful-card .icon {
    transition: transform 0.5s ease;
}

.playful-card:hover .icon {
    transform: translateY(-3px) rotate(-8deg);
}
</style>



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
