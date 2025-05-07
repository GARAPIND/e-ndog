@extends('layouts_pengunjung.main')
@section('title', 'Transaksi Sukses')

@section('content')
    <div class="container my-5">
        <div class="text-center mb-5">
            <div class="display-1 text-danger mb-3">
                <i class="fas fa-times-circle fa-shake"></i>
            </div>
            <h2 class="fw-bold">Transaksi Gagal!</h2>
            <p class="lead text-muted">Maaf, terjadi kesalahan saat memproses transaksi Anda</p>
        </div>

        <div class="card shadow border-0 mb-5">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-receipt me-2"></i> Detail Transaksi
                    </h5>
                    <span class="badge bg-danger px-3 py-2 text-white">
                        <i class="fas fa-check me-1"></i> Pembayaran Gagal
                    </span>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="card mb-4 border-start border-primary border-4">
                            <div class="card-header bg-transparent">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle text-primary me-2"></i> Informasi Transaksi
                                </h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span><i class="fas fa-hashtag text-muted me-2"></i> Kode Transaksi</span>
                                        <span class="fw-bold">{{ $data->kode_transaksi }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span><i class="fas fa-calendar-alt text-muted me-2"></i> Tanggal Transaksi</span>
                                        <span>{{ \Carbon\Carbon::parse($data->tanggal_transaksi)->translatedFormat('d F Y') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span><i class="fas fa-map-marker-alt text-muted me-2"></i> Alamat</span>
                                        <span class="text-end">{{ $data->alamat->alamat }} {{ $data->alamat->kota }}
                                            ({{ $data->alamat->keterangan }})</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span><i class="fas fa-money-check-alt text-muted me-2"></i> Status
                                            Pembayaran</span>
                                        <span class="text-success fw-bold">{{ $data->status_pembayaran }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span><i class="fas fa-shipping-fast text-muted me-2"></i> Ekspedisi</span>
                                        <span>{{ $data->ekspedisi }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Data Produk -->
                    <div class="col-md-5">
                        <div class="card mb-4 border-start border-secondary border-4">
                            <div class="card-header bg-transparent">
                                <h5 class="mb-0">
                                    <i class="fas fa-shopping-bag text-secondary me-2"></i> Data Produk
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" style="max-height: 250px; overflow-x: auto;">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Produk</th>
                                                <th class="text-center">Jumlah</th>
                                                <th class="text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data->detail as $item)
                                                <tr>
                                                    <td>{{ $item->produk->nama }}</td>
                                                    <td class="text-center">{{ $item->jumlah }}</td>
                                                    <td class="text-end">{{ rupiahFormat($data->sub_total) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Ringkasan Pembayaran -->
                <div class="card border-start border-success border-4">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">
                            <i class="fas fa-file-invoice-dollar text-success me-2"></i> Ringkasan Pembayaran
                        </h5>
                    </div>
                    <div class="card-body pt-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Total Belanja</span>
                                <span>{{ rupiahFormat($data->sub_total) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Ongkir</span>
                                <span>{{ rupiahFormat($data->ongkir) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0 fw-bold">
                                <span>Total Pembayaran</span>
                                <span>{{ rupiahFormat($data->sub_total + $data->ongkir) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- Tombol Aksi -->
            <div class="card-footer bg-white text-center py-4">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <a href="{{ route('belanja.pesanan') }}" class="btn btn-outline-danger w-100">
                            <i class="fas fa-box me-2"></i> Cek Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Tambahan -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-primary mr-3">
                        <i class="fas fa-headset fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Butuh Bantuan?</h6>
                        <p class="mb-0 text-muted small">Hubungi customer service kami di <a
                                href="#">{{ $profileToko->telepon }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
