    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row bg-secondary py-1 px-xl-5">
            <div class="col-lg-6 d-none d-lg-block">
                <small class="text-lg-left">Aplikasi E-Ndog untuk berbelanja berbagai macam telur</small>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    @if (auth()->user())
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-light dropdown-toggle"
                                data-toggle="dropdown">{{ auth()->user()->name }}</button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="" class="dropdown-item" type="button">Profile</a>
                                <a href="{{ route('logout') }}" class="dropdown-item" type="button">Log out</a>
                            </div>
                        </div>
                    @else
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-light dropdown-toggle"
                                data-toggle="dropdown">Login/Registrasi</button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ route('login') }}" class="dropdown-item" type="button">Sign in</a>
                                <a href="{{ route('register') }}" class="dropdown-item" type="button">Sign up</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="d-inline-flex align-items-center d-block d-lg-none">
                    <a href="" class="btn px-0 ml-2">
                        <i class="fas fa-heart text-dark"></i>
                        <span class="badge text-dark border border-dark rounded-circle"
                            style="padding-bottom: 2px;">0</span>
                    </a>
                    <a href="" class="btn px-0 ml-2">
                        <i class="fas fa-shopping-cart text-dark"></i>
                        <span class="badge text-dark border border-dark rounded-circle"
                            style="padding-bottom: 2px;">0</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">
            <div class="col-lg-4">
                <a href="" class="text-decoration-none">
                    <img src="{{ asset('assets/images/logo-icon.png') }}" alt="Logo Aplikasi"
                        style="max-height: 100px;">
                </a>
            </div>
            <div class="col-lg-4
                        col-6 text-left">
                <form action="">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Pencarian ...">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 col-6 text-right">
                <p class="m-0">Kontak Kami</p>
                <h5 class="m-0">0894-7584-38391</h5>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid bg-dark mb-30">
        <div class="row px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn d-flex align-items-center justify-content-between bg-primary w-100" data-toggle="collapse"
                    href="#navbar-vertical" style="height: 65px; padding: 0 30px;">
                    <h6 class="text-dark m-0"><i class="fa fa-bars mr-2"></i>Produk Diskon</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light"
                    id="navbar-vertical" style="width: calc(100% - 30px); z-index: 999;">
                    <div class="navbar-nav w-100">
                        @php
                            $produk_diskon = App\Models\Produk::whereNotNull('harga_diskon')->get();
                        @endphp
                        @foreach ($produk_diskon as $item)
                            <a href="{{ route('produk.detail', ['id' => $item['id']]) }}" class="nav-item nav-link">
                                {{ $item['nama'] }} -
                                <span class="text-muted" style="text-decoration: line-through;">
                                    Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                </span>
                                <span class="text-danger ml-2">
                                    Rp {{ number_format($item['harga_diskon'], 0, ',', '.') }}
                                </span>
                            </a>
                        @endforeach

                    </div>
                </nav>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-0">
                    <a href="" class="text-decoration-none d-block d-lg-none">
                        <img src="{{ asset('assets/images/logo-icon.png') }}" alt="Logo Aplikasi"
                            style="max-height: 50px;">
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="{{ route('dashboard.pengunjung') }}"
                                class="nav-item nav-link {{ request()->routeIs('dashboard.pengunjung') ? 'active' : '' }}">Home</a>

                            <a href="{{ route('produk.list') }}"
                                class="nav-item nav-link {{ request()->routeIs('produk.list') ? 'active' : '' }}">Produk</a>
                            <a href="" class="nav-item nav-link">Tentang Kami</a>
                        </div>
                        <div class="navbar-nav ml-auto py-0 d-none d-lg-block">
                            <a href="#" class="btn px-0 ml-3 text-light" onclick="cekLogin()">
                                <i class="fas fa-shopping-cart text-primary"></i> Belanja
                            </a>
                        </div>
                        <script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-aio-3.2.6.min.js"></script>
                        <script>
                            function cekLogin() {
                                @if (Auth::check())
                                    window.location.href = "{{ route('belanja.list') }}";
                                @else
                                    Notiflix.Notify.failure('Silakan login terlebih dahulu untuk belanja');
                                @endif
                            }
                        </script>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->
