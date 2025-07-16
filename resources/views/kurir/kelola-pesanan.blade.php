@extends('layouts.main')

@section('title', 'Kelola Pengiriman')
@section('page-title', 'Kelola Pengiriman')
@section('page-subtitle', 'Pengiriman/kelola_pengiriman')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title">Daftar Pengiriman</h4>
                        <h6 class="card-subtitle">Daftar pesanan yang perlu dikirim</h6>
                    </div>
                    <div>
                        <select id="filter-status" class="form-control select2">
                            <option value="">Semua Status</option>
                            <option value="Dikemas">Dikemas</option>
                            <option value="Dikirim">Dikirim</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="pesanan-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Kode Transaksi</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Lokasi</th>
                                <th>Foto Bukti</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Pesanan</h5>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Informasi Pesanan</h6>
                            <p><strong>Kode Transaksi:</strong> <span id="detail-kode"></span></p>
                            <p><strong>Tanggal:</strong> <span id="detail-tanggal"></span></p>
                            <p><strong>Status:</strong> <span id="detail-status"></span></p>
                            <p><strong>COD:</strong> <span id="detail-cod"></span></p>
                            <p><strong>Ongkir:</strong> <span id="detail-ongkir"></span></p>
                            <p><strong>Total:</strong> <span id="detail-total"></span></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Informasi Pelanggan</h6>
                            <p><strong>Nama:</strong> <span id="detail-pelanggan"></span></p>
                            <p><strong>Alamat:</strong> <span id="detail-alamat"></span></p>
                            <p><strong>Catatan Pelanggan:</strong> <span id="detail-catatan-pelanggan"></span></p>
                            <p><strong>Catatan Penjual:</strong> <span id="detail-catatan-penjual"></span></p>
                        </div>
                    </div>
                    <h6>Produk</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detail-produk">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Update Status Pengiriman</h5>
                </div>
                <div class="modal-body">
                    <form id="status-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="transaksi-id" name="transaksi_id">

                        <div class="mb-3 d-none">
                            <label for="status" class="form-label">Status Pengiriman</label>
                            <select class="form-control" id="status" name="status">
                                <option value="Dikemas">Dikemas</option>
                                <option value="Dikirim">Dikirim</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>

                        <div class="mb-3" id="foto-bukti-container" style="display:none;">
                            <label for="foto_bukti" class="form-label">Foto Bukti Pengiriman</label>
                            <input type="file" class="form-control" id="foto_bukti" name="foto_bukti" accept="image/*">
                            <small class="text-muted">Upload foto sebagai bukti pengiriman (maksimal 2MB)</small>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                            <input type="date" class="form-control" id="tanggal_sampai" name="tanggal_sampai"
                                value="{{ date('Y-m-d') }}">

                        </div>

                        <div class="mb-3">
                            <label for="catatan_kurir" class="form-label">Catatan Pengiriman</label>
                            <textarea class="form-control" id="catatan_kurir" name="catatan_kurir" rows="3"
                                placeholder="Masukkan catatan pengiriman (opsional)..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="save-status">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk menampilkan foto bukti -->
    <div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fotoModalLabel">Foto Bukti Pengiriman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="foto-bukti-preview" src="" alt="Foto Bukti" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#pesanan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('kurir.pesanan.data') }}",
                    data: function(d) {
                        d.status = $('#filter-status').val();
                    }
                },
                columns: [{
                        data: 'kode_transaksi',
                        name: 'kode_transaksi'
                    },
                    {
                        data: 'tanggal_transaksi',
                        name: 'tanggal_transaksi'
                    },
                    {
                        data: 'pelanggan',
                        name: 'pelanggan'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'status_pengiriman',
                        name: 'status_pengiriman'
                    },
                    {
                        data: 'maps',
                        name: 'maps',
                    },
                    {
                        data: 'foto',
                        name: 'foto',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            if (data) {
                                return '<button class="btn btn-sm btn-info btn-foto" data-foto="' +
                                    data + '"><i class="fas fa-image"></i></button>';
                            }
                            return '<span class="badge bg-secondary text-white">Tidak Ada</span>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'desc']
                ]
            });

            $('#filter-status').change(function() {
                table.draw();
            });

            $(document).on('click', '.btn-foto', function() {
                var foto = $(this).data('foto');
                var fotoUrl = "{{ asset('storage') }}/" + foto;
                $('#foto-bukti-preview').attr('src', fotoUrl);
                $('#fotoModal').modal('show');
            });

            $('.modal').on('click', function(e) {
                if ($(e.target).hasClass('modal')) {
                    $(this).modal('hide');
                }
            });

            $(document).on('click', '.btn-detail', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ url('kurir/pesanan') }}/" + id,
                    type: 'GET',
                    success: function(response) {
                        var data = response.data;
                        console.log(data);
                        $('#detail-kode').text(data.kode_transaksi);
                        $('#detail-tanggal').text(data.tanggal_transaksi);
                        $('#detail-status').text(data.status_pengiriman);
                        $('#detail-ongkir').text('Rp ' + numberFormat(data.ongkir));

                        $('#detail-cod').text(data.is_cod == 1 ? 'Ya' : 'Tidak');
                        $('#detail-total').text('Rp ' + numberFormat(data.sub_total + data
                            .ongkir));
                        $('#detail-pelanggan').text(data.pelanggan.user.name);
                        $('#detail-alamat').text(data.alamat.alamat + ', ' + data.alamat
                            .kecamatan + ', ' + data.alamat
                            .kota + ', ' + data.alamat.provinsi + ', ' + data.alamat
                            .kode_pos);
                        $('#detail-catatan-pelanggan').text(data.catatan_pelanggan || '-');
                        $('#detail-catatan-penjual').text(data.catatan_penjual || '-');

                        var productHtml = '';
                        $.each(data.detail, function(i, item) {
                            productHtml += '<tr>' +
                                '<td>' + item.produk.nama + '</td>' +
                                '<td>' + item.jumlah + '</td>' +
                                '<td>Rp ' + numberFormat(item.produk.harga) + '</td>' +
                                '<td>Rp ' + numberFormat(item.produk.harga * item
                                    .jumlah) +
                                '</td>' +
                                '</tr>';
                        });
                        $('#detail-produk').html(productHtml);

                        $('#detailModal').modal('show');
                    }
                });
            });

            $(document).on('click', '.btn-status', function() {
                var id = $(this).data('id');
                var status = $(this).data('status');

                $('#transaksi-id').val(id);
                $('#status').val(status);

                togglePhotoUpload();

                $('#statusModal').modal('show');
            });

            $(document).on('click', '.btn-maps', function() {
                var lat = $(this).data('lat');
                var lng = $(this).data('lng');
                console.log(lat, lng);
                var mapsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' + lat + ',' + lng +
                    '&travelmode=driving';
                window.open(mapsUrl, '_blank');
            });


            $('#status').change(function() {
                togglePhotoUpload();
            });

            function togglePhotoUpload() {
                if ($('#status').val() === 'Dikirim') {
                    $('#foto-bukti-container').show();
                    $('#foto_bukti').prop('required', true);
                } else {
                    $('#foto-bukti-container').hide();
                    $('#foto_bukti').prop('required', false);
                }
            }

            $('#save-status').click(function() {
                var formData = new FormData($('#status-form')[0]);
                var transaksiId = $('#transaksi-id').val();

                if ($('#status').val() === 'Dikirim') {
                    if (!$('#foto_bukti').val() && $('#foto_bukti').prop('required')) {
                        showAlert('error', 'Foto bukti pengiriman wajib diupload!');
                        return;
                    }
                }

                $.ajax({
                    url: "{{ url('kurir/pesanan') }}/" + transaksiId + "/update-status",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#save-status').prop('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
                        );
                    },
                    success: function(response) {
                        $('#statusModal').modal('hide');
                        $('#status-form')[0].reset();
                        table.ajax.reload();
                        showAlert('success', response.message);
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            $.each(errors, function(key, value) {
                                showAlert('error', value[0]);
                            });
                        } else {
                            showAlert('error', 'Terjadi kesalahan saat menyimpan data.');
                        }
                    },
                    complete: function() {
                        $('#save-status').prop('disabled', false).text('Simpan');
                    }
                });
            });

            function numberFormat(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }
        });
    </script>
@endsection
