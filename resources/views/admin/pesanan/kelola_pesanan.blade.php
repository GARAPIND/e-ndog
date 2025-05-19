@extends('layouts.main')

@section('title', 'Kelola Pesanan')
@section('page-title', 'Kelola Pesanan')
@section('page-subtitle', 'Pesanan/kelola_pesanan')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title">Tabel Pesanan</h4>
                        <h6 class="card-subtitle">Tabel untuk mengelola data Pesanan</h6>
                    </div>
                    <div>
                        <select id="filter-status" class="form-control select2">
                            <option value="">Semua Status</option>
                            <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
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
                                <th>Total</th>
                                <th>Status Pembayaran</th>
                                <th>Status Pengiriman</th>
                                <th>COD</th>
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

    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Ubah Status Pengiriman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="status-form">
                        <input type="hidden" id="transaksi-id" name="transaksi_id">
                        <input type="hidden" id="is_cod" name="is_cod">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Pengiriman</label>
                            <select class="form-control select2" id="status" name="status">
                                <option value="Dikemas">Dikemas</option>
                                <option value="Dikirim">Dikirim</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>

                        <div class="mb-3 kurir-selection" style="display:none;">
                            <label for="kurir_id" class="form-label">Pilih Kurir</label>
                            <select class="form-control select2" id="kurir_id" name="kurir_id">
                                <option value="">Pilih Kurir</option>
                            </select>
                            <small class="text-muted">* Kurir yang direkomendasikan ditandai</small>
                        </div>

                        <div class="mb-3 catatan-penjual" style="display:none;">
                            <label for="catatan_penjual" class="form-label">Catatan Penjual</label>
                            <textarea class="form-control" id="catatan_penjual" name="catatan_penjual" rows="3"
                                placeholder="Masukkan catatan untuk pesanan ini..."></textarea>
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
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#pesanan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.pesanan.data') }}",
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
                        data: 'total',
                        name: 'total',
                        render: function(data) {
                            return 'Rp ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'status_pembayaran',
                        name: 'status_pembayaran'
                    },

                    {
                        data: 'status_pengiriman',
                        name: 'status_pengiriman',
                        render: function(data) {
                            let badgeClass = 'badge text-white ';
                            if (data === 'Dikemas') {
                                badgeClass += 'bg-warning';
                            } else if (data === 'Dikirim') {
                                badgeClass += 'bg-info';
                            } else if (data === 'Selesai') {
                                badgeClass += 'bg-success';
                            } else {
                                badgeClass += 'bg-primary';
                            }
                            return '<span class="' + badgeClass + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'is_cod',
                        name: 'is_cod',
                        render: function(data) {
                            let badgeClass = 'badge text-white ';
                            if (data === 1) {
                                badgeClass += 'bg-success';
                                return '<span class="' + badgeClass + '">' + 'Aktif' + '</span>';
                            } else {
                                badgeClass += 'bg-danger';
                                return '<span class="' + badgeClass + '">' + 'Tidak' + '</span>';
                            }
                        }
                    },
                    {
                        data: 'id',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let buttons = `
                                <a href="/admin/pesanan/${data}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <button type="button" class="btn btn-sm btn-success change-status" data-id="${data}" data-status="${row.status_pengiriman}" data-cod="${row.is_cod}">
                                    <i class="fas fa-sync-alt"></i> Status
                                </button>
                            `;
                            return buttons;
                        }
                    },
                ]
            });

            $('#filter-status').change(function() {
                table.ajax.reload();
            });

            function loadKurir() {
                $.ajax({
                    url: "{{ route('admin.pesanan.kurir.recommendations') }}",
                    type: 'GET',
                    success: function(response) {
                        var options = '<option value="">Pilih Kurir</option>';

                        $.each(response.data, function(index, kurir) {
                            var recommended = kurir.is_recommended ? ' ⭐ (Direkomendasikan)' :
                                '';
                            options +=
                                `<option value="${kurir.id}">${kurir.user.name}${recommended}</option>`;
                        });

                        $('#kurir_id').html(options);
                    },
                    error: function(xhr) {
                        showAlert('error', 'Gagal memuat data kurir. Silakan coba lagi.');
                    }
                });
            }

            $('#status').change(function() {
                var status = $(this).val();
                var cod = $('#is_cod').val();
                $('.kurir-selection, .catatan-penjual').hide();
                $('#kurir_id').prop('required', false);

                if (status === 'Dikirim') {
                    if (cod == 1) {
                        $('.kurir-selection').show();
                        $('#kurir_id').prop('required', true);
                        loadKurir();
                    }
                    $('.catatan-penjual').show();
                } else if (status === 'Selesai') {
                    $('.catatan-penjual').show();
                }
            });

            $(document).on('click', '.change-status', function() {
                var id = $(this).data('id');
                var cod = $(this).data('cod');
                // console.log(cod);
                var currentStatus = $(this).data('status');

                $('#status-form')[0].reset();
                $('.kurir-selection, .catatan-penjual').hide();

                $('#transaksi-id').val(id);
                $('#is_cod').val(cod);
                $('#status').val(currentStatus).trigger('change');

                $.ajax({
                    url: `/admin/pesanan/${id}/get-data`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            if (response.data.catatan_penjual) {
                                $('#catatan_penjual').val(response.data.catatan_penjual);
                            }
                            if (response.data.kurir_id) {
                                $('#kurir_id').val(response.data.kurir_id);
                            }
                        }
                    }
                });

                $('#statusModal').modal('show');
            });

            $('#save-status').click(function() {
                var id = $('#transaksi-id').val();
                var status = $('#status').val();
                var formData = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    status: status,
                    catatan_penjual: $('#catatan_penjual').val()
                };

                if (status === 'Dikirim' && $('#kurir_id').val()) {
                    formData.kurir_id = $('#kurir_id').val();
                }

                $.ajax({
                    url: `/admin/pesanan/${id}/update-status`,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#statusModal').modal('hide');
                        table.ajax.reload();
                        showAlert('success', 'Status pengiriman berhasil diperbarui.');
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                showAlert('error', value[0]);
                            });
                        } else {
                            showAlert('error',
                                'Gagal memperbarui status pengiriman. Silakan coba lagi.');
                        }
                    }
                });
            });

            $('#statusModal .btn-close, #statusModal .btn-secondary').click(function() {
                $('#statusModal').modal('hide');
            });
        });
    </script>
@endsection
