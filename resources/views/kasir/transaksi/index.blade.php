<div class="row p-2">
    <div class="col-m-6 col-sm-12">
        <div class="card">

            <div class="card-body">
                <h5><b>{{ $title }}</b></h5>

                <a href="/kasir/transaksi/create" class="btn btn-primary mb-2"><i class="fas fa-plus"></i>Tambah</a>
                <table class="table">
                    <tr>
                        <th>No</th>
                        <th>Nama Kasir</th>
                        <th>Tanggal</th>
                        <th>Action</th>
                    </tr>

                   @foreach ($transaksi as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            <a href="{{ url('/kasir/transaksi/' . $item->id) }}" class="btn btn-sm btn-info">View</a>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                   </table>

                <div class="d-flex justify-content-center">
                    {{ $transaksi->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
