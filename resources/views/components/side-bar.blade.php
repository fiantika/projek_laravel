<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  
  <style>
    :root {
      --sidebar-width: 280px;
      --header-height: 60px;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: var(--sidebar-width);
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      z-index: 1000;
      transform: translateX(-100%);
      transition: transform 0.3s ease;
      overflow-y: auto;
    }

    .sidebar.show {
      transform: translateX(0);
    }

    .sidebar-header {
      padding: 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-menu {
      padding: 0;
      margin: 0;
      list-style: none;
    }

    .sidebar-menu li {
      margin: 0;
    }

    .sidebar-menu a {
      display: flex;
      align-items: center;
      padding: 12px 20px;
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: all 0.3s ease;
      border-left: 3px solid transparent;
    }

    .sidebar-menu a:hover {
      background: rgba(255, 255, 255, 0.1);
      color: white;
      border-left-color: #fff;
    }

    .sidebar-menu a.active {
      background: rgba(255, 255, 255, 0.2);
      color: white;
      border-left-color: #ffd700;
    }

    .sidebar-menu i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }

    .main-content {
      margin-left: 0;
      transition: margin-left 0.3s ease;
      min-height: 100vh;
      background: #f8f9fa;
    }

    .main-content.sidebar-open {
      margin-left: var(--sidebar-width);
    }

    .header {
      background: white;
      height: var(--header-height);
      box-shadow: 0 2px 4px rgba(0,0,0,.1);
      display: flex;
      align-items: center;
      padding: 0 20px;
      position: sticky;
      top: 0;
      z-index: 999;
    }

    .content-wrapper {
      padding: 30px;
    }

    .stats-card {
      background: white;
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0 2px 10px rgba(0,0,0,.1);
      border-left: 4px solid;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stats-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 20px rgba(0,0,0,.15);
    }

    .stats-card.primary {
      border-left-color: #007bff;
    }

    .stats-card.success {
      border-left-color: #28a745;
    }

    .stats-card.warning {
      border-left-color: #ffc107;
    }

    .stats-card.danger {
      border-left-color: #dc3545;
    }

    .stats-icon {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      color: white;
    }

    .stats-icon.primary { background: #007bff; }
    .stats-icon.success { background: #28a745; }
    .stats-icon.warning { background: #ffc107; }
    .stats-icon.danger { background: #dc3545; }

    .welcome-card {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 15px;
      padding: 30px;
      margin-bottom: 30px;
    }

    .sidebar-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(0,0,0,0.5);
      z-index: 999;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }

    .sidebar-overlay.show {
      opacity: 1;
      visibility: visible;
    }

    @media (min-width: 992px) {
      .sidebar {
        transform: translateX(0);
      }
      
      .main-content {
        margin-left: var(--sidebar-width);
      }
      
      .sidebar-overlay {
        display: none;
      }
    }

    .role-badge {
      background: rgba(255, 255, 255, 0.2);
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .quick-actions .btn {
      border-radius: 8px;
      padding: 10px 20px;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .quick-actions .btn:hover {
      transform: translateY(-2px);
    }
  </style>
</head>

<body>
  <!-- Sidebar Overlay for mobile -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <h4 class="mb-1">
        <i class="fas fa-tachometer-alt me-2"></i>
        Admin Panel
      </h4>
      <span class="role-badge">{{ ucfirst(Auth::user()->role) }}</span>
    </div>
    
    <ul class="sidebar-menu">
      <li>
        <a href="/admin/dashboard" class="active">
          <i class="fas fa-home"></i>
          Dashboard
        </a>
      </li>
      <li>
        <a href="/admin/transaksi">
          <i class="fas fa-receipt"></i>
          Transaksi
        </a>
      </li>
      <li>
        <a href="/admin/produk">
          <i class="fas fa-box"></i>
          Produk
        </a>
      </li>
      <li>
        <a href="/admin/kategori">
          <i class="fas fa-tags"></i>
          Kategori
        </a>
      </li>
      <li>
        <a href="/admin/user">
          <i class="fas fa-users"></i>
          User
        </a>
      </li>

      <hr style="border-color: rgba(255,255,255,0.2); margin: 20px 0;">
      
      <li>
        <a href="#">
          <i class="fas fa-user"></i>
          Profil
        </a>
      </li>
      <li>
        <a href="#">
          <i class="fas fa-cog"></i>
          Pengaturan
        </a>
      </li>
      <li>
        <a href="/logout" class="text-danger">
          <i class="fas fa-sign-out-alt"></i>
          Logout
        </a>
      </li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <!-- Header -->
    <div class="header">
      <button class="btn btn-outline-secondary d-lg-none me-3" id="sidebarToggle">
        <i class="fas fa-bars"></i>
      </button>
      
      <h5 class="mb-0 flex-grow-1">Dashboard {{ ucfirst(Auth::user()->role) }}</h5>
      
      <div class="dropdown">
        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <i class="fas fa-user-circle me-1"></i>
          {{ Auth::user()->name }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil</a></li>
          <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
        </ul>
      </div>
    </div>

    <!-- Content -->
    <div class="content-wrapper">
      {{ $slot }}
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
  </script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sidebar = document.getElementById('sidebar');
      const sidebarToggle = document.getElementById('sidebarToggle');
      const sidebarOverlay = document.getElementById('sidebarOverlay');
      const mainContent = document.getElementById('mainContent');

      function toggleSidebar() {
        sidebar.classList.toggle('show');
        sidebarOverlay.classList.toggle('show');
        
        if (window.innerWidth >= 992) {
          mainContent.classList.toggle('sidebar-open');
        }
      }

      function closeSidebar() {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
      }

      if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
      }

      sidebarOverlay.addEventListener('click', closeSidebar);

      // Handle window resize
      window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) {
          sidebar.classList.remove('show');
          sidebarOverlay.classList.remove('show');
          mainContent.classList.add('sidebar-open');
        } else {
          mainContent.classList.remove('sidebar-open');
        }
      });

      // Initialize sidebar state on desktop
      if (window.innerWidth >= 992) {
        mainContent.classList.add('sidebar-open');
      }

      // Smooth scroll untuk internal links
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          e.preventDefault();
          const target = document.querySelector(this.getAttribute('href'));
          if (target) {
            target.scrollIntoView({
              behavior: 'smooth'
            });
          }
        });
      });
    });
  </script>
</body>

</html>