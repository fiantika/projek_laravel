<div class="card mb-3" style="border-radius: 12px;">
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #4e73df, #6f42c1); 
                color: white; 
                display: flex; 
                justify-content: space-between; 
                align-items: center; 
                padding: 15px 20px; 
                border-radius: 12px 12px 0 0;">
        <!-- Judul Tengah -->
        <h5 class="mb-0 fw-bold text-center flex-grow-1" style="letter-spacing: 0.5px;">
            {{ $title }}
        </h5>

        <!-- Tombol Tambah -->
        <a href="/kasir/transaksi/create" 
           class="btn shadow-sm"
           style="background-color: white; color: #4e73df; font-weight: bold; border-radius: 10px; border: none;">
            <i class="fas fa-plus me-1"></i> Tambah
        </a>
    </div>

    <!-- Tabel -->
    <div class="card-body">
        <div class="row p-2">
            <div class="col-md-12 col-sm-12">
                <div class="card shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-0" style="overflow-x: auto;">
                        <table class="table table-bordered table-hover mb-0" style="border: 1px solid #dee2e6;">
                            <thead style="background-color: #4e73df; color: white;">
                                <tr>
                                    <th style="padding: 12px;">No</th>
                                    <th style="padding: 12px;">Nama Kasir</th>
                                    <th style="padding: 12px;">Nama Produk</th>
                                    <th style="padding: 12px;">Total Barang</th>
                                    <th style="padding: 12px;">Total Harga</th>
                                    <th style="padding: 12px;">Tanggal</th>
                                    <th style="padding: 12px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi as $item)
                                @php
                                    $productNames = $item->details->pluck('produk_name')->implode(', ');
                                    $totalQty = $item->details->sum('qty');
                                @endphp
                                <tr>
                                    <td style="padding: 10px;">{{ $loop->iteration }}</td>
                                    <td style="padding: 10px;">{{ $item->user->name ?? '-' }}</td>
                                    <td style="padding: 10px;">{{ $productNames ?: '-' }}</td>
                                    <td style="padding: 10px;">{{ $totalQty ?: '0' }}</td>
                                    <td style="padding: 10px;">Rp. {{ format_rupiah($item->total) }}</td>
                                    <td style="padding: 10px;">{{ $item->created_at }}</td>
                                    <td style="padding: 10px;">
                                        <a href="{{ url('/kasir/transaksi/' . $item->id) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-2">
                            {{ $transaksi->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if(session('swal'))
    const swalData = @json(session('swal'));
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: swalData.icon,
        title: swalData.title,
        text: swalData.text,
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    });
@elseif(session('success'))
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    });
@elseif(session('error'))
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: 'Gagal!',
        text: "{{ session('error') }}",
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    });
@endif
</script>
