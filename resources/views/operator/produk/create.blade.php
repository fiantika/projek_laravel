<div class="row p-2">
    <div class="col-md-6 col-sm-12">
        <div class="card shadow" style="border-radius: 12px;">
            
            <!-- Header Card -->
            <div class="card-header text-white"
                 style="background: linear-gradient(135deg, #4e73df, #6f42c1); 
                        border-radius: 12px 12px 0 0;">
                <h5 class="mb-0 fw-bold text-center">
                    <i></i>{{ $title }}
                </h5>
            </div>

            <!-- Body Card -->
            <div class="card-body">
                @isset($produk)
                <form action="/operator/produk/{{ $produk->id }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                @else
                <form action="/operator/produk" method="POST" enctype="multipart/form-data">
                @endisset
                
                    @csrf

                    <label for="">Nama Produk</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                        placeholder="Nama Produk" value="{{ isset($produk) ? $produk->name : old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label for="">Kategori</label>
                    <select name="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror">
                        <option value="">--Kategori--</option>
                        @foreach ($kategori as $item)
                            <option value="{{ $item->id }}" {{ isset($produk) && $item->id == $produk->kategori_id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label for="">Harga</label>
                    <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" 
                        placeholder="Harga" value="{{ isset($produk) ? $produk->harga : old('harga') }}">
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label for="">Berat (gram)</label>
                    <input type="number" step="0.01" name="berat" class="form-control @error('berat') is-invalid @enderror" 
                        placeholder="Berat" value="{{ isset($produk) ? $produk->berat : old('berat') }}">
                    @error('berat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label for="">Gambar</label>
                    <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror">
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @isset($produk)
                        @if($produk->gambar)
                            <div class="mt-2">
                                <img src="{{ asset($produk->gambar) }}" width="100px" alt="">
                            </div>
                        @endif
                    @endisset

                    <div class="mt-3">
                        <a href="/operator/produk" class="btn btn-info">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
