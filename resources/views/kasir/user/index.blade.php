<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                
                <h5><b>{{ $title }}</b></h5>

                <a href="/admin/user/create" class="btn btn-primary mb-3"><i class="fa fa-plus me-1"></i> Tambah </a>

                @if (session()->has('success'))
                <div class="alert alert-success"><i class="fas fa-check"></i>
                {{ session('success') }}
                </div>                    
                @endif

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($user as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>
                                <a href="{{ route('user.edit', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('user.destroy', $item->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin mau hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
