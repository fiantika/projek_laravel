<div class="row p-2">
    <div class="col-m-6 col-sm-12">
        <div class="card">

            <div class="card-body">
                <h5><b>{{ $title }}</b></h5>

                <a href="/operator/produk/create" class="btn btn-primary mb-2"><i class="fas fa-plus"></i>Tambah</a>
                <table class="table">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Berat (gr)</th>
                        <th>Stok</th>
                        <th>Gambar</th>
                        <th>Action</th>
                    </tr>

                    @foreach ($produk as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->kategori ? $item->kategori->name : '-' }}</td>
                        <td>Rp. {{ format_rupiah($item->harga) }}</td>
                        <td>{{ $item->berat ?? '-' }}</td>
                        <td>{{ $item->stok ?? 0 }}</td>
                        <td>
                            @if($item->gambar)
                                <img src="{{ asset($item->gambar) }}" width="60px" alt="{{ $item->name }}">
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="/operator/produk/{{ $item->id }}/edit" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="/operator/produk/{{ $item->id }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin mau hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </table>

                <div class="d-flex justify-content-center">
                    {{ $produk->links() }}
                </div>
            </div>
        </div>
    </div>
</div>