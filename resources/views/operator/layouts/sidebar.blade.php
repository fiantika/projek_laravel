<!-- New Sidebar for Operator -->
<aside class="new-sidebar">
    <div class="sidebar-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Tokoku</h4>
        <span class="role-badge">Operator</span>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="/operator/dashboard" class="{{ Request::is('operator/dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a href="/operator/produk" class="{{ Request::is('operator/produk*') ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                Produk
            </a>
        </li>
        <li>
            <a href="/operator/kategori" class="{{ Request::is('operator/kategori*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i>
                Kategori
            </a>
        </li>
        <li>
            <a href="/operator/user" class="{{ Request::is('operator/user*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                User
            </a>
        </li>
        <li>
            <a href="/operator/stok" class="{{ Request::is('operator/stok*') ? 'active' : '' }}">
                <i class="fas fa-box-open"></i>
                Stok
            </a>
        </li>
        <hr style="border-color: rgba(255,255,255,0.2); margin: 20px 0;">
        <li>
            <a href="{{ route('logout') }}" class="text-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>
    </ul>
</aside>

<style>
/* New sidebar styling inspired by reference */
.new-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 260px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    z-index: 1000;
    overflow-y: auto;
}
.new-sidebar .sidebar-header {
    padding: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}
.new-sidebar .sidebar-menu {
    padding: 0;
    margin: 0;
    list-style: none;
}
.new-sidebar .sidebar-menu li {
    margin: 0;
}
.new-sidebar .sidebar-menu a,
.new-sidebar .sidebar-menu button {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 12px 20px;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
    background: none;
}
.new-sidebar .sidebar-menu a i,
.new-sidebar .sidebar-menu button i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}
.new-sidebar .sidebar-menu a:hover,
.new-sidebar .sidebar-menu button:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border-left-color: #fff;
}
.new-sidebar .sidebar-menu a.active {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border-left-color: #ffd700;
}
.role-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* At viewport widths >= 992px (lg), shift the main wrapper to make room for the fixed sidebar */
@media (min-width: 992px) {
    .wrapper {
        margin-left: 260px;
    }
}

@media (max-width: 991.98px) {
    .wrapper {
        margin-left: 0;
    }
}
</style>