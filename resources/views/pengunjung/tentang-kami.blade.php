@extends('layouts_pengunjung.main')
@section('title', 'Tentang Kami')

@section('content')
    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ route('dashboard.pengunjung') }}">Home</a>
                    <span class="breadcrumb-item active">Tentang Kami</span>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-5">
        <div class="row px-xl-5">
            <div class="col-12">
                <div class="bg-light p-30 mb-5">
                    <div class="row align-items-center">
                        @if (isset($profile->logo))
                            <div class="col-md-3 text-center">
                                <img src="{{ asset('storage/' . $profile->logo) }}" alt="{{ $profile->nama_toko }}"
                                    class="img-fluid mb-4" style="max-height: 180px;">
                            </div>
                            <div class="col-md-9">
                            @else
                                <div class="col-12">
                        @endif
                        <h1 class="mb-4 section-title position-relative text-uppercase font-weight-bold">
                            {{ $profile->nama_toko ?? 'E-Ndog' }}</h1>

                        @if (isset($profile->deskripsi))
                            <div class="mb-4 store-description">
                                {{ $profile->deskripsi }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="container-fluid pb-5">
        <div class="row px-xl-5">
            <div class="col-lg-6">
                <h5 class="section-title position-relative text-uppercase mb-4"><span class="bg-secondary pr-3">Informasi
                        Kontak</span></h5>
                <div class="bg-light p-30 mb-30">
                    <div class="contact-info">
                        @if (isset($profile->alamat))
                            <div class="d-flex mb-4">
                                <i class="fa fa-map-marker-alt text-primary mr-3 mt-1" style="width: 20px;"></i>
                                <div>
                                    <h6 class="font-weight-semi-bold mb-1">Alamat Toko</h6>
                                    <p class="text-muted">{{ $profile->alamat }}</p>
                                </div>
                            </div>
                        @endif

                        @if (isset($profile->telepon))
                            <div class="d-flex mb-4">
                                <i class="fa fa-phone-alt text-primary mr-3 mt-1" style="width: 20px;"></i>
                                <div>
                                    <h6 class="font-weight-semi-bold mb-1">Telepon</h6>
                                    <p class="text-muted">{{ $profile->telepon }}</p>
                                </div>
                            </div>
                        @endif

                        @if (isset($profile->email))
                            <div class="d-flex mb-4">
                                <i class="fa fa-envelope text-primary mr-3 mt-1" style="width: 20px;"></i>
                                <div>
                                    <h6 class="font-weight-semi-bold mb-1">Email</h6>
                                    <p class="text-muted">{{ $profile->email }}</p>
                                </div>
                            </div>
                        @endif

                        @if (isset($profile->jam_operasional))
                            <div class="d-flex mb-4">
                                <i class="fa fa-clock text-primary mr-3 mt-1" style="width: 20px;"></i>
                                <div>
                                    <h6 class="font-weight-semi-bold mb-1">Jam Operasional</h6>
                                    <p class="text-muted">{{ $profile->jam_operasional }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h5 class="section-title position-relative text-uppercase mb-4"><span class="bg-secondary pr-3">Sosial
                        Media</span></h5>
                <div class="bg-light p-30 mb-3">
                    <div class="row social-media">
                        @if (isset($profile->facebook))
                            <div class="col-md-6 mb-4">
                                <a href="{{ $profile->facebook }}" target="_blank"
                                    class="btn btn-block btn-outline-primary py-2 px-4">
                                    <i class="fab fa-facebook-f mr-2"></i>Facebook
                                </a>
                            </div>
                        @endif

                        @if (isset($profile->instagram))
                            <div class="col-md-6 mb-4">
                                <a href="{{ $profile->instagram }}" target="_blank"
                                    class="btn btn-block btn-outline-danger py-2 px-4">
                                    <i class="fab fa-instagram mr-2"></i>Instagram
                                </a>
                            </div>
                        @endif

                        @if (isset($profile->twitter))
                            <div class="col-md-6 mb-4">
                                <a href="{{ $profile->twitter }}" target="_blank"
                                    class="btn btn-block btn-outline-info py-2 px-4">
                                    <i class="fab fa-twitter mr-2"></i>Twitter
                                </a>
                            </div>
                        @endif

                        @if (isset($profile->whatsapp))
                            <div class="col-md-6 mb-4">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profile->whatsapp) }}"
                                    target="_blank" class="btn btn-block btn-outline-success py-2 px-4">
                                    <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                                </a>
                            </div>
                        @endif
                    </div>

                    @if (isset($profile->alamat))
                        <div class="map-responsive mt-3">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item"
                                    src="https://maps.google.com/maps?q={{ urlencode($profile->alamat) }}&t=&z=15&ie=UTF8&iwloc=&output=embed"
                                    frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
                                </iframe>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col-12">
                <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span
                        class="bg-secondary pr-3">Komitmen Kami</span></h2>
                <div class="row px-xl-5">
                    <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                        <div class="d-flex flex-column align-items-center justify-content-center bg-light mb-4 px-3"
                            style="height: 250px;">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="fa fa-check fa-2x"></i>
                            </div>
                            <h5 class="font-weight-semi-bold text-center">Kualitas Produk Terjamin</h5>
                            <p class="text-center">Kami selalu memastikan produk yang kami jual memiliki kualitas terbaik
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                        <div class="d-flex flex-column align-items-center justify-content-center bg-light mb-4 px-3"
                            style="height: 250px;">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="fa fa-shipping-fast fa-2x"></i>
                            </div>
                            <h5 class="font-weight-semi-bold text-center">Pengiriman Cepat</h5>
                            <p class="text-center">Kami berkomitmen untuk mengirim pesanan Anda secepat mungkin</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                        <div class="d-flex flex-column align-items-center justify-content-center bg-light mb-4 px-3"
                            style="height: 250px;">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="fa fa-exchange-alt fa-2x"></i>
                            </div>
                            <h5 class="font-weight-semi-bold text-center">Kebijakan Pengembalian</h5>
                            <p class="text-center">Kepuasan Anda adalah prioritas kami dengan kebijakan pengembalian yang
                                mudah</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                        <div class="d-flex flex-column align-items-center justify-content-center bg-light mb-4 px-3"
                            style="height: 250px;">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="fa fa-phone-volume fa-2x"></i>
                            </div>
                            <h5 class="font-weight-semi-bold text-center">Dukungan 24/7</h5>
                            <p class="text-center">Tim dukungan kami siap membantu Anda kapan saja</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid py-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Metode
                pembayaran yang ada di E-Ndog</span></h2>
        <div class="row px-xl-5">
            <div class="col">
                <style>
                    .owl-carousel img {
                        width: 150px;
                        height: 150px;
                        object-fit: contain;
                        display: block;
                        margin: 0 auto;
                    }
                </style>
                <div class="owl-carousel vendor-carousel">
                    <div class="bg-light p-4">
                        <img src="{{ asset('assets') }}/images/bni.webp" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="{{ asset('assets') }}/images/mandiri.png" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="{{ asset('assets') }}/images/bca.png" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="{{ asset('assets') }}/images/qris.png" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="{{ asset('assets') }}/images/indomaret.png" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="{{ asset('assets') }}/images/alfamart.png" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="{{ asset('assets') }}/images/visa.png" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="{{ asset('assets') }}/images/mastercard.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .store-description {
            font-size: 16px;
            line-height: 1.8;
            text-align: justify;
        }

        .map-responsive {
            overflow: hidden;
            position: relative;
            height: 0;
            padding-bottom: 56.25%;
        }

        .map-responsive iframe {
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            position: absolute;
        }

        .social-media .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .social-media .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
