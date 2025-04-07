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
                <li class="nav-small-cap"><span class="hide-menu">Master</span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('customers.index') }}" aria-expanded="false">
                        <i data-feather="users" class="feather-icon"></i>
                        <span class="hide-menu">Pelanggan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.data.kategori.index') }}" aria-expanded="false">
                        <i data-feather="tag" class="feather-icon"></i>
                        <span class="hide-menu">Kategori Produk</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.data.produk.index') }}" aria-expanded="false">
                        <i data-feather="package" class="feather-icon"></i>
                        <span class="hide-menu">Produk</span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Stok</span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.data.stok.index') }}" aria-expanded="false">
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
                <li class="nav-small-cap"><span class="hide-menu">Authentication</span></li>

                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="authentication-login1.html"
                        aria-expanded="false"><i data-feather="lock" class="feather-icon"></i><span
                            class="hide-menu">Login
                        </span></a>
                </li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="authentication-register1.html"
                        aria-expanded="false"><i data-feather="lock" class="feather-icon"></i><span
                            class="hide-menu">Register
                        </span></a>
                </li>

                <li class="sidebar-item"> <a class="sidebar-link has-arrow" href="javascript:void(0)"
                        aria-expanded="false"><i data-feather="feather" class="feather-icon"></i><span
                            class="hide-menu">Icons
                        </span></a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item"><a href="icon-fontawesome.html" class="sidebar-link"><span
                                    class="hide-menu"> Fontawesome Icons </span></a></li>

                        <li class="sidebar-item"><a href="icon-simple-lineicon.html" class="sidebar-link"><span
                                    class="hide-menu"> Simple Line Icons </span></a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item"> <a class="sidebar-link has-arrow" href="javascript:void(0)"
                        aria-expanded="false"><i data-feather="crosshair" class="feather-icon"></i><span
                            class="hide-menu">Multi
                            level
                            dd</span></a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item"><a href="javascript:void(0)" class="sidebar-link"><span
                                    class="hide-menu"> item 1.1</span></a>
                        </li>
                        <li class="sidebar-item"><a href="javascript:void(0)" class="sidebar-link"><span
                                    class="hide-menu"> item 1.2</span></a>
                        </li>
                        <li class="sidebar-item"> <a class="has-arrow sidebar-link" href="javascript:void(0)"
                                aria-expanded="false"><span class="hide-menu">Menu 1.3</span></a>
                            <ul aria-expanded="false" class="collapse second-level base-level-line">
                                <li class="sidebar-item"><a href="javascript:void(0)" class="sidebar-link"><span
                                            class="hide-menu"> item
                                            1.3.1</span></a></li>
                                <li class="sidebar-item"><a href="javascript:void(0)" class="sidebar-link"><span
                                            class="hide-menu"> item
                                            1.3.2</span></a></li>
                                <li class="sidebar-item"><a href="javascript:void(0)" class="sidebar-link"><span
                                            class="hide-menu"> item
                                            1.3.3</span></a></li>
                                <li class="sidebar-item"><a href="javascript:void(0)" class="sidebar-link"><span
                                            class="hide-menu"> item
                                            1.3.4</span></a></li>
                            </ul>
                        </li>
                        <li class="sidebar-item"><a href="javascript:void(0)" class="sidebar-link"><span
                                    class="hide-menu"> item
                                    1.4</span></a></li>
                    </ul>
                </li>
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Extra</span></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="docs/docs.html"
                        aria-expanded="false"><i data-feather="edit-3" class="feather-icon"></i><span
                            class="hide-menu">Documentation</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{ route('logout') }}"
                        aria-expanded="false"><i data-feather="log-out" class="feather-icon"></i><span
                            class="hide-menu">Logout</span></a></li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
