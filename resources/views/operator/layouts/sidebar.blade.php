  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <span class="brand-text font-weight-light">TokoKu Operator</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Dashboard menu is always available -->
          <li class="nav-item">
            <a href="/operator/dashboard" class="nav-link {{ Request::is('operator/dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <!-- Operator has access to product, category and user management -->
          <li class="nav-item">
            <a href="/operator/produk" class="nav-link {{ Request::is('operator/produk*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-table"></i>
              <p>Produk</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/operator/kategori" class="nav-link {{ Request::is('operator/kategori*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-list"></i>
              <p>Kategori</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/operator/user" class="nav-link {{ Request::is('operator/user*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>User</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <div class="content-wrapper">