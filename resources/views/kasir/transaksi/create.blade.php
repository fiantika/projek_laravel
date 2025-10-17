<div class="container-fluid px-4 py-3">

  <!-- ROW UTAMA: Form produk & tabel transaksi sejajar -->
  <div class="row">
    <!-- Kolom kiri: Form tambah produk -->
    <div class="col-md-6 mb-4">
      <!-- Card Judul -->
      <div class="card shadow-sm mb-2" style="background-color: #4e73df; color: white;">
        <div class="card-body d-flex align-items-center">
          <i class="bi bi-cart-plus me-2"></i>
          <h5 class="mb-0">Form Tambah Produk</h5>
        </div>
      </div>
      <!-- Card Konten -->
      <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body">

          <!-- Form Pilih Produk -->
          <form method="GET" class="mb-4">
            <label class="form-label fw-semibold">Pilih Produk</label>
            <div class="d-flex gap-2">
              <select name="produk_id" class="form-select">
                <option value="">-- {{ isset($p_detail) ? $p_detail->name : 'Nama Produk' }} --</option>
                @foreach ($produk as $item)
                  <option value="{{ $item->id }}">{{ $item->id.' - '.$item->name }}</option>
                @endforeach
              </select>
              <button type="submit" class="btn btn-primary">Pilih</button>
            </div>
          </form>

          <!-- Form Tambah Detail -->
          <form action="/kasir/transaksi/detail/create" method="POST">
            @csrf
            <input type="hidden" name="transaksi_id" value="{{ $transaksi_id ?? '' }}">
            @if ($p_detail)
              <input type="hidden" name="produk_id" value="{{ $p_detail->id }}">
              <input type="hidden" name="produk_name" value="{{ $p_detail->name }}">
              <input type="hidden" id="hiddenSubtotal" name="subtotal" value="{{ $subtotal }}">
            @else
              <div class="alert alert-warning py-2">Silakan pilih produk terlebih dahulu</div>
            @endif

            <div class="mb-3">
              <label class="form-label">Nama Produk</label>
              <input type="text" class="form-control bg-light" value="{{ $p_detail->name ?? '' }}" disabled>
            </div>

            <div class="mb-3">
              <label class="form-label">Harga Satuan</label>
              <input type="text" class="form-control bg-light" value="{{ isset($p_detail) ? 'Rp. '.format_rupiah($p_detail->harga) : '' }}" disabled>
            </div>

            <div class="mb-3">
              <label class="form-label">QTY</label>
              <div class="d-flex align-items-center gap-2">
                <a href="?produk_id={{ request('produk_id') }}&act=min&qty={{ $qty }}" class="btn btn-primary px-3">-</a>
                <input id="qtyInput" type="number" name="qty" value="{{ $qty }}" class="form-control text-center w-25" min="1">
                <a href="?produk_id={{ request('produk_id') }}&act=plus&qty={{ $qty }}" class="btn btn-primary px-3">+</a>
              </div>
            </div>

            <div class="mb-3 fw-semibold">
              Subtotal: Rp. <span id="subtotalDisplay">{{ format_rupiah($subtotal) }}</span>
            </div>

            <div class="d-flex gap-2">
              <a href="{{ route('kasir.transaksi.cancel', ['id' => $transaksi_id ?? Request::segment(3)]) }}" class="btn btn-danger w-50">Kembali</a>
              <button type="submit" class="btn btn-primary w-50">Tambah</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Kolom kanan: Daftar produk -->
    <div class="col-md-6 mb-4">
      <!-- Card Judul -->
      <div class="card shadow-sm mb-2" style="background-color: #1cc88a; color: white;">
        <div class="card-body d-flex align-items-center">
          <i class="bi bi-card-list me-2"></i>
          <h5 class="mb-0">Daftar Transaksi</h5>
        </div>
      </div>

      <!-- Card Konten -->
      <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body p-2">
          <table class="table table-bordered table-hover align-middle mb-0">
            <thead style="background-color: #36b9cc; color: white;">
              <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>#</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($transaksi_detail as $index => $item)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $item->produk_name }}</td>
                  <td>{{ $item->qty }}</td>
                  <td>Rp. {{ format_rupiah($item->subtotal) }}</td>
                  <td><a href="/kasir/transaksi/detail/delete?id={{ $item->id }}" class="text-danger">x</a></td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="mt-3">

              <form id="selesaiForm" action="{{ route('kasir.transaksi.detail.done', ['id' => Request::segment(3)]) }}" method="GET">
                <input type="hidden" name="bayar" id="bayarHidden" value="{{ $dibayarkan ?? 0 }}">
                <button type="submit" id="selesaiBtn" class="btn btn-success w-100" disabled>Selesai</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ROW BAWAH: Hitung uang -->
  <div class="row">
    <div class="col-md-6">
      <!-- Card Judul -->
      <div class="card shadow-sm mb-2" style="background-color: #36b9cc; color: white;">
        <div class="card-body d-flex align-items-center">
          <i class="bi bi-cash-stack me-2"></i>
          <h5 class="mb-0">Pembayaran</h5>
        </div>
      </div>

      <!-- Card Konten -->
      <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body">

          <!-- ALERT ERROR TRANSAKSI BELUM DIBAYAR -->
          @if(session('error'))
            <div class="alert alert-danger fw-bold">
              {{ session('error') }}
            </div>
          @endif

          <form method="GET">
            <div class="mb-3">
              <label class="form-label">Total Belanja</label>
              <input type="text" value="{{ 'Rp. '.format_rupiah($transaksi->total) }}" class="form-control bg-light" readonly>
            </div>

            <div class="mb-3">
              <label class="form-label">Dibayarkan</label>
              <input type="number" name="dibayarkan" value="{{ $dibayarkan ?? '' }}" class="form-control" placeholder="Masukkan jumlah bayar">
            </div>

            <button type="submit" class="btn btn-primary w-100">Hitung</button>
          </form>

          <hr>

          <div class="mb-3">
            <label class="form-label">Kembalian</label>
            <input type="text" value="{{ 'Rp. '.format_rupiah($kembalian) }}" class="form-control bg-light" readonly>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const bayarInputField = document.querySelector('input[name="dibayarkan"]');
    const bayarHidden = document.getElementById('bayarHidden');
    const selesaiBtn = document.getElementById('selesaiBtn');
    const total = {{ $transaksi->total }};

    function checkBayar() {
        const bayar = parseInt(bayarInputField.value) || 0;
        bayarHidden.value = bayar;

        // ❗ Tambahkan kondisi biar gak aktif kalau belum ada produk (total = 0)
        if (total <= 0) {
            selesaiBtn.disabled = true;
            return;
        }

        selesaiBtn.disabled = bayar < total;
    }

    bayarInputField.addEventListener('input', checkBayar);
    checkBayar(); // inisialisasi saat halaman load
});
</script>



@if (isset($p_detail))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const qtyInput = document.getElementById('qtyInput');
    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const hiddenSubtotal = document.getElementById('hiddenSubtotal');
    const price = {{ $p_detail->harga ?? 0 }};

    function formatRupiah(n) {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updateSubtotal() {
        let qty = parseInt(qtyInput.value);
        if (isNaN(qty) || qty < 1) {
            qty = 1;
            qtyInput.value = qty;
        }
        const subtotal = qty * price;
        subtotalDisplay.textContent = formatRupiah(subtotal);
        hiddenSubtotal.value = subtotal;
    }

    if (qtyInput) {
        qtyInput.addEventListener('input', updateSubtotal);
    }
});
</script>
@endif

<style>
/* Card hover effect */
.card.shadow-sm:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    transition: box-shadow 0.3s ease;
}

/* Table row hover */
.table-hover tbody tr:hover {
    background-color: rgba(54, 185, 204, 0.15);
    transition: background-color 0.2s ease;
}

/* Button hover */
.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.2s ease;
}
</style>

