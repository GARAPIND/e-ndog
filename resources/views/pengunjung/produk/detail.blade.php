@extends('layouts_pengunjung.main')
@section('title', 'Detail Produk')

@section('content')
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ route('dashboard.pengunjung') }}">Home</a>
                    <a class="breadcrumb-item text-dark" href="{{ route('produk.list') }}">Produk</a>
                    <span class="breadcrumb-item active">Produk Detail</span>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 mb-30">
                <style>
                    #product-carousel .carousel-item {
                        width: 100%;
                        height: 600px;
                        background-color: #f5f5f5;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    #product-carousel .carousel-item img {
                        object-fit: contain;
                        width: 100%;
                        height: 100%;
                    }
                </style>

                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner bg-light">
                        <div class="carousel-item active">
                            <img src="{{ asset('storage/foto-produk/' . $data['foto']) }}" alt="Image">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-7 h-auto mb-30">
                <div class="h-100 bg-light p-30" style="max-height: 600px; overflow-y: auto;">
                    <h3>{{ $data['nama'] }}</h3>
                    <div class="mb-3">
                        <span class="badge badge-primary mr-2" style="font-size: 1rem; padding: 0.7em 1.2em;">
                            Ecer: Rp. {{ number_format($data['harga']) }}
                        </span>
                        <span class="badge badge-warning mr-2" style="font-size: 1rem; padding: 0.7em 1.2em;">
                            Grosir: Rp. {{ number_format($data['harga_grosir']) }}
                        </span>
                        <span class="badge badge-success" style="font-size: 1rem; padding: 0.7em 1.2em;">
                            Pengampu: Rp. {{ number_format($data['harga_pengampu']) }}
                        </span>
                    </div>
                    <small class="text-muted d-block mb-3" style="line-height: 1.6;">
                        <strong>Keterangan:</strong><br>
                        - Harga <strong>ecer</strong> untuk pembelian di bawah <strong>10 kg</strong><br>
                        - Harga <strong>grosir</strong> untuk pembelian antara <strong>10–30 kg</strong><br>
                        - Harga <strong>pengampu</strong> untuk pembelian lebih dari <strong>30 kg</strong>
                    </small>

                    @php
                        $deskripsi = $data['deskripsi'];
                        $maxLength = 800;
                        $isLong = strlen($deskripsi) > $maxLength;
                        $shortDesc = $isLong ? substr($deskripsi, 0, $maxLength) . '...' : $deskripsi;
                    @endphp

                    <p class="mb-5" id="deskripsi-short">
                        {{ $shortDesc }}
                        @if ($isLong)
                            <a href="javascript:void(0);" onclick="toggleDeskripsi()">Lihat Selengkapnya</a>
                        @endif
                    </p>

                    <p class="mb-3" id="deskripsi-full" style="display:none;">
                        {{ $deskripsi }}
                        <a href="javascript:void(0);" onclick="toggleDeskripsi()">Tampilkan Lebih Sedikit</a>
                    </p>
                    <div class="d-flex align-items-center pt-2">
                        <div class="card p-3 shadow-sm border-light rounded" style="width: 30%;">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <span><strong>Stok:</strong> <span class="text-dark">{{ $data['stok'] }}</span></span>
                                    <span><strong>Berat:</strong> <span class="text-dark">{{ $data['berat'] }}
                                            kg</span></span>
                                    <span><strong>Satuan:</strong> <span
                                            class="text-dark">{{ $data['satuan'] }}</span></span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-box-open fa-2x text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Produk
                lain yang mungkin anda suka</span></h2>
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel related-carousel">
                    @foreach ($all_produk as $item)
                        <div class="product-item bg-light">
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
                                    <h6 class="font-weight-semi-bold mb-1 text-muted">
                                        Ecer: Rp. {{ number_format($item['harga']) }}
                                    </h6>
                                    <h6 class="font-weight-semi-bold mb-1 text-muted">
                                        Grosir: Rp. {{ number_format($item['harga_grosir']) }}
                                    </h6>
                                    <h6 class="font-weight-semi-bold mb-1 text-muted">
                                        Pengampu: Rp. {{ number_format($item['harga_pengampu']) }}
                                    </h6>
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
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function toggleDeskripsi() {
            const shortDesc = document.getElementById('deskripsi-short');
            const fullDesc = document.getElementById('deskripsi-full');
            if (shortDesc.style.display === 'none') {
                shortDesc.style.display = '';
                fullDesc.style.display = 'none';
            } else {
                shortDesc.style.display = 'none';
                fullDesc.style.display = '';
            }
        }
    </script>
@endsection
