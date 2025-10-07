<div class="card shadow mb-4">
  <div class="card-body d-flex align-items-center">
    <h1 class="h4 mb-0 font-weight-bold text-gray-800">
      📊 Dashboard | Admin
    </h1>
  </div>
</div>


<div class="row">
    <!-- Total Produk -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow h-100 border-left-primary hover-shadow">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-box fa-2x text-primary"></i>
                </div>
                <div>
                    <h6 class="card-title text-primary mb-1">Total Produk</h6>
                    <h4 class="font-weight-bold">{{ \App\Models\Produk::count() }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Kategori -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow h-100 border-left-success hover-shadow">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-tags fa-2x text-success"></i>
                </div>
                <div>
                    <h6 class="card-title text-success mb-1">Total Kategori</h6>
                    <h4 class="font-weight-bold">{{ \App\Models\Kategori::count() }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Total User -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow h-100 border-left-warning hover-shadow">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-users fa-2x text-warning"></i>
                </div>
                <div>
                    <h6 class="card-title text-warning mb-1">Total User</h6>
                    <h4 class="font-weight-bold">{{ \App\Models\User::count() }}</h4>
                </div>
            </div>
        </div>
    </div>
    <style>
         .content-wrapper {
           background: linear-gradient(135deg, hsl(250, 100%, 84%), hsl(250, 82%, 62%));
         }
    </style>
</div>


