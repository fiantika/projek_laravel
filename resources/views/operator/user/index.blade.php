<div class="row">
    <div class="col-md-12">
        <!-- Card Header -->
        <div class="card shadow mb-3" style="border-radius: 12px;">
            <div class="card-body" style="background: linear-gradient(135deg, #4e73df, #6f42c1); color: white;">
                <div class="col-12 d-flex justify-content-between align-items-center" style="gap: 30px;">
                    <h5 class="mb-0" style="padding-top: 5px;"><b>{{ $title }}</b></h5>
                    <a href="/operator/user/create" class="btn" 
                       style="background-color: white; color: #4e73df; border: none; font-weight: bold;">
                        <i class="fa fa-plus me-1"></i> Tambah
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Tabel -->
        <div class="card shadow" style="border-radius: 12px;">
            <div class="card-body">
                @if (session()->has('success'))
                    <div class="alert alert-success"><i class="fas fa-check"></i>
                        {{ session('success') }}
                    </div>                    
                @endif

                <table class="table table-bordered table-hover">
                     <thead style="background-color: #4e73df; color: white;">
                    <tr>
                    <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th> <!-- 🔹 Tambahan -->
                        <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                            @if($user->role == 'operator')
                                <span class="badge bg-primary">Admin</span>
                            @elseif($user->role == 'keuangan')
                                <span class="badge bg-success">Kasir</span>
                            @else
                                <span class="badge bg-secondary">Tidak Diketahui</span>
                            @endif
                            </td>
                            <td class="text-center" style="white-space: nowrap;">
                            <div class="btn-group" role="group">
                                <a href="/operator/user/{{ $user->id }}/edit" 
                                class="btn btn-sm btn-warning text-white" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="/operator/user/{{ $user->id }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                        </tr>
                        @endforeach
                    </tbody>
                    </table>

            </div>
        </div>
    </div>
</div>


