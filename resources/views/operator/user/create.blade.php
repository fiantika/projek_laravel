<div class="container-fluid pt-2">
    <div class="row">
        <div class="col-md-6">
            <div class="card">

                <!-- Header biru -->
                <div class="card-header" 
                     style="background: linear-gradient(135deg, #4e73df, #6f42c1); color: white; border-radius: 8px 8px 0 0;
                            display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <i class="fas fa-user-plus"></i>
                    <h5 class="mb-0"><b>{{ isset($user) ? 'Edit User' : 'Tambah Data' }}</b></h5>
                </div>

                <div class="card-body">
                    @isset($user)
                        <form action="/operator/user/{{ $user->id }}" method="POST">
                        @method('PUT')
                    @else
                        <form action="/operator/user" method="POST">
                    @endisset

                    @csrf

                    <div class="form-group">
                        <label for=""><b>Nama Lengkap</b></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            placeholder="Nama Lengkap" value="{{ isset($user) ? $user->name : '' }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for=""><b>Email</b></label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                            placeholder="Email" value="{{ isset($user) ? $user->email : '' }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @isset($user)
                    <!-- Password Lama hanya muncul saat edit -->
                    <div class="form-group">
                        <label for=""><b>Password Lama</b></label>
                        <input type="password" class="form-control @error('old_password') is-invalid @enderror" 
                               name="old_password" placeholder="Masukkan password lama">
                        @error('old_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endisset

                    <div class="form-group">
                        <label for=""><b>Password Baru</b></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" placeholder="{{ isset($user) ? 'Kosongkan jika tidak ingin ganti' : 'Password' }}">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for=""><b>Konfirmasi Password Baru</b></label>
                        <input type="password" class="form-control @error('re_password') is-invalid @enderror" 
                               name="re_password" placeholder="{{ isset($user) ? 'Kosongkan jika tidak ingin ganti' : 'Konfirmasi Password' }}">
                        @error('re_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for=""><b>Role</b></label>
                        <select name="role" class="form-control @error('role') is-invalid @enderror">
                            <option value="">-- Pilih Role --</option>
                            <option value="keuangan" {{ (isset($user) && $user->role == 'keuangan') ? 'selected' : '' }}>Kasir</option>
                            <option value="operator" {{ (isset($user) && $user->role == 'operator') ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <a href="/operator/user" class="btn btn-secondary mt-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary mt-2">
                        <i class="fas fa-save"></i> Simpan
                    </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
