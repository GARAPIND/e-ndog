<!-- Footer Start -->
<div class="container-fluid bg-dark text-secondary mt-5 pt-5">
    <div class="row px-xl-5 pt-5">
        <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
            <h5 class="text-secondary text-uppercase mb-4">E-Ndog</h5>
            <p class="mb-4">"E-Ndog – Toko online terpercaya yang menyediakan berbagai macam telur segar dan
                berkualitas untuk kebutuhan harian Anda. Pengiriman cepat dan harga bersahabat."</p>
            <h6 class="text-secondary text-uppercase mt-4 mb-3">Follow Us</h6>
            <div class="d-flex">
                <a class="btn btn-primary btn-square mr-2" href="https://www.facebook.com" target="_blank">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a class="btn btn-primary btn-square mr-2" href="https://www.instagram.com" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
                <a class="btn btn-primary btn-square mr-2" href="mailto:e_ndog@gmail.com" target="_blank">
                    <i class="fab fa-google"></i>
                </a>
                <a class="btn btn-primary btn-square mr-2" href="https://shopee.co.id" target="_blank">
                    <i class="fas fa-shopping-cart"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-8 col-md-12">
            <div class="row">

                <div class="col-md-4 mb-5">
                    <h5 class="text-secondary text-uppercase mb-4">Kontak Kami</h5>
                    <div class="d-flex flex-column justify-content-start">
                        <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>JL Dr. Wahidin Kediri
                        </p>
                        <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>e_ndog@gmail.com</p>
                        <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>0894-7584-38391</p>
                    </div>
                </div>

                <div class="col-md-4 mb-5">
                    <h5 class="text-secondary text-uppercase mb-4">Menu</h5>
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-secondary mb-2" href="{{ route('dashboard.pengunjung') }}"><i
                                class="fa fa-angle-right mr-2"></i>Home</a>
                        <a class="text-secondary mb-2" href="{{ route('produk.list') }}"><i
                                class="fa fa-angle-right mr-2"></i>Produk</a>
                        <a class="text-secondary mb-2" href=""><i class="fa fa-angle-right mr-2"></i>Tentang
                            Kami</a>
                        <a class="text-secondary mb-2" href="{{ route('login') }}"><i
                                class="fa fa-key mr-2"></i>Login</a>
                        <a class="text-secondary mb-2" href="{{ route('register') }}"><i
                                class="fa fa-user-plus mr-2"></i>Register </a>

                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <h5 class="text-secondary text-uppercase mb-4">Maps</h5>
                    <div class="bg-light p-30 mb-30">
                        <iframe style="width: 100%; height: 150px;"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.075856487233!2d110.35716347530081!3d-7.781781977212763!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5997cd89737d%3A0xa911e2d7ea67a808!2se-Ndog%20Shanum%20Telor%20Ayam!5e0!3m2!1sid!2sid!4v1744429094208!5m2!1sid!2sid"
                            frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false"
                            tabindex="0"></iframe>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row border-top mx-xl-5 py-4" style="border-color: rgba(256, 256, 256, .1) !important;">
        <div class="col-md-6 px-xl-0">
            <p class="mb-md-0 text-center text-md-left text-secondary">
                2025 &copy; <a class="text-primary text-center" href="{{ route('login') }}"> E_Ndog</a>
            </p>
        </div>
    </div>
</div>
<!-- Footer End -->
