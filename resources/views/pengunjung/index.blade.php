@extends('layouts_pengunjung.main')
@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid mb-3">
        <div class="row px-xl-5">
            <div class="col-lg-8">
                <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#header-carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#header-carousel" data-slide-to="1"></li>
                        <li data-target="#header-carousel" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item position-relative active" style="height: 430px;">
                            <img class="position-absolute w-100 h-100" src="{{ asset('pengunjung') }}/img/carousel-1.jpg"
                                style="object-fit: cover;">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Men
                                        Fashion</h1>
                                    <p class="mx-md-5 px-5 animate__animated animate__bounceIn">Lorem rebum magna amet
                                        lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                                    <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp"
                                        href="#">Shop Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item position-relative" style="height: 430px;">
                            <img class="position-absolute w-100 h-100" src="{{ asset('pengunjung') }}/img/carousel-2.jpg"
                                style="object-fit: cover;">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Women
                                        Fashion</h1>
                                    <p class="mx-md-5 px-5 animate__animated animate__bounceIn">Lorem rebum magna amet
                                        lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                                    <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp"
                                        href="#">Shop Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item position-relative" style="height: 430px;">
                            <img class="position-absolute w-100 h-100" src="{{ asset('pengunjung') }}/img/carousel-3.jpg"
                                style="object-fit: cover;">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Kids
                                        Fashion</h1>
                                    <p class="mx-md-5 px-5 animate__animated animate__bounceIn">Lorem rebum magna amet
                                        lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                                    <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp"
                                        href="#">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="product-offer mb-30" style="height: 200px;">
                    <img class="img-fluid" src="{{ asset('pengunjung') }}/img/offer-1.jpg" alt="">
                    <div class="offer-text">
                        <h6 class="text-white text-uppercase">Save 20%</h6>
                        <h3 class="text-white mb-3">Special Offer</h3>
                        <a href="" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
                <div class="product-offer mb-30" style="height: 200px;">
                    <img class="img-fluid" src="{{ asset('pengunjung') }}/img/offer-2.jpg" alt="">
                    <div class="offer-text">
                        <h6 class="text-white text-uppercase">Save 20%</h6>
                        <h3 class="text-white mb-3">Special Offer</h3>
                        <a href="" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Kualitas Produk</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                    <h5 class="font-weight-semi-bold m-0">Pengiriman cepat</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fas fa-credit-card text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Metode Pembayaran</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Dukungan 24/7</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Produk
                Diskon</span></h2>
        <div class="row px-xl-5 pb-3">
            @foreach ($produk_diskon as $item)
                <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                    <a class="text-decoration-none" href="">
                        <div class="cat-item d-flex align-items-center mb-4">
                            <div class="overflow-hidden" style="width: 100px; height: 100px;">
                                <img class="img-fluid" src="{{ asset('storage/foto-produk/' . $item['foto']) }}"
                                    alt="">
                            </div>
                            <div class="flex-fill pl-3 d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h6 class="mb-1 text-truncate">{{ $item['nama'] }}</h6>
                                    <h6 class="font-weight-semi-bold mb-2">
                                        <del class="text-muted mr-2">Rp {{ number_format($item['harga']) }}</del>
                                        <span class="text-danger">Rp {{ number_format($item['harga_diskon']) }}</span>
                                    </h6>
                                </div>
                                <small class="text-body">Stok : {{ $item['stok'] }}</small>
                            </div>

                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Beberapa
                produk dari E-Ndog</span></h2>
        <div class="row px-xl-5">
            @foreach ($produk as $item)
                <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                    <div class="product-item bg-light mb-4 shadow-sm">
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid w-100" src="{{ asset('storage/foto-produk/' . $item['foto']) }}"
                                alt="{{ $item['nama'] }}" style="height: 200px; width: 150px; object-fit: contain;">
                            <div class="product-action">
                                <a class="btn btn-outline-dark btn-square"
                                    href="{{ route('produk.detail', ['id' => $item['id']]) }}"><i
                                        class="fa fa-search"></i></a>
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate" href="">{{ $item['nama'] }}</a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                @if (isset($item['harga_diskon']) && $item['harga_diskon'] > 0)
                                    <h6 class="font-weight-semi-bold mb-4">
                                        <del class="text-muted mr-2">Rp. {{ number_format($item['harga']) }}</del>
                                        Rp. {{ number_format($item['harga_diskon']) }}
                                    </h6>
                                @else
                                    <h6 class="font-weight-semi-bold mb-4">Rp. {{ number_format($item['harga']) }}</h6>
                                @endif
                            </div>
                            <div class="mt-2 {{ $item['stok'] != 0 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                {{ $item['stok'] != 0 ? 'Stok tersedia (' . $item['stok'] . ')' : 'Stok habis' }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container-fluid pt-5 pb-3">
        <div class="row px-xl-5">
            <div class="col-md-6">
                <div class="product-offer mb-30" style="height: 300px;">
                    <img class="img-fluid" src="{{ asset('pengunjung') }}/img/offer-1.jpg" alt="">
                    <div class="offer-text">
                        <h6 class="text-white text-uppercase">Save 20%</h6>
                        <h3 class="text-white mb-3">Special Offer</h3>
                        <a href="" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="product-offer mb-30" style="height: 300px;">
                    <img class="img-fluid" src="{{ asset('pengunjung') }}/img/offer-2.jpg" alt="">
                    <div class="offer-text">
                        <h6 class="text-white text-uppercase">Save 20%</h6>
                        <h3 class="text-white mb-3">Special Offer</h3>
                        <a href="" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid py-5">
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
@endsection
