<div class="container-fluid">
<!-- Greeting -->
@if (auth()->check())
    <div class="alert alert-success shadow-sm greet-hover" style="border-radius: 10px;">
        Halo <b>{{ auth()->user()->name }}</b>, selamat datang di halaman kasir!
    </div>
@else
    <div class="alert alert-warning shadow-sm greet-hover" style="border-radius: 10px;">
        Halo Guest, silakan login terlebih dahulu!
    </div>
@endif

<style>
.greet-hover {
    position: relative;
    overflow: hidden;
}

.greet-hover::before {
    content: '';
    position: absolute;
    top: 0;
    left: -75%;
    width: 50%;
    height: 100%;
    background: linear-gradient(120deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
    transform: skewX(-20deg);
}

.greet-hover:hover::before {
    animation: shine 1s infinite;
}

@keyframes shine {
    0% { left: -75%; }
    100% { left: 125%; }
}
</style>



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



    <!-- Summary cards -->
 <div class="row mt-4">
    <!-- Card 1: Transaksi Hari Ini -->
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card shadow-lg text-white card-hover" 
             style="border-radius: 12px; background: linear-gradient(135deg, #4e73df, #6f42c1); transition: transform 0.3s;">
            <div class="card-body d-flex align-items-center gap-4">
                <div style="font-size: 2rem; flex-shrink: 0;">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="text-center flex-grow-1">
                    <h6 class="mb-1" style="opacity: 0.8;">Transaksi Hari Ini</h6>
                    <p class="h3 mb-0 fw-bold counter" data-target="{{ $totalTransaksi ?? 0 }}">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Barang Terjual -->
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card shadow-lg text-white card-hover" 
             style="border-radius: 12px; background: linear-gradient(135deg, #1cc88a, #17a673); transition: transform 0.3s;">
            <div class="card-body d-flex align-items-center gap-4">
                <div style="font-size: 2rem; flex-shrink: 0;">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="text-center flex-grow-1">
                    <h6 class="mb-1" style="opacity: 0.8;">Barang Terjual Hari Ini</h6>
                    <p class="h3 mb-0 fw-bold counter" data-target="{{ $totalItems ?? 0 }}">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Pendapatan Hari Ini -->
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card shadow-lg text-white card-hover" 
             style="border-radius: 12px; background: linear-gradient(135deg, #f6c23e, #dda20a); transition: transform 0.3s;">
            <div class="card-body d-flex align-items-center gap-4">
                <div style="font-size: 2rem; flex-shrink: 0;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="text-center flex-grow-1">
                    <h6 class="mb-1" style="opacity: 0.8;">Pendapatan Hari Ini</h6>
                    <p class="h3 mb-0 fw-bold counter-rupiah" data-target="{{ $totalPendapatan ?? 0 }}">Rp. 0</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-hover:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 25px rgba(0,0,0,0.2);
}
</style>

<script>
// Animasi angka naik untuk angka biasa
document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = +counter.getAttribute('data-target');
        let count = 0;
        const increment = target / 100;
        const updateCounter = () => {
            count += increment;
            if(count < target){
                counter.innerText = Math.ceil(count);
                requestAnimationFrame(updateCounter);
            } else {
                counter.innerText = target;
            }
        };
        updateCounter();
    });

    // Animasi angka naik untuk rupiah
    const rupiahCounters = document.querySelectorAll('.counter-rupiah');
    rupiahCounters.forEach(counter => {
        const target = +counter.getAttribute('data-target');
        let count = 0;
        const increment = target / 100;
        const formatRupiah = (num) => {
            return 'Rp. ' + num.toLocaleString('id-ID');
        }
        const updateCounter = () => {
            count += increment;
            if(count < target){
                counter.innerText = formatRupiah(Math.ceil(count));
                requestAnimationFrame(updateCounter);
            } else {
                counter.innerText = formatRupiah(target);
            }
        };
        updateCounter();
    });
});
</script>



  <div class="card mt-4" style="border-radius: 12px; overflow: hidden;">
    <!-- Header gradasi -->
    <div style="background: linear-gradient(135deg, #4e73df, #6f42c1); 
                color: white; 
                text-align: center; 
                padding: 15px 0;">
        <h5 class="mb-0 fw-bold">Transaksi Terbaru</h5>
    </div>


    <!-- Table dengan jarak atas -->
    <div class="card-body pt-3" style="overflow-x: auto;">
        <table class="table table-bordered table-hover mb-0">
            <thead style="background-color: #4e73df; color: white;">
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
        </table>
    </div>
</div>
