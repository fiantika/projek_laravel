<div class="row p-2">
    <div class="col-md-12 col-sm-12">
       <!-- Card Header -->
        <div class="card shadow mb-3" style="border-radius: 12px;">
            <div class="card-body" style="background: linear-gradient(135deg, #4e73df, #6f42c1); color: white;">
                <div class="col-12 d-flex justify-content-between align-items-center" style="gap: 30px;">
                    <h5 class="mb-0" style="padding-top: 5px;"><b>Manajemen Kategori</b></h5>
                    <a href="/operator/kategori/create" class="btn" 
                       style="background-color: white; color: #4e73df; border: none; font-weight: bold;">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                </div>
            </div>
        </div>


        <!-- Card Tabel -->
        <div class="card shadow" style="border-radius: 12px;">
            <div class="card-body">
                <table class="table table-hover table-bordered">
    <thead style="background-color: #4e73df; color: white;">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kategori as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->name }}</td>
            <td>
                <a href="/operator/kategori/{{ $item->id }}/edit" class="btn btn-info btn-sm">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="/operator/kategori/{{ $item->id }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin mau hapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


                <div class="d-flex justify-content-center">
                    {{ $kategori->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
