<aside class="sidebar bg-light border-end vh-100 d-flex flex-column p-2">
    <!-- Branding / Profile -->
    <div class="sidebar-brand d-flex align-items-center gap-2 px-2 py-3 mb-3 border-bottom">
        <img src="{{ asset('assets/images/illustrator-avatar.png') }}" 
             alt="Super Admin" 
             width="36" 
             height="36" 
             class="rounded-circle border">
        <span class="fw-semibold link-text">Super Admin</span>
    </div>

    <!-- Menu -->
    <ul class="list-unstyled m-0 p-0 flex-grow-1">
        <li class="mb-1">
            <a href="{{ route(Auth::user()->role . '.dashboard') }}" 
               class="d-flex align-items-center gap-2 px-3 py-2 rounded sidebar-link {{ request()->routeIs(Auth::user()->role.'.dashboard') ? 'active' : '' }}">
                <span class="icon-circle bg-primary text-white"><i class="ri-dashboard-line"></i></span>
                <span class="link-text">Dashboard</span>
            </a>
        </li>

        <li class="mb-1">
            <a href="{{ route(Auth::user()->role . '.users.index') }}" 
               class="d-flex align-items-center gap-2 px-3 py-2 rounded sidebar-link {{ request()->routeIs(Auth::user()->role.'.users.*') ? 'active' : '' }}">
                <span class="icon-circle bg-warning text-white"><i class="ri-user-3-line"></i></span>
                <span class="link-text">Manajemen Pengguna</span>
            </a>
        </li>

        <li class="mb-1">
            <a href="{{ route(Auth::user()->role . '.products.index') }}" 
               class="d-flex align-items-center gap-2 px-3 py-2 rounded sidebar-link {{ request()->routeIs(Auth::user()->role.'.products*') ? 'active' : '' }}">
                <span class="icon-circle bg-info text-white"><i class="ri-shopping-bag-line"></i></span>
                <span class="link-text">Manajemen Produk</span>
            </a>
        </li>

        <li class="mb-1">
            <a href="{{ route(Auth::user()->role . '.transactions.index') }}" 
               class="d-flex align-items-center gap-2 px-3 py-2 rounded sidebar-link {{ request()->routeIs(Auth::user()->role.'.transactions*') ? 'active' : '' }}">
                <span class="icon-circle bg-danger text-white"><i class="ri-exchange-dollar-line"></i></span>
                <span class="link-text">Transaksi</span>
            </a>
        </li>

        <li class="mb-1">
            <a href="{{ route('superadmin.refunds.index') }}" 
               class="d-flex align-items-center gap-2 px-3 py-2 rounded sidebar-link {{ request()->routeIs('superadmin.refunds*') ? 'active' : '' }}">
                <span class="icon-circle bg-danger text-white"><i class="ri-refund-line"></i></span>
                <span class="link-text">Refund</span>
            </a>
        </li>

        <li class="mb-1">
            <a href="{{ route('profile.index') }}" 
               class="d-flex align-items-center gap-2 px-3 py-2 rounded sidebar-link {{ request()->routeIs(Auth::user()->role.'.settings*') ? 'active' : '' }}">
                <span class="icon-circle bg-purple text-white"><i class="ri-settings-3-line"></i></span>
                <span class="link-text">Pengaturan</span>
            </a>
        </li>
    </ul>
</aside>

<style>
.sidebar {
    width: 220px;
    transition: width 0.3s ease, left 0.3s ease;
}
body.sidebar-collapsed .sidebar {
    width: 60px;
}

/* mobile: overlay */
@media (max-width: 991px) {
    .sidebar {
        position: fixed;
        top: 0;
        left: -220px;
        height: 100%;
        z-index: 1050;
        background: #fff;
    }
    body.sidebar-collapsed .sidebar {
        left: 0;
    }
}

/* teks link */
.sidebar .link-text {
    transition: opacity 0.3s ease, margin 0.3s ease;
}
body.sidebar-collapsed .sidebar .link-text {
    opacity: 0;
    margin-left: -10px;
    pointer-events: none;
}

/* Bulatan Icon */
.icon-circle {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    font-size: 18px;
    flex-shrink: 0;
    line-height: 1;
}
.icon-circle i,
.icon-circle iconify-icon {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
    line-height: 1 !important;
    font-size: inherit;
}

/* Link sidebar */
.sidebar-link {
    color: #495057;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
}
.sidebar-link:hover {
    background-color: rgba(0, 123, 255, 0.1);
    color: #0d6efd;
    padding-left: 1.5rem;
}
.sidebar-link.active {
    background-color: #0d6efd;
    color: #fff;
    font-weight: 600;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const body = document.body;
    const navToggle = document.querySelector(".sidebar-toggle");
    const sidebarToggle = document.getElementById("sidebarToggle");

    if (navToggle) {
        navToggle.addEventListener("click", () => {
            body.classList.toggle("sidebar-collapsed");
        });
    }
    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", () => {
            body.classList.toggle("sidebar-collapsed");
        });
    }
});
</script>
