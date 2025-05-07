<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                        <i data-feather="home" class="feather-icon"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Stok</span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.stok.index') }}" aria-expanded="false">
                        <i data-feather="layers" class="feather-icon"></i>
                        <span class="hide-menu">Manajemen Stok</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.stok-masuk.index') }}" aria-expanded="false">
                        <i data-feather="arrow-down-circle" class="feather-icon"></i>
                        <span class="hide-menu">Stok Masuk</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.stok-keluar.index') }}" aria-expanded="false">
                        <i data-feather="arrow-up-circle" class="feather-icon"></i>
                        <span class="hide-menu">Stok Keluar</span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Pesanan</span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.pesanan.index') }}" aria-expanded="false">
                        <i data-feather="shopping-cart" class="feather-icon"></i>
                        <span class="hide-menu">Kelola Pesanan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="" aria-expanded="false">
                        <i data-feather="clock" class="feather-icon"></i>
                        <span class="hide-menu">History Pesanan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="" aria-expanded="false">
                        <i data-feather="file-text" class="feather-icon"></i>
                        <span class="hide-menu">Laporan</span>
                    </a>
                </li>
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Master</span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('customers.index') }}" aria-expanded="false">
                        <i data-feather="users" class="feather-icon"></i>
                        <span class="hide-menu">Pelanggan</span>
                    </a>
                </li>
                {{-- <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.kategori.index') }}" aria-expanded="false">
                        <i data-feather="tag" class="feather-icon"></i>
                        <span class="hide-menu">Kategori Produk</span>
                    </a>
                </li> --}}
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.kurir.index') }}" aria-expanded="false">
                        <i data-feather="user" class="feather-icon"></i>
                        <span class="hide-menu">Kurir</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.produk.index') }}" aria-expanded="false">
                        <i data-feather="package" class="feather-icon"></i>
                        <span class="hide-menu">Produk</span>
                    </a>
                </li>
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Kelola Tampilan</span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('carousel.index') }}" aria-expanded="false">
                        <i data-feather="image" class="feather-icon"></i>
                        <span class="hide-menu">Carousel</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('promosi.index') }}" aria-expanded="false">
                        <i data-feather="camera" class="feather-icon"></i>
                        <span class="hide-menu">Banner Promosi</span>
                    </a>
                </li>
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Extra</span></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link"
                        href="{{ route('admin.profile-toko.index') }}" aria-expanded="false"><i
                            data-feather="edit-3" class="feather-icon"></i><span class="hide-menu">Kelola
                            Toko</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{ route('logout') }}"
                        aria-expanded="false"><i data-feather="log-out" class="feather-icon"></i><span
                            class="hide-menu">Logout</span></a></li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
