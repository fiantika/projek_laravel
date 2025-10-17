<div class="row p-2">
    <div class="col-md-6 col-sm-12">
        <div class="card shadow" style="border-radius: 12px;">
            
            <!-- Header biru -->
            <div style="background: linear-gradient(135deg, #4e73df, #6f42c1);
                        color: white;
                        padding: 15px 20px;
                        border-radius: 12px 12px 0 0;">
                <h5 class="mb-0 fw-bold" style="letter-spacing: 0.5px;">
                    {{ $title }}
                </h5>
            </div>

            <!-- Isi form -->
            <div class="card-body">
                @isset($kategori)
                <form action="/operator/kategori/{{ $kategori->id }}" method="POST">
                    @method('PUT')
                @else
                <form action="/operator/kategori" method="POST">
                @endisset
                    @csrf
                    <label for="">Nama Kategori</label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Nama Kategori"
                           value="{{ isset($kategori) ? $kategori->name : old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <a href="/operator/kategori" class="btn btn-info mt-3">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
