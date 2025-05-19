@extends('layouts.main')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')
@section('page-subtitle', 'Pesanan/detail_pesanan')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Informasi Pesanan</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td width="150">Kode Transaksi</td>
                                <td>: {{ $transaksi->kode_transaksi }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td>: {{ date('d-m-Y H:i', strtotime($transaksi->tanggal_transaksi)) }}</td>
                            </tr>
                            <tr>
                                <td>Status Pembayaran</td>
                                <td>: <span
                                        class="badge bg-{{ $transaksi->status_pembayaran == 'Lunas' ? 'success' : 'warning' }}">{{ $transaksi->status_pembayaran }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Status Pengiriman</td>
                                <td>: <span
                                        class="badge bg-{{ $transaksi->status_pengiriman == 'Selesai' ? 'success' : ($transaksi->status_pengiriman == 'Dikirim' ? 'primary' : 'secondary') }}">{{ $transaksi->status_pengiriman }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>COD</td>
                                <td>: {{ $transaksi->is_cod ? 'Ya' : 'Tidak' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Informasi Pelanggan</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td width="150">Nama</td>
                                <td>: {{ $transaksi->pelanggan->nama }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>: {{ $transaksi->alamat->alamat_lengkap }}, {{ $transaksi->alamat->kelurahan }},
                                    {{ $transaksi->alamat->kecamatan }}, {{ $transaksi->alamat->kota }},
                                    {{ $transaksi->alamat->provinsi }}</td>
                            </tr>
                            <tr>
                                <td>Catatan Pelanggan</td>
                                <td>: {{ $transaksi->catatan_pelanggan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Catatan Penjual</td>
                                <td>: {{ $transaksi->catatan_penjual ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <h5>Detail Produk</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaksi->detail as $detail)
                                        <tr>
                                            <td>{{ $detail->produk->nama_produk }}</td>
                                            <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                            <td>{{ $detail->jumlah }}</td>
                                            <td>Rp {{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Subtotal</strong></td>
                                        <td>Rp {{ number_format($transaksi->sub_total, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Ongkir</strong></td>
                                        <td>Rp {{ number_format($transaksi->ongkir, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                                        <td>Rp {{ number_format($transaksi->sub_total + $transaksi->ongkir, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                @if ($transaksi->status_pengiriman === 'Dikirim' || $transaksi->status_pengiriman === 'Selesai')
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>Bukti Pengiriman</h5>
                            @if ($transaksi->foto)
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $transaksi->foto) }}" alt="Bukti Pengiriman"
                                        class="img-fluid rounded" style="max-height: 400px;">
                                </div>
                                <div class="text-center mt-2">
                                    <p class="text-muted">Foto bukti pengiriman oleh kurir:
                                        {{ $transaksi->kurir->nama ?? '-' }}</p>
                                </div>
                            @else
                                <p class="text-center text-muted">Tidak ada foto bukti pengiriman yang diupload</p>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="d-flex justify-content-end">
                    <a href="{{ route('dashboard.admin.pesanan.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
