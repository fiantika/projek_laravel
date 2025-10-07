  <!-- Main Sidebar Container -->
  <aside class="main-sidebar elevation-4 sidebar-pastel">

    <!-- Brand Logo -->
    <div href="index3.html" class="brand-link">
      <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">}] -->
      <span class="brand-text font-weight-light">Tokoku</span>
      <i class="fas fa-cash-register nav-icon"></i>
  </div>

    

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
          <li class="nav-item">
            <a href="/kasir/dashboard" class="nav-link {{ Request::is('kasir/dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/kasir/transaksi" class="nav-link {{ Request::is('kasir/transaksi*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p>Transaksi</p>
            </a>
          </li>
        </li>

    <li class="nav-separator"></li>

      <li class="nav-item">
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="nav-link border-0 bg-transparent text-left w-100">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Logout</p>
      </button>
    </form>
  </li>



        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <style>

.sidebar-pastel {
  --sidebar-bg: hsl(250, 55%, 66%);
  --sidebar-accent: #c4b5fd;
  --sidebar-accent-strong: #a78bfa;
  --sidebar-text: #ffffff;
  --sidebar-border: #e5e7eb;
}

.sidebar-pastel.main-sidebar {
  background-color: var(--sidebar-bg) !important;
  color: var(--sidebar-text);
}

.sidebar-pastel .brand-link {
  background-color: var(--sidebar-bg) !important;
  color: var(--sidebar-text) !important;
  border-bottom: 1px solid var(--sidebar-border);
  font-weight: 600;
}

.sidebar-pastel .brand-link:hover {
  background-color: rgba(196, 181, 253, 0.25);
}

.sidebar-pastel .sidebar {
  padding-top: 0.25rem;
}

/* Nav links */
.sidebar-pastel .nav-sidebar .nav-link {
  color: var(--sidebar-text) !important;
  border-radius: 10px;
  margin: 4px 8px;
  transition: background-color .2s ease, color .2s ease, box-shadow .2s ease;
}

.sidebar-pastel .nav-sidebar .nav-link .nav-icon {
  color: var(--sidebar-accent-strong) !important;
}

.sidebar-pastel .nav-sidebar .nav-link:hover {
  background-color: rgba(167, 139, 250, 0.15);
  color: var(--sidebar-text) !important;
}

.sidebar-pastel .nav-sidebar .nav-link:focus-visible {
  outline: 2px solid var(--sidebar-accent-strong);
  outline-offset: 2px;
}

.sidebar-pastel .nav-sidebar .nav-link.active {
  background-color: var(--sidebar-accent) !important;
  color: var(--sidebar-text) !important;
  box-shadow: 0 2px 6px rgba(167, 139, 250, 0.35);
}

.sidebar-pastel .nav-sidebar .nav-link.active .nav-icon {
  color: var(--sidebar-text) !important;
}

.sidebar-pastel .nav-sidebar > .nav-item > .nav-link.active,
.sidebar-pastel .nav-sidebar > .nav-item > .nav-link:hover {
  border-left: 3px solid var(--sidebar-accent-strong);
}

/* Treeview */
.sidebar-pastel .nav-treeview > .nav-item > .nav-link {
  margin-left: 16px;
  border-radius: 8px;
}

/* Elevation softness */
.sidebar-pastel.elevation-4 {
  box-shadow: 0 10px 20px -5px rgba(167, 139, 250, 0.25), 0 6px 10px -6px rgba(31, 41, 55, 0.2) !important;
}

/* Hilangkan scrollbar sidebar */
.sidebar .os-scrollbar,
.sidebar .os-scrollbar-handle {
  display: none !important;
}

/* Pastikan sidebar gak bisa geser horizontal */
.sidebar {
  overflow-x: hidden !important;
}

/* Bikin tulisan Tokoku | Kasir rata tengah */
.sidebar-pastel .brand-link {
  display: flex;
  justify-content: center;   /* rata tengah horizontal */
  align-items: center;       /* rata tengah vertical */
  text-align: center;
  font-weight: bold;
  font-size: 18px;
}

.sidebar-pastel .brand-link {
  display: flex;
  justify-content: center;   /* tengahin isi */
  align-items: center;       /* vertikal sejajar */
  gap: 8px;                  /* jarak antara logo & tulisan */
  text-align: center;
  font-weight: bold;
  font-size: 18px;
}

.sidebar-pastel .brand-link .brand-image {
  height: 30px;       /* tinggi logo */
  width: auto;        /* biar proporsional */
  border-radius: 6px; /* opsional, biar sudut logo agak halus */
}

/* Styling tombol logout biar mirip nav-link */
.sidebar-pastel .btn-logout {
  background: none;
  border: none;
  width: 100%;
  text-align: left;
  padding: 0.5rem 1rem;
  color: var(--sidebar-text);
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  border-radius: 10px;
  transition: background-color .2s;
}

.sidebar-pastel .btn-logout:hover {
  background-color: rgba(251, 251, 251, 0.15);
  color: var(--sidebar-text);
}

.sidebar-pastel .btn-logout .nav-icon {
  color: var(--sidebar-accent-strong);
}

.nav-separator {
  border-top: 1px solid rgb(255, 255, 255); /* garis tipis */
  margin: 10px 0;
}

/* Normal (sidebar terbuka) */
.brand-link {
  display: flex;
  align-items: center;
  justify-content: center; /* biar tengah di mode normal */
}

/* Kalau sidebar collapse */
.sidebar-collapse .brand-link {
  justify-content: center;   /* paksa logo ke tengah */
  text-align: center;
}

.sidebar-collapse .brand-link .brand-text {
  display: none; /* teks 'Tokoku | Kasir' sembunyi pas collapse */
}


  </style>

  <div class="content-wrapper">