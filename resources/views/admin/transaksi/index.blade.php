<div class="row p-2">
    <div class="col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h5><b>{{ $title }}</b></h5>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <a href="/admin/transaksi/create" class="btn btn-primary mb-2">
                    <i class="fas fa-plus"></i> Tambah Transaksi
                </a>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Transaksi</th>
                                <th>Nama Kasir</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksi as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>#{{ $item->id }}</td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td>Rp. {{ number_format($item->total, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-{{ $item->status == 'selesai' ? 'success' : 'warning' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ url('/admin/transaksi/' . $item->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    @if($item->status == 'pending')
                                        <a href="{{ url('/admin/transaksi/' . $item->id . '/edit') }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data transaksi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $transaksi->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
