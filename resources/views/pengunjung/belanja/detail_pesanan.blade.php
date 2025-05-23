@extends('layouts_pengunjung.main')
@section('title', 'Detail Pesanan')
@section('content')
    <!-- CSS tambahan untuk tampilan yang lebih menarik -->
    <style>
        .order-card {
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
            transition: transform 0.3s;
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
            padding: 1rem 1.5rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 30px;
            font-weight: 600;
        }

        .product-img {
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .highlight-text {
            font-weight: 600;
            color: #3D464D;
        }

        .info-icon {
            width: 30px;
            color: #FFD333;
        }

        .action-btn {
            border-radius: 5px;
            padding: 8px 16px;
            transition: all 0.3s;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .product-card {
            border-left: 5px solid #FFD333;
        }
    </style>
    </style>

    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ route('dashboard.pengunjung') }}">Home</a>
                    <a class="breadcrumb-item text-dark" href="{{ route('belanja.pesanan') }}">Pesanan</a>
                    <span class="breadcrumb-item active">Detail Pesanan</span>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-5">
        <div class="row px-xl-5">
            <div class="col-lg-12">
                <div class="card order-card border-secondary mb-4">
                    <div class="card-header bg-secondary border-0 d-flex justify-content-between align-items-center">
                        <h4 class="font-weight-semi-bold m-0">
                            <i class="fas fa-shopping-bag mr-2"></i>Detail Pesanan #{{ $data->kode_transaksi }}
                        </h4>
                        @php
                            $status = strtolower($data->status_pengiriman);
                            $badgeClass = 'bg-info';
                            $icon = 'fas fa-truck';

                            if ($status === 'dikemas') {
                                $badgeClass = 'bg-warning';
                                $icon = 'fas fa-box';
                            } elseif ($status === 'dikirim') {
                                $badgeClass = 'bg-dark';
                                $icon = 'fas fa-shipping-fast';
                            } elseif ($status === 'selesai') {
                                $badgeClass = 'bg-success';
                                $icon = 'fas fa-check-circle';
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }} text-white status-badge">
                            <i class="{{ $icon }} mr-1"></i> {{ ucfirst($data->status_pengiriman) }}
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light border-0 p-3 h-100">
                                    <h5 class="mb-3 d-flex align-items-center">
                                        <i class="fas fa-user-circle info-icon mr-2"></i>
                                        Informasi Pemesan
                                    </h5>
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span><i class="fas fa-user mr-2"></i>Nama:</span>
                                            <span class="highlight-text">{{ $data->pelanggan->user->name }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span><i class="fas fa-map-marker-alt mr-2"></i>Alamat:</span>
                                            <span class="highlight-text text-right">{{ $data->alamat->alamat }}
                                                {{ $data->alamat->kecamatan }} {{ $data->alamat->kota }}
                                                {{ $data->alamat->provinsi }} {{ $data->alamat->kode_pos }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span><i class="fas fa-phone-alt mr-2"></i>Telepon:</span>
                                            <span class="highlight-text">{{ $data->pelanggan->telp }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><i class="fas fa-calendar-alt mr-2"></i>Tanggal:</span>
                                            <span
                                                class="highlight-text">{{ \Carbon\Carbon::parse($data->created_at)->locale('id')->translatedFormat('l, d F Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0 p-3 h-100">
                                    <h5 class="mb-3 d-flex align-items-center">
                                        <i class="fas fa-shipping-fast info-icon mr-2"></i>
                                        Status Pengiriman
                                    </h5>
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span><i class="fas fa-credit-card mr-2"></i>Status Pembayaran:</span>
                                            @php
                                                $statusPembayaran = strtolower($data->status_pembayaran);
                                                $badgePembayaranClass = 'bg-secondary';
                                                $iconPembayaran = 'fas fa-info-circle';

                                                if ($statusPembayaran === 'sudah dibayar') {
                                                    $badgePembayaranClass = 'bg-success';
                                                    $iconPembayaran = 'fas fa-check-circle';
                                                } elseif ($statusPembayaran === 'menunggu pembayaran') {
                                                    $badgePembayaranClass = 'bg-warning';
                                                    $iconPembayaran = 'fas fa-hourglass-half';
                                                } elseif ($statusPembayaran === 'kadaluarsa') {
                                                    $badgePembayaranClass = 'bg-dark';
                                                    $iconPembayaran = 'fas fa-clock';
                                                } elseif ($statusPembayaran === 'pembayaran ditolak') {
                                                    $badgePembayaranClass = 'bg-danger';
                                                    $iconPembayaran = 'fas fa-times-circle';
                                                } elseif ($statusPembayaran === 'dibatalkan') {
                                                    $badgePembayaranClass = 'bg-secondary';
                                                    $iconPembayaran = 'fas fa-ban';
                                                }
                                            @endphp
                                            <span class="badge {{ $badgePembayaranClass }} text-white status-badge">
                                                <i
                                                    class="{{ $iconPembayaran }} mr-1"></i>{{ ucfirst($data->status_pembayaran) }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span><i class="fas fa-truck mr-2"></i>Status Pengiriman:</span>
                                            @php
                                                $status = strtolower($data->status_pengiriman);
                                                $badgeClass = 'bg-info';
                                                $icon = 'fas fa-truck';

                                                if ($status === 'dikemas') {
                                                    $badgeClass = 'bg-warning';
                                                    $icon = 'fas fa-box';
                                                } elseif ($status === 'dikirim') {
                                                    $badgeClass = 'bg-dark';
                                                    $icon = 'fas fa-shipping-fast';
                                                } elseif ($status === 'selesai') {
                                                    $badgeClass = 'bg-success';
                                                    $icon = 'fas fa-check-circle';
                                                }
                                            @endphp
                                            <span class="badge {{ $badgeClass }} text-white status-badge">
                                                <i class="{{ $icon }} mr-1"></i>
                                                {{ ucfirst($data->status_pengiriman) }}
                                            </span>
                                        </div>
                                        @if ($data->cancel !== null)
                                            @if ($data->cancel == 0)
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span><i class="fas fa-hourglass-half mr-2"></i>Status:</span>
                                                    <span class="highlight-text">Proses Pembatalan <small>(Menunggu validasi
                                                            dari admin)</small></span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span><i class="fas fa-comment-dots mr-2"></i>Alasan:</span>
                                                    <span class="highlight-text">{{ $data->catatan_cancel }}</span>
                                                </div>
                                            @elseif ($data->cancel == 2)
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span><i class="fas fa-hourglass-half mr-2"></i>Status:</span>
                                                    <span class="highlight-text">Pembatalan ditolak</small></span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span><i class="fas fa-comment-dots mr-2"></i>Alasan Pembatalan:</span>
                                                    <span class="highlight-text">{{ $data->catatan_cancel }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span><i class="fas fa-comment-dots mr-2"></i>Alasan Ditolak:</span>
                                                    <span
                                                        class="highlight-text">{{ $data->catatan_cancel_penjual ?? '-' }}</span>
                                                </div>
                                            @elseif ($data->cancel == 1)
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span><i class="fas fa-hourglass-half mr-2"></i>Status:</span>
                                                    <span class="highlight-text">Pembatalan disetujui</small></span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span><i class="fas fa-comment-dots mr-2"></i>Alasan Pembatalan:</span>
                                                    <span class="highlight-text">{{ $data->catatan_cancel }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span><i class="fas fa-comment-dots mr-2"></i>Catatan Penjual:</span>
                                                    <span
                                                        class="highlight-text">{{ $data->catatan_cancel_penjual ?? '-' }}</span>
                                                </div>
                                            @endif
                                        @endif

                                        @if ($data->status_pengiriman == 'Dikirim' && $data->is_cod == 1)
                                            <div class="d-flex justify-content-between mb-3">
                                                <span><i class="fas fa-box mr-2"></i>Kurir E-Ndog:</span>
                                                <span class="highlight-text">{{ $data->kurir->user->name }}</span>
                                            </div>
                                        @elseif ($data->status_pengiriman == 'Dikirim' && $data->is_cod == 0)
                                            <div class="d-flex justify-content-between mb-3">
                                                <span><i class="fas fa-box mr-2"></i>Ekspedisi:</span>
                                                <span class="highlight-text">{{ $data->ekspedisi }}</span>
                                            </div>
                                        @endif

                                        @if ($data->foto)
                                            <div class="d-flex justify-content-between mb-3">
                                                <span><i class="fas fa-box mr-2"></i>Catatan Kurir:</span>
                                                <span>{{ $data->catatan_kurir }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span><i class="fas fa-image mr-2"></i>Foto Bukti:</span>
                                                <span>
                                                    <a href="{{ asset('storage/' . $data->foto) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $data->foto) }}" alt="Bukti Foto"
                                                            style="max-width: 120px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                                                    </a>
                                                </span>
                                            </div>
                                        @endif
                                        {{-- <div class="d-flex justify-content-between mb-2">
                                            <span><i class="fas fa-barcode mr-2"></i>No. Resi:</span>
                                            <span class="highlight-text">JNE12345678901</span>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card order-card border-secondary mb-4">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">
                            <i class="fas fa-box-open mr-2"></i>Produk Pesanan
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12">
                                @foreach ($data->detail as $item)
                                    <div class="card product-card p-3 mb-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-7">
                                                <div class="d-flex align-items-center">
                                                    <img src="/storage/foto-produk/{{ $item->produk->foto }}"
                                                        alt="Product Image" class="product-img"
                                                        style="width:80px; height:80px;">
                                                    <div class="ml-4">
                                                        <h5 class="mb-1">{{ $item->produk->nama }}</h5>
                                                        <p class="text-muted mb-0">
                                                            <i class="fas fa-weight-hanging mr-1"></i> Berat:
                                                            {{ number_format($item->produk->berat) }} gr
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="row text-center">
                                                    <div class="col-4">
                                                        <div class="d-flex flex-column">
                                                            @php
                                                                $hargaLabel = [
                                                                    'ecer' => 'Harga (Ecer)',
                                                                    'grosir' => 'Harga (Grosir)',
                                                                    'pengampu' => 'Harga (Pengampu)',
                                                                ];
                                                                $hargaValue = [
                                                                    'ecer' => $item->produk->harga,
                                                                    'grosir' => $item->produk->harga_grosir,
                                                                    'pengampu' => $item->produk->harga_pengampu,
                                                                ];
                                                                $status = $item->status_harga;
                                                            @endphp
                                                            <small
                                                                class="text-muted mb-1">{{ $hargaLabel[$status] ?? '-' }}</small>
                                                            <span class="highlight-text">
                                                                Rp
                                                                {{ number_format($hargaValue[$status] ?? 0, 0, ',', '.') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="d-flex flex-column">
                                                            <small class="text-muted mb-1">Jumlah</small>
                                                            <span class="highlight-text">
                                                                <i class="fas fa-times mr-1"></i>{{ $item->jumlah }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="d-flex flex-column">
                                                            <small class="text-muted mb-1">Subtotal</small>
                                                            <span
                                                                class="highlight-text text-dark">{{ rupiahFormat($item->sub_total) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card order-card border-secondary mb-5">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">
                            <i class="fas fa-money-check-alt mr-2"></i>Ringkasan Pembayaran
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <div class="p-4 bg-light rounded">
                                    <div class="d-flex justify-content-between mb-3 pt-1">
                                        <h6 class="font-weight-medium">
                                            <i class="fas fa-shopping-cart mr-2"></i>Sub Total
                                        </h6>
                                        <h6 class="font-weight-medium">{{ rupiahFormat($data->sub_total) }}</h6>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <h6 class="font-weight-medium">
                                            <i class="fas fa-truck mr-2"></i>Ongkos Kirim
                                        </h6>
                                        <h6 class="font-weight-medium">{{ rupiahFormat($data->ongkir) }}</h6>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mt-2">
                                        <h5 class="font-weight-bold">
                                            <i class="fas fa-coins mr-2"></i>Grand Total
                                        </h5>
                                        <h5 class="font-weight-bold text-primary">
                                            {{ rupiahFormat($data->sub_total + $data->ongkir) }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-0 bg-light">
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <a href="{{ route('belanja.pesanan') }}" class="btn btn-secondary action-btn">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Pesanan
                            </a>

                            <div>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profile->whatsapp) }}?text=Halo%20saya%20ingin%20menanyakan%20pesanan%20{{ $data->kode_transaksi }}"
                                    target="_blank" class="btn btn-info action-btn mr-2">
                                    <i class="fas fa-comments mr-1"></i>Hubungi Penjual
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
