@extends('layouts_pengunjung.main')
@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid mb-3">
        @if (isset($carousel))
            <div class="row px-xl-5">
                <div class="col-lg-8">
                    <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-ride="carousel">
                        <ol class="carousel-indicators">
                            @foreach ($carousel as $index => $item)
                                <li data-target="#header-carousel" data-slide-to="{{ $index }}"
                                    class="{{ $index == 0 ? 'active' : '' }}"></li>
                            @endforeach
                        </ol>

                        <div class="carousel-inner">
                            @foreach ($carousel as $index => $item)
                                <div class="carousel-item position-relative {{ $index == 0 ? 'active' : '' }}"
                                    style="height: 430px;">
                                    <img class="position-absolute w-100 h-100"
                                        src="{{ asset('storage/carousel/' . $item->foto) }}" style="object-fit: cover;">
                                    <div
                                        class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                        <div class="p-3" style="max-width: 700px;">
                                            <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">
                                                {{ $item->judul }}</h1>
                                            <p class="mx-md-5 px-5 animate__animated animate__bounceIn">
                                                {{ $item->deskripsi }}
                                            </p>
                                            <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp"
                                                href="{{ route('produk.list') }}">Belanja Sekarang</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
        @endif
        @if (isset($promosi))
            <div class="col-lg-4">
                @if (isset($promosi[0]))
                    <div class="product-offer mb-30" style="height: 200px;">
                        <img class="img-fluid" src="{{ asset('storage/promosi/' . $promosi[0]->foto) }}" alt="">
                        <div class="offer-text">
                            <h6 class="text-white text-uppercase">{{ $promosi[0]->judul }}</h6>
                            <h3 class="text-white mb-3">{{ $promosi[0]->sub_judul }}</h3>
                            <a href="{{ route('produk.list') }}" class="btn btn-primary">Shop Now</a>
                        </div>
                    </div>
                @endif
                @if (isset($promosi[1]))
                    <div class="product-offer mb-30" style="height: 200px;">
                        <img class="img-fluid" src="{{ asset('storage/promosi/' . $promosi[1]->foto) }}" alt="">
                        <div class="offer-text">
                            <h6 class="text-white text-uppercase">{{ $promosi[1]->judul }}</h6>
                            <h3 class="text-white mb-3">{{ $promosi[1]->sub_judul }}</h3>
                            <a href="{{ route('produk.list') }}" class="btn btn-primary">Shop Now</a>
                        </div>
                    </div>
                @endif
            </div>
        @endif
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

    @if (isset($produk))
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
                                <div class="d-flex flex-column align-items-center justify-content-center mt-2">
                                    <div>
                                        <span class="font-weight-bold">Harga Ecer:</span>
                                        <span>Rp. {{ number_format($item['harga']) }}</span>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold">Harga Grosir:</span>
                                        <span>Rp. {{ number_format($item['harga_grosir']) }}</span>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold">Harga Pengampu:</span>
                                        <span>Rp. {{ number_format($item['harga_pengampu']) }}</span>
                                    </div>
                                    <small class="text-muted text-center mt-2" style="line-height: 1.5;">
                                        <strong>Keterangan:</strong><br>
                                        - Harga <strong>ecer</strong> untuk pembelian di bawah <strong>10 kg</strong><br>
                                        - Harga <strong>grosir</strong> untuk pembelian antara <strong>10–30 kg</strong><br>
                                        - Harga <strong>pengampu</strong> untuk pembelian lebih dari <strong>30 kg</strong>
                                    </small>
                                </div>
                                <div
                                    class="mt-2 {{ $item['stok'] != 0 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                    {{ $item['stok'] != 0 ? 'Stok tersedia (' . $item['stok'] . ')' : 'Stok habis' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if (isset($promosi))
        <div class="container-fluid pt-5 pb-3">
            <div class="row px-xl-5">
                @if (isset($promosi[2]))
                    <div class="col-md-6">
                        <div class="product-offer mb-30" style="height: 300px;">
                            <img class="img-fluid" src="{{ asset('storage/promosi/' . $promosi[2]->foto) }}"
                                alt="">
                            <div class="offer-text">
                                <h6 class="text-white text-uppercase">{{ $promosi[2]->judul }}</h6>
                                <h3 class="text-white mb-3">{{ $promosi[2]->sub_judul }}</h3>
                                <a href="{{ route('produk.list') }}" class="btn btn-primary">Shop Now</a>
                            </div>
                        </div>
                    </div>
                @endif
                @if (isset($promosi[3]))
                    <div class="col-md-6">
                        <div class="product-offer mb-30" style="height: 300px;">
                            <img class="img-fluid" src="{{ asset('storage/promosi/' . $promosi[3]->foto) }}"
                                alt="">
                            <div class="offer-text">
                                <h6 class="text-white text-uppercase">{{ $promosi[3]->judul }}</h6>
                                <h3 class="text-white mb-3">{{ $promosi[3]->sub_judul }}</h3>
                                <a href="{{ route('produk.list') }}" class="btn btn-primary">Shop Now</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

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
@endsection
