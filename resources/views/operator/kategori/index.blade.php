<div class="row p-2">
    <div class="col-m-6 col-sm-12">
        <div class="card">

            <div class="card-body">
                <h5><b>{{ $title }}</b></h5>

                <a href="/operator/kategori/create" class="btn btn-primary mb-2"><i class="fas fa-plus"></i>Tambah</a>
                <table class="table">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>

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
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </table>

                <div class="d-flex justify-content-center">
                    {{ $kategori->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
 <style>
         .content-wrapper {
           background: linear-gradient(135deg, hsl(250, 100%, 84%), hsl(250, 82%, 62%));
         }
    </style>