<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('index') }}" class="sidebar-logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('assets/images/logo-light.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li class="dropdown">
                <a  href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Utility</span>
                </a>
                <ul class="sidebar-submenu">
                    {{-- FINANCE --}}
                    @if(Auth::user()->role === 'finance')
                        <li>
                            <a href="{{ route('finance.index') }}">
                                <iconify-icon icon="solar:widget-4-outline" class="menu-icon"></iconify-icon> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('finance.index2') }}">
                                <iconify-icon icon="solar:card-outline" class="menu-icon"></iconify-icon> Transaksi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('finance.index3') }}">
                                <iconify-icon icon="mdi:airplane" class="menu-icon"></iconify-icon> Traveler
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('finance.index4') }}">
                                <iconify-icon icon="solar:box-outline" class="menu-icon"></iconify-icon> Penitip
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('finance.index5') }}">
                                <iconify-icon icon="solar:refresh-outline" class="menu-icon"></iconify-icon> Refund
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('finance.index6') }}">
                                <iconify-icon icon="solar:settings-outline" class="menu-icon"></iconify-icon> Pengaturan
                            </a>
                        </li>
                    @endif

                    {{-- ADMIN --}}
                    @if(Auth::user()->role === 'admin')
                        <li>
                            <a href="{{ route('admin.index') }}">
                                <iconify-icon icon="solar:widget-4-outline" class="menu-icon"></iconify-icon> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.index2') }}">
                                <iconify-icon icon="solar:users-group-rounded-outline" class="menu-icon"></iconify-icon> Manajemen Pelanggan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.index3') }}">
                                <iconify-icon icon="solar:card-outline" class="menu-icon"></iconify-icon> Transaksi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.index4') }}">
                                <iconify-icon icon="solar:bag-4-outline" class="menu-icon"></iconify-icon> Manajemen Produk
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.index5') }}">
                                <iconify-icon icon="solar:settings-outline" class="menu-icon"></iconify-icon> Pengaturan
                            </a>
                        </li>
                    @endif

                    {{-- SUPERADMIN --}}
                    @if(Auth::user()->role === 'superadmin')
                        <li>
                            <a href="{{ route('superadmin.index') }}">
                                <iconify-icon icon="solar:widget-4-outline" class="menu-icon"></iconify-icon> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('superadmin.index2') }}">
                                <iconify-icon icon="solar:user-id-outline" class="menu-icon"></iconify-icon> Manajemen Pengguna
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('superadmin.index3') }}">
                                <iconify-icon icon="solar:bag-4-outline" class="menu-icon"></iconify-icon> Manajemen Produk
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('superadmin.index4') }}">
                                <iconify-icon icon="solar:card-outline" class="menu-icon"></iconify-icon> Transaksi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('superadmin.index5') }}">
                                <iconify-icon icon="solar:refresh-outline" class="menu-icon"></iconify-icon> Refund
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('superadmin.index6') }}">
                                <iconify-icon icon="solar:settings-outline" class="menu-icon"></iconify-icon> Pengaturan
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        </ul>
    </div>
</aside>