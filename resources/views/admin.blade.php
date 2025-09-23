<x-side-bar>
        <!-- Welcome Card -->
      <div class="welcome-card">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h2 class="mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
            <p class="mb-0 opacity-75">Anda login sebagai <strong>{{ ucfirst(Auth::user()->role) }}</strong>. Kelola sistem dengan mudah dari dashboard ini.</p>
          </div>
          <div class="col-md-4 text-md-end">
            <i class="fas fa-user-shield" style="font-size: 4rem; opacity: 0.3;"></i>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="stats-card primary">
            <div class="row align-items-center">
              <div class="col">
                <div class="text-muted small">Total User</div>
                <div class="h4 mb-0">{{ App\Models\User::count() }}</div>
              </div>
              <div class="col-auto">
                <div class="stats-icon primary">
                  <i class="fas fa-users"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="stats-card success">
            <div class="row align-items-center">
              <div class="col">
                <div class="text-muted small">Total Transaksi</div>
                <div class="h4 mb-0">0</div>
              </div>
              <div class="col-auto">
                <div class="stats-icon success">
                  <i class="fas fa-receipt"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="stats-card warning">
            <div class="row align-items-center">
              <div class="col">
                <div class="text-muted small">Total Produk</div>
                <div class="h4 mb-0">0</div>
              </div>
              <div class="col-auto">
                <div class="stats-icon warning">
                  <i class="fas fa-box"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="stats-card danger">
            <div class="row align-items-center">
              <div class="col">
                <div class="text-muted small">Total Kategori</div>
                <div class="h4 mb-0">0</div>
              </div>
              <div class="col-auto">
                <div class="stats-icon danger">
                  <i class="fas fa-tags"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">
                <i class="fas fa-bolt me-2"></i>
                Aksi Cepat
              </h5>
            </div>
            <div class="card-body">
              <div class="quick-actions">
                <a href="/admin/user/create" class="btn btn-primary me-2 mb-2">
                  <i class="fas fa-user-plus me-1"></i>
                  Tambah User
                </a>
                <a href="/admin/transaksi/create" class="btn btn-success me-2 mb-2">
                  <i class="fas fa-plus me-1"></i>
                  Transaksi Baru
                </a>
                <a href="/admin/produk/create" class="btn btn-info me-2 mb-2">
                  <i class="fas fa-box me-1"></i>
                  Tambah Produk
                </a>
                <a href="/admin/kategori/create" class="btn btn-warning me-2 mb-2">
                  <i class="fas fa-tags me-1"></i>
                  Tambah Kategori
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity (Optional) -->
      <div class="row mt-4">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">
                <i class="fas fa-clock me-2"></i>
                Aktivitas Terbaru
              </h5>
            </div>
            <div class="card-body">
              <div class="text-center text-muted py-4">
                <i class="fas fa-info-circle fa-2x mb-3"></i>
                <p>Belum ada aktivitas terbaru</p>
              </div>
            </div>
          </div>
        </div>
      </div>
</x-side-bar>