<!-- STRIP HEADER -->
<div class="col-md-12 mb-1"> <!-- kasih sedikit jarak kecil -->
  <div class="d-flex justify-content-between align-items-center px-4 py-3 shadow-sm"
       style="background: linear-gradient(135deg, #4e73df, #6f42c1);
              border-radius: 14px;
              border-left: 5px solid #fff;
              height: 70px;
              color: #fff;">
              
    <!-- KIRI: Total Produk -->
    <div class="d-flex align-items-center gap-2">
      <div class="shadow-sm px-4 py-2" 
           style="background-color: white; border-radius: 10px; min-width: 120px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <small class="text-muted" style="font-weight: 500; font-size: 0.9rem; line-height: 1;">Total Produk</small>
        <span class="text-primary fw-semibold" style="font-size: 0.9rem; line-height: 1;">{{ $produk->total() }}</span>
      </div>
    </div>

    <!-- TENGAH: Judul -->
    <div class="flex-grow-1 text-center">
      <h5 class="mb-0 fw-semibold"
          style="color: #ffffff; letter-spacing: 0.5px; font-size: 1.4rem;">
        {{ $title }}
      </h5>
    </div>

    <!-- KANAN: Tombol Tambah -->
    <div>
      <a href="/operator/produk/create"
         class="btn btn-light shadow-sm px-4 py-2"
         style="border-radius: 10px; font-size: 0.9rem; font-weight: 600; color: #4e73df;">
        <i class="fas fa-plus me-1"></i> Tambah
      </a>
    </div>
  </div>
</div>

<!-- GRID WRAPPER -->
<div style="
    background-color: #cfe9ff; 
    border-radius: 16px; 
    padding: 30px 20px 40px 20px; 
    margin-top: 15px; /* jarak kecil dari header */
    position: relative;">
    
  <!-- buat efek overlap halus -->
  <div style="
      position: absolute;
      top: -15px;
      left: 0;
      right: 0;
      height: 15px;
      border-top-left-radius: 16px;
      border-top-right-radius: 16px;">
  </div>

  <div class="row gx-4 gy-4 custom-grid-spacing">
    @foreach ($produk as $item)
    <div class="col-6 col-md-4 col-lg-3 col-xl-2-4 mb-4">
      <div class="card product-card border-0 shadow-sm position-relative overflow-hidden"
        style="border-radius: 18px;
                transition: all 0.3s ease;
                background-color: hsl(225, 91%, 71%);
                color: #ffffff;
                height: 100%;">
        
        <!-- GAMBAR PRODUK -->
        <div style="height: 180px; width: 100%; overflow: hidden; border-radius: 18px 18px 0 0;">
          @if($item->gambar)
          <img src="{{ asset($item->gambar) }}" 
               class="w-100 h-100 object-fit-cover" 
               alt="{{ $item->name }}">
          @else
          <div class="d-flex align-items-center justify-content-center bg-light h-100">
            <span class="text-muted small">No Image</span>
          </div>
          @endif
        </div>

        <!-- DESKRIPSI PRODUK -->
        <div class="card-body text-center p-3 position-relative"
             style="background: transparent; border-radius: 0 0 18px 18px;">
          <h6 class="fw-bold mb-1 text-truncate"
              style="font-size: 0.95rem; color: #fff;">
            {{ $item->name }}
          </h6>
          <small class="d-block mb-1" style="font-size: 0.8rem; color: #eee;">
            {{ $item->kategori ? $item->kategori->name : '-' }}
          </small>
          <p class="fw-semibold mb-2" style="font-size: 0.9rem; color: #ffffff;">
            Rp. {{ format_rupiah($item->harga) }}
          </p>

          @php
            $stok = $item->stok ?? 0;
            $stokClass = $stok == 0 ? 'bg-danger' : ($stok < 10 ? 'bg-warning text-dark' : 'bg-success');
          @endphp
          <span class="badge {{ $stokClass }} px-3 py-2 fw-semibold"
                style="border-radius: 8px; font-size: 0.75rem;">
            {{ $stok == 0 ? 'Stok Habis' : 'Stok: ' . $stok }}
          </span>

          <!-- TOMBOL AKSI -->
          <div class="action-buttons position-absolute bottom-0 start-0 p-2 d-flex gap-1">
            <a href="/operator/produk/{{ $item->id }}/edit"
               class="btn btn-light btn-sm p-1 px-2 shadow-sm" title="Edit">
              <i class="fas fa-edit fa-xs text-primary"></i>
            </a>
            <form action="/operator/produk/{{ $item->id }}" method="POST"
                  onsubmit="return confirm('Yakin mau hapus data ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm p-1 px-2 shadow-sm" title="Hapus">
                <i class="fas fa-trash fa-xs"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>

<style>
  @media (min-width: 1200px) {
    .col-xl-2-4 {
      flex: 0 0 20%;
      max-width: 20%;
    }
  }

  .custom-grid-spacing > [class*="col-"] {
    margin-bottom: 30px !important;
  }

  .product-card {
    min-height: 320px;
    max-height: 340px;
  }

  .product-card img {
    object-fit: cover;
    transition: transform 0.4s ease;
  }

  .product-card:hover img {
    transform: scale(1.05);
  }

  .product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.15);
  }

  .product-card .action-buttons {
    opacity: 0;
    transform: translateY(8px);
    transition: all 0.3s ease;
  }

  .product-card:hover .action-buttons {
    opacity: 1;
    transform: translateY(0);
  }

  .btn-sm {
    font-size: 0.7rem;
    border-radius: 6px;
  }

  .card-body {
    position: relative;
    padding-bottom: 42px !important;
  }
</style>
