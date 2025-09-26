  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">}] -->
      <span class="brand-text font-weight-light">TokoKu Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">

        
        {{-- <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
  <!-- Sidebar untuk Admin -->
  @if(auth()->user()->role == 'admin')
    <li class="nav-item">
      <a href="/post" class="nav-link {{ Request::is('/') ? 'active' : '' }}">
        <i class="nav-icon fas fa-th"></i>
        <p>Dashboard</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="/admin/transaksi" class="nav-link {{ Request::is('admin/transaksi*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-exchange-alt"></i>
        <p>Transaksi</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="/admin/produk" class="nav-link {{ Request::is('admin/produk*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-table"></i>
        <p>Produk</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="/admin/kategori" class="nav-link {{ Request::is('admin/kategori*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-list"></i>
        <p>Kategori</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="/admin/user" class="nav-link {{ Request::is('admin/user*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i>
        <p>User</p>
      </a>
    </li>
  @endif

  <!-- Sidebar untuk Kasir -->
  @if(auth()->user()->role == 'kasir')
    <li class="nav-item">
      <a href="/post" class="nav-link {{ Request::is('/') ? 'active' : '' }}">
        <i class="nav-icon fas fa-th"></i>
        <p>Dashboard</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="/admin/transaksi" class="nav-link {{ Request::is('admin/transaksi*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-exchange-alt"></i>
        <p>Transaksi</p>
      </a>
    </li>
  @endif
</ul> --}}

        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Dashboard menu is always available -->
          <li class="nav-item">
            <a href="/admin/dashboard" class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>Dashboard</p>
            </a>
          </li>
          @if(auth()->check() && auth()->user()->role == 'admin')
          <!-- Only administrators can manage produk, kategori and user. Admin does not handle transactions here. -->
          <li class="nav-item">
            <a href="/admin/produk" class="nav-link {{ Request::is('admin/produk*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-table"></i>
              <p>Produk</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/admin/kategori" class="nav-link {{ Request::is('admin/kategori*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-list"></i>
              <p>Kategori</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/admin/user" class="nav-link {{ Request::is('admin/user*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>User</p>
            </a>
          </li>
          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <div class="content-wrapper">