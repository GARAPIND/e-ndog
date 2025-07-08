@extends('layouts.main')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')
@section('page-subtitle', 'Pesanan/detail_pesanan')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart mr-2"></i> Informasi Pesanan #{{ $transaksi->kode_transaksi }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 40%">Tanggal Transaksi</th>
                                        <td>:
                                            {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y H:i') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status Pembayaran</th>
                                        <td>:
                                            @if ($transaksi->status_pembayaran == 'Sudah Dibayar')
                                                <span class="badge badge-success">Lunas</span>
                                            @elseif($transaksi->status_pembayaran == 'Menunggu Pembayaran')
                                                <span class="badge badge-warning">Menunggu Pembayaran</span>
                                            @else
                                                <span
                                                    class="badge badge-danger">{{ ucfirst($transaksi->status_pembayaran) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status Pengiriman</th>
                                        <td>:
                                            @if ($transaksi->status_pengiriman == 'Belum Diproses')
                                                <span class="badge badge-secondary">Belum Diproses</span>
                                            @elseif($transaksi->status_pengiriman == 'Dikemas')
                                                <span class="badge badge-info">Dikemas</span>
                                            @elseif($transaksi->status_pengiriman == 'Dikirim')
                                                <span class="badge badge-primary">Dikirim</span>
                                            @elseif($transaksi->status_pengiriman == 'Selesai')
                                                <span class="badge badge-success">Selesai</span>
                                            @else
                                                <span class="badge badge-warning">{{ $transaksi->status_pengiriman }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Nota Pembayaran</th>
                                        <td>:
                                            @if ($transaksi->status_pengiriman == 'Selesai')
                                                <a href="{{ route('admin.pesanan.nota', ['transaksi_id' => $transaksi_id]) }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-file"></i> Unduh Nota
                                                </a>
                                            @else
                                                <span class="badge badge-warning">Belum Tersedia</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Metode Pembayaran</th>
                                        <td>:
                                            @if ($transaksi->is_onsite)
                                                <span class="badge badge-success">ONSITE</span>
                                            @else
                                                {{ $transaksi->is_cod ? 'COD (Cash On Delivery)' : 'Transfer Bank' }}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 40%">Pelanggan</th>
                                        <td>:
                                            @if ($transaksi->is_onsite)
                                                {{ $transaksi->nama_pelanggan_onsite }}
                                                <span class="badge badge-info">ONSITE</span>
                                            @else
                                                {{ $transaksi->pelanggan ? $transaksi->pelanggan->user->name : '-' }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Kurir</th>
                                        <td>:
                                            @if ($transaksi->is_onsite)
                                                -
                                            @else
                                                {{ $transaksi->kurir ? $transaksi->kurir->user->name : 'Belum Ditentukan' }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Catatan Pelanggan</th>
                                        <td>: {{ $transaksi->catatan_pelanggan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Catatan Penjual</th>
                                        <td>: {{ $transaksi->catatan_penjual ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-box-open mr-2"></i> Daftar Produk
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 80px">Foto</th>
                                        <th>Produk</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-right">Harga</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaksi->detail as $detail)
                                        <tr>
                                            <td>
                                                <img src="{{ $detail->produk->foto ? asset('storage/foto-produk/' . $detail->produk->foto) : asset('images/default-product.png') }}"
                                                    alt="{{ $detail->produk->nama }}" class="img-thumbnail"
                                                    style="width: 70px; height: 70px; object-fit: cover;">
                                            </td>
                                            <td>
                                                <h6 class="mb-0">{{ $detail->produk->nama }}</h6>
                                                <small class="text-muted">{{ $detail->produk->kode }}</small>
                                                <br>
                                                <small class="text-muted">Berat: {{ $detail->berat }} gram</small>
                                            </td>
                                            <td class="text-center align-middle">{{ $detail->jumlah }}</td>
                                            <td class="text-right align-middle">Rp
                                                {{ number_format($detail->produk->harga_aktif, 0, ',', '.') }}</td>
                                            <td class="text-right align-middle font-weight-bold">Rp
                                                {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="4" class="text-right font-weight-bold">Subtotal</td>
                                        <td class="text-right font-weight-bold">Rp
                                            {{ number_format($transaksi->sub_total, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right font-weight-bold">Ongkos Kirim</td>
                                        <td class="text-right font-weight-bold">Rp
                                            {{ number_format($transaksi->ongkir, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right font-weight-bold">Total</td>
                                        <td class="text-right font-weight-bold text-success h5">Rp
                                            {{ number_format($transaksi->sub_total + $transaksi->ongkir, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                @if (!$transaksi->is_onsite)
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-map-marker-alt mr-2"></i> Alamat Pengiriman
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded-circle p-3 mr-3">
                                    <i class="fas fa-user fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $transaksi->alamat->nama_penerima }}</h6>
                                    <p class="mb-0 text-muted">{{ $transaksi->alamat->no_telepon }}</p>
                                </div>
                            </div>
                            <hr>
                            <p class="mb-1"><strong>Alamat Lengkap:</strong></p>
                            <p>{{ $transaksi->alamat->alamat_lengkap }}</p>
                            <p class="mb-0">
                                {{ $transaksi->alamat->kelurahan }}, {{ $transaksi->alamat->kecamatan }}<br>
                                {{ $transaksi->alamat->kota }}, {{ $transaksi->alamat->provinsi }}
                                {{ $transaksi->alamat->kode_pos }}
                            </p>

                            @if ($transaksi->jarak)
                                <div class="mt-3 alert alert-info">
                                    <i class="fas fa-route mr-2"></i> Jarak:
                                    <strong>{{ number_format($transaksi->jarak / 1000, 2) }} km</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-store mr-2"></i> Info Pelanggan Onsite
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded-circle p-3 mr-3">
                                    <i class="fas fa-user fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $transaksi->nama_pelanggan_onsite }}</h6>
                                    <p class="mb-0 text-muted">{{ $transaksi->no_telepon_onsite }}</p>
                                </div>
                            </div>
                            <hr>
                            <p class="mb-1"><strong>Alamat:</strong></p>
                            <p>{{ $transaksi->alamat_onsite }}</p>
                            <div class="mt-3 alert alert-success">
                                <i class="fas fa-store mr-2"></i> Pembelian di Toko
                            </div>
                        </div>
                    </div>
                @endif

                @if (!$transaksi->is_onsite)
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-edit mr-2"></i> Update Status Pesanan
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="updateStatusForm">
                                @csrf
                                <div class="form-group">
                                    <label for="status">Status Pengiriman</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="Belum Diproses"
                                            {{ $transaksi->status_pengiriman == 'Belum Diproses' ? 'selected' : '' }}>Belum
                                            Diproses</option>
                                        <option value="Dikemas"
                                            {{ $transaksi->status_pengiriman == 'Dikemas' ? 'selected' : '' }}>Dikemas
                                        </option>
                                        <option value="Dikirim"
                                            {{ $transaksi->status_pengiriman == 'Dikirim' ? 'selected' : '' }}>Dikirim
                                        </option>
                                        <option value="Selesai"
                                            {{ $transaksi->status_pengiriman == 'Selesai' ? 'selected' : '' }}>Selesai
                                        </option>
                                    </select>
                                </div>

                                <div id="kurirSection" class="form-group" style="display: none;">
                                    <label for="kurir_id">Pilih Kurir</label>
                                    <select class="form-control" id="kurir_id" name="kurir_id">
                                        <option value="">-- Pilih Kurir --</option>
                                    </select>
                                    <div class="mt-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="showRecommended">
                                            <label class="custom-control-label" for="showRecommended">Tampilkan kurir yang
                                                direkomendasikan</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="catatan_penjual">Catatan untuk Pengiriman</label>
                                    <textarea class="form-control" id="catatan_penjual" name="catatan_penjual" rows="3">{{ $transaksi->catatan_penjual }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                @if ($transaksi->foto)
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-receipt mr-2"></i> Bukti Pengiriman
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ asset('storage/' . $transaksi->foto) }}" alt="Bukti Pembayaran"
                                class="img-fluid img-thumbnail">
                            <a href="{{ asset('storage/' . $transaksi->foto) }}" class="btn btn-sm btn-info mt-2"
                                target="_blank">
                                <i class="fas fa-eye mr-1"></i> Lihat Ukuran Penuh
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function loadKurirs(showRecommended = false) {
                $.ajax({
                    url: "{{ route('admin.pesanan.kurir.recommendations') }}",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $('#kurir_id').empty().append(
                                '<option value="">-- Pilih Kurir --</option>');

                            let kurirs = response.data;
                            if (showRecommended) {
                                kurirs.sort((a, b) => {
                                    if (a.is_recommended && !b.is_recommended) return -1;
                                    if (!a.is_recommended && b.is_recommended) return 1;
                                    return 0;
                                });
                            }

                            $.each(kurirs, function(index, kurir) {
                                let option = $('<option></option>')
                                    .attr('value', kurir.id)
                                    .text(kurir.user.name + ' (' + kurir.delivery_count +
                                        ' pengiriman)');

                                if (kurir.is_recommended && showRecommended) {
                                    option.text('⭐ ' + option.text() + ' (Direkomendasikan)');
                                }

                                $('#kurir_id').append(option);
                            });

                            getTransaksiData();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching kurirs:", error);
                        toastr.error('Gagal memuat data kurir.');
                    }
                });
            }

            function getTransaksiData() {
                $.ajax({
                    url: "{{ route('admin.pesanan.pesanan.get-data', $transaksi->id) }}",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (response.success && response.data.kurir_id) {
                            $('#kurir_id').val(response.data.kurir_id);
                        }
                    }
                });
            }

            $('#status').change(function() {
                if ($(this).val() === 'Dikirim') {
                    $('#kurirSection').slideDown();
                    loadKurirs($('#showRecommended').is(':checked'));
                } else {
                    $('#kurirSection').slideUp();
                }
            });

            if ($('#status').val() === 'Dikirim') {
                $('#kurirSection').show();
                loadKurirs();
            }

            $('#showRecommended').change(function() {
                loadKurirs($(this).is(':checked'));
            });

            $('#updateStatusForm').submit(function(e) {
                e.preventDefault();

                if ($('#status').val() === 'Dikirim' && !$('#kurir_id').val()) {
                    toastr.error('Silakan pilih kurir untuk pengiriman.');
                    return false;
                }

                $.ajax({
                    url: "{{ route('admin.pesanan.update-status', $transaksi->id) }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: $('#status').val(),
                        kurir_id: $('#kurir_id').val(),
                        catatan_penjual: $('#catatan_penjual').val()
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message || 'Status berhasil diperbarui.');

                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        } else {
                            toastr.error(response.message || 'Gagal memperbarui status.');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('Terjadi kesalahan pada server.');
                        }
                    }
                });
            });
        });
    </script>
@endpush
