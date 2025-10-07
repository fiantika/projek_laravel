<div class="card mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center mb-3" style="padding-top: 20px" style="gap: 30px;">
        <h4><b>{{ $title }}</b></h4>
        <a href="/operator/produk/create"  class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>
</div>
</div>

<div class="row p-2">

    @foreach ($produk as $item)
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card shadow-sm h-100 border-0 rounded-lg">
                {{-- Gambar produk --}}
                <img src="{{ $item->image ?? 'https://via.placeholder.com/300x200?text=No+Image' }}" 
                     class="card-img-top" alt="{{ $item->name }}" 
                     style="height: 200px; object-fit: cover;">

                <div class="card-body d-flex flex-column">
                    {{-- Nama produk --}}
                    <h5 class="card-title text-dark fw-bold">{{ $item->name }}</h5>

                    {{-- Harga & Berat --}}
                    <p class="mb-1 text-success fw-bold">
                        Rp{{ number_format($item->harga, 0, ',', '.') }}
                    </p>
                    <p class="text-muted">
                        Berat: {{ $item->berat }} gram
                    </p>

                    {{-- Tombol aksi --}}
                    <div class="mt-auto d-flex justify-content-between">
                        <a href="/operator/produk/{{ $item->id }}/edit" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        <form action="/operator/produk/{{ $item->id }}" method="POST" onsubmit="return confirm('Yakin mau hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
       <style>
         .content-wrapper {
           background: linear-gradient(135deg, hsl(250, 100%, 84%), hsl(250, 82%, 62%));
         }
    </style>
</div>

<div class="d-flex justify-content-center">
    {{ $produk->links() }}
</div>
