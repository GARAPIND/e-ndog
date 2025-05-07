@extends('layouts.main')
@section('title', 'Manajemen Stok')
@section('page-title', 'Manajemen Stok')
@section('page-subtitle', 'Master/Manajemen-Stok')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="card-title">Kelola Stok Produk</h4>
                                <h6 class="card-subtitle">Tambah, kurangi, atau sesuaikan stok produk</h6>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="stok-table" class="table table-striped table-bordered no-wrap">
                                <thead>
                                    <tr>
                                        <th>Kode - Nama</th>
                                        <th>Kategori</th>
                                        <th>Stok</th>
                                        <th>Stok Minimum</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Stok -->
    <div class="modal fade" id="tambahStokModal" tabindex="-1" role="dialog" aria-labelledby="tambahStokModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahStokModalLabel">Tambah Stok</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="tambahStokForm">
                    <div class="modal-body">
                        <input type="hidden" id="tambah_produk_id" name="produk_id">
                        <input type="hidden" name="tipe" value="tambah">

                        <div class="form-group">
                            <label>Nama Produk</label>
                            <h5 id="tambah_nama_produk" class="font-weight-bold"></h5>
                        </div>

                        <div class="form-group">
                            <label for="tambah_jumlah">Jumlah Stok Masuk</label>
                            <input type="number" class="form-control" id="tambah_jumlah" name="jumlah" min="1"
                                required>
                            <div class="invalid-feedback" id="tambah-jumlah-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="tambah_keterangan">Keterangan</label>
                            <textarea class="form-control" id="tambah_keterangan" name="keterangan" rows="3"
                                placeholder="Contoh: Pembelian dari supplier"></textarea>
                            <div class="invalid-feedback" id="tambah-keterangan-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Tambah Stok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Kurangi Stok -->
    <div class="modal fade" id="kurangStokModal" tabindex="-1" role="dialog" aria-labelledby="kurangStokModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kurangStokModalLabel">Kurangi Stok</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="kurangStokForm">
                    <div class="modal-body">
                        <input type="hidden" id="kurang_produk_id" name="produk_id">
                        <input type="hidden" name="tipe" value="kurang">

                        <div class="form-group">
                            <label>Nama Produk</label>
                            <h5 id="kurang_nama_produk" class="font-weight-bold"></h5>
                        </div>

                        <div class="form-group">
                            <label for="kurang_jumlah">Jumlah Stok Keluar</label>
                            <input type="number" class="form-control" id="kurang_jumlah" name="jumlah" min="1"
                                required>
                            <div class="invalid-feedback" id="kurang-jumlah-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="kurang_keterangan">Keterangan</label>
                            <textarea class="form-control" id="kurang_keterangan" name="keterangan" rows="3"
                                placeholder="Contoh: Barang rusak"></textarea>
                            <div class="invalid-feedback" id="kurang-keterangan-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Kurangi Stok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Sesuaikan Stok -->
    <div class="modal fade" id="sesuaiStokModal" tabindex="-1" role="dialog" aria-labelledby="sesuaiStokModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sesuaiStokModalLabel">Sesuaikan Stok</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="sesuaiStokForm">
                    <div class="modal-body">
                        <input type="hidden" id="sesuai_produk_id" name="produk_id">
                        <input type="hidden" name="tipe" value="sesuai">

                        <div class="form-group">
                            <label>Nama Produk</label>
                            <h5 id="sesuai_nama_produk" class="font-weight-bold"></h5>
                        </div>

                        <div class="form-group">
                            <label for="sesuai_stok_sekarang">Stok Saat Ini</label>
                            <input type="number" class="form-control" id="sesuai_stok_sekarang" disabled>
                        </div>

                        <div class="form-group">
                            <label for="sesuai_jumlah">Jumlah Stok Aktual</label>
                            <input type="number" class="form-control" id="sesuai_jumlah" name="jumlah" min="0"
                                required>
                            <div class="invalid-feedback" id="sesuai-jumlah-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="sesuai_keterangan">Keterangan</label>
                            <textarea class="form-control" id="sesuai_keterangan" name="keterangan" rows="3"
                                placeholder="Contoh: Hasil stock opname"></textarea>
                            <div class="invalid-feedback" id="sesuai-keterangan-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info">Sesuaikan Stok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Riwayat Stok -->
    <div class="modal fade" id="historyStokModal" tabindex="-1" role="dialog" aria-labelledby="historyStokModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyStokModalLabel">Riwayat Stok</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h5 id="history_nama_produk" class="font-weight-bold"></h5>
                        <p class="mb-1">Stok Saat Ini: <span id="history_stok_sekarang"
                                class="font-weight-bold"></span></p>
                        <p>Stok Minimum: <span id="history_stok_minimum"></span></p>
                    </div>

                    <div class="table-responsive">
                        <table id="history-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                    <th>Stok Sebelum</th>
                                    <th>Stok Setelah</th>
                                    <th>Keterangan</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody id="history-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#stok-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.stok.data') }}",
                columns: [{
                        data: 'nama_lengkap',
                        name: 'nama'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori.nama'
                    },
                    {
                        data: 'stok',
                        name: 'stok'
                    },
                    {
                        data: 'stok_minimum',
                        name: 'stok_minimum'
                    },
                    {
                        data: 'status_stok',
                        name: 'stok',
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'asc']
                ]
            });

            // Reset form ketika modal ditutup
            $('#tambahStokModal, #kurangStokModal, #sesuaiStokModal').on('hidden.bs.modal', function() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $(this).find('form')[0].reset();
            });

            // Tambah Stok
            $(document).on('click', '.tambah-stok-btn', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                $('#tambah_produk_id').val(id);
                $('#tambah_nama_produk').text(nama);
                $('#tambahStokModal').modal('show');
            });

            // Kurang Stok
            $(document).on('click', '.kurang-stok-btn', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                $('#kurang_produk_id').val(id);
                $('#kurang_nama_produk').text(nama);
                $('#kurangStokModal').modal('show');
            });

            // Sesuaikan Stok
            $(document).on('click', '.sesuai-stok-btn', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                $.ajax({
                    url: "{{ url('admin/data/produk') }}" + '/' + id,
                    type: "GET",
                    success: function(response) {
                        $('#sesuai_produk_id').val(id);
                        $('#sesuai_nama_produk').text(nama);
                        $('#sesuai_stok_sekarang').val(response.stok);
                        $('#sesuai_jumlah').val(response.stok);
                        $('#sesuaiStokModal').modal('show');
                    },
                    error: function() {
                        showAlert('error', 'Gagal memuat data produk');
                    }
                });
            });

            // Riwayat Stok
            $(document).on('click', '.history-stok-btn', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                $.ajax({
                    url: "{{ url('admin/data/stok/history') }}" + '/' + id,
                    type: "GET",
                    success: function(response) {
                        $('#history_nama_produk').text(nama);
                        $('#history_stok_sekarang').text(response.produk.stok);
                        $('#history_stok_minimum').text(response.produk.stok_minimum);

                        var tbody = '';
                        if (response.histories.length > 0) {
                            response.histories.forEach(function(item) {
                                var tipeLabel = getTipeLabel(item.tipe);
                                var jumlahFormat = formatJumlah(item.jumlah, item.tipe);

                                tbody += '<tr>';
                                tbody += '<td>' + formatDate(item.created_at) + '</td>';
                                tbody += '<td>' + tipeLabel + '</td>';
                                tbody += '<td>' + jumlahFormat + '</td>';
                                tbody += '<td>' + item.stok_sebelum + '</td>';
                                tbody += '<td>' + item.stok_setelah + '</td>';
                                tbody += '<td>' + (item.keterangan || '-') + '</td>';
                                tbody += '<td>' + (item.user ? item.user.name : '-') +
                                    '</td>';
                                tbody += '</tr>';
                            });
                        } else {
                            tbody +=
                                '<tr><td colspan="7" class="text-center">Tidak ada data riwayat</td></tr>';
                        }

                        $('#history-table-body').html(tbody);
                        $('#historyStokModal').modal('show');
                    },
                    error: function() {
                        showAlert('error', 'Gagal memuat riwayat stok');
                    }
                });
            });

            // Submit form tambah stok
            $('#tambahStokForm').on('submit', function(e) {
                e.preventDefault();
                submitStokForm($(this), 'Stok berhasil ditambahkan');
            });

            // Submit form kurang stok
            $('#kurangStokForm').on('submit', function(e) {
                e.preventDefault();
                submitStokForm($(this), 'Stok berhasil dikurangi');
            });

            // Submit form sesuaikan stok
            $('#sesuaiStokForm').on('submit', function(e) {
                e.preventDefault();
                submitStokForm($(this), 'Stok berhasil disesuaikan');
            });

            function submitStokForm(form, successMessage) {
                $.ajax({
                    url: "{{ route('admin.stok.update') }}",
                    type: "POST",
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        form.closest('.modal').modal('hide');
                        table.ajax.reload();
                        showAlert('success', successMessage);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + form.attr('id').split('Form')[0] + '_' + key).addClass(
                                    'is-invalid');
                                $('#' + form.attr('id').split('Form')[0] + '-' + key + '-error')
                                    .text(value[0]);
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.error) {
                            showAlert('error', xhr.responseJSON.error);
                        } else {
                            showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            }

            function formatDate(dateString) {
                var date = new Date(dateString);
                return date.toLocaleString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            function getTipeLabel(tipe) {
                switch (tipe) {
                    case 'tambah':
                        return '<span class="badge badge-success">Tambah</span>';
                    case 'kurang':
                        return '<span class="badge badge-warning">Kurang</span>';
                    case 'sesuai':
                        return '<span class="badge badge-info">Sesuaikan</span>';
                    default:
                        return tipe;
                }
            }

            function formatJumlah(jumlah, tipe) {
                if (tipe === 'tambah') {
                    return '+' + jumlah;
                } else if (tipe === 'kurang') {
                    return '-' + jumlah;
                } else {
                    return jumlah;
                }
            }
        });
    </script>
@endsection
