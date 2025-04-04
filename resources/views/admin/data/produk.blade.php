@extends('layouts.main')
@section('title', 'Kelola Produk')
@section('page-title', 'Kelola Produk')
@section('page-subtitle', 'Master/Kelola-Produk')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="card-title">Tabel Produk</h4>
                                <h6 class="card-subtitle">Tabel untuk mengelola data produk</h6>
                            </div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProdukModal">
                                <i class="fas fa-plus-circle"></i> Tambah Produk
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="produk-table" class="table table-striped table-bordered no-wrap">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
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

    <div class="modal fade" id="addProdukModal" tabindex="-1" role="dialog" aria-labelledby="addProdukModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProdukModalLabel">Tambah Produk Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addProdukForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">Nama Produk</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                    <div class="invalid-feedback" id="nama-error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="kode">Kode Produk</label>
                                    <input type="text" class="form-control" id="kode" name="kode" required>
                                    <div class="invalid-feedback" id="kode-error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="kategori_id">Kategori</label>
                                    <select class="form-control" id="kategori_id" name="kategori_id">
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($kategoriList as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="kategori_id-error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="foto">Foto Produk</label>
                                    <input type="file" class="form-control-file" id="foto" name="foto">
                                    <small class="form-text text-muted">Format: JPG, JPEG, PNG. Maks: 2MB</small>
                                    <div class="invalid-feedback" id="foto-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga">Harga (Rp)</label>
                                    <input type="number" class="form-control" id="harga" name="harga" min="0"
                                        required>
                                    <div class="invalid-feedback" id="harga-error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="harga_diskon">Harga Diskon (Rp)</label>
                                    <input type="number" class="form-control" id="harga_diskon" name="harga_diskon"
                                        min="0">
                                    <small class="form-text text-muted">Kosongkan jika tidak ada diskon</small>
                                    <div class="invalid-feedback" id="harga_diskon-error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="stok">Stok</label>
                                    <input type="number" class="form-control" id="stok" name="stok" min="0"
                                        required>
                                    <div class="invalid-feedback" id="stok-error"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="berat">Berat</label>
                                            <input type="number" class="form-control" id="berat" name="berat"
                                                min="0" step="0.01" required>
                                            <div class="invalid-feedback" id="berat-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="satuan">Satuan</label>
                                            <input type="text" class="form-control" id="satuan" name="satuan"
                                                required>
                                            <small class="form-text text-muted">Contoh: gram, kg, pcs</small>
                                            <div class="invalid-feedback" id="satuan-error"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="aktif"
                                            name="aktif" checked>
                                        <label class="custom-control-label" for="aktif">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi Produk</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"></textarea>
                            <div class="invalid-feedback" id="deskripsi-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProdukModal" tabindex="-1" role="dialog" aria-labelledby="editProdukModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProdukModalLabel">Edit Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editProdukForm" enctype="multipart/form-data">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_nama">Nama Produk</label>
                                    <input type="text" class="form-control" id="edit_nama" name="nama" required>
                                    <div class="invalid-feedback" id="edit-nama-error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="edit_kode">Kode Produk</label>
                                    <input type="text" class="form-control" id="edit_kode" name="kode" required>
                                    <div class="invalid-feedback" id="edit-kode-error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="edit_kategori_id">Kategori</label>
                                    <select class="form-control" id="edit_kategori_id" name="kategori_id">
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($kategoriList as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="edit-kategori_id-error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="edit_foto">Foto Produk</label>
                                    <div class="mb-2" id="current_foto_container">
                                        <img id="current_foto" src="" alt="Foto Produk" class="img-fluid mb-2"
                                            style="max-height: 100px; display: none;">
                                    </div>
                                    <input type="file" class="form-control-file" id="edit_foto" name="foto">
                                    <small class="form-text text-muted">Format: JPG, JPEG, PNG. Maks: 2MB. Kosongkan jika
                                        tidak ingin mengubah foto</small>
                                    <div class="invalid-feedback" id="edit-foto-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_harga">Harga (Rp)</label>
                                    <input type="number" class="form-control" id="edit_harga" name="harga"
                                        min="0" required>
                                    <div class="invalid-feedback" id="edit-harga-error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="edit_harga_diskon">Harga Diskon (Rp)</label>
                                    <input type="number" class="form-control" id="edit_harga_diskon"
                                        name="harga_diskon" min="0">
                                    <small class="form-text text-muted">Kosongkan jika tidak ada diskon</small>
                                    <div class="invalid-feedback" id="edit-harga_diskon-error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="edit_stok">Stok</label>
                                    <input type="number" class="form-control" id="edit_stok" name="stok"
                                        min="0" required>
                                    <div class="invalid-feedback" id="edit-stok-error"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="edit_berat">Berat</label>
                                            <input type="number" class="form-control" id="edit_berat" name="berat"
                                                min="0" step="0.01" required>
                                            <div class="invalid-feedback" id="edit-berat-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="edit_satuan">Satuan</label>
                                            <input type="text" class="form-control" id="edit_satuan" name="satuan"
                                                required>
                                            <small class="form-text text-muted">Contoh: gram, kg, pcs</small>
                                            <div class="invalid-feedback" id="edit-satuan-error"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="edit_aktif"
                                            name="aktif">
                                        <label class="custom-control-label" for="edit_aktif">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_deskripsi">Deskripsi Produk</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="4"></textarea>
                            <div class="invalid-feedback" id="edit-deskripsi-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewProdukModal" tabindex="-1" role="dialog" aria-labelledby="viewProdukModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewProdukModalLabel">Detail Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img id="view_foto" src="" alt="Foto Produk" class="img-fluid mb-2"
                                style="max-height: 200px;">
                            <div id="no_foto" class="badge badge-secondary p-2" style="display: none;">Tidak Ada Foto
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 id="view_nama"></h4>
                            <p><strong>Kode:</strong> <span id="view_kode"></span></p>
                            <p><strong>Kategori:</strong> <span id="view_kategori"></span></p>
                            <p>
                                <strong>Harga:</strong> <span id="view_harga"></span><br>
                                <span id="view_diskon_container" style="display: none;">
                                    <strong>Harga Diskon:</strong> <span id="view_harga_diskon"></span>
                                </span>
                            </p>
                            <p><strong>Stok:</strong> <span id="view_stok"></span></p>
                            <p><strong>Berat:</strong> <span id="view_berat"></span> <span id="view_satuan"></span></p>
                            <p><strong>Status:</strong> <span id="view_status"></span></p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Deskripsi Produk</h5>
                            <p id="view_deskripsi"></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tanggal Dibuat:</strong> <span id="view_created_at"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Terakhir Diperbarui:</strong> <span id="view_updated_at"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="deleteProdukModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteProdukModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProdukModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.</p>
                    <p class="text-danger">Perhatian: Foto produk juga akan dihapus dari penyimpanan.</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="delete_id">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#produk-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.data.produk.data') }}",
                columns: [{
                        data: 'foto',
                        name: 'foto',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori_id'
                    },
                    {
                        data: 'harga_format',
                        name: 'harga',
                        searchable: false
                    },
                    {
                        data: 'stok',
                        name: 'stok'
                    },
                    {
                        data: 'status',
                        name: 'aktif',
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [2, 'asc']
                ]
            });

            $('#addProdukModal, #editProdukModal').on('hidden.bs.modal', function() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $(this).find('form')[0].reset();
                if ($(this).attr('id') === 'editProdukModal') {
                    $('#current_foto').attr('src', '').hide();
                }
            });

            $('#addProdukForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.data.produk.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#addProdukModal').modal('hide');
                        table.ajax.reload();
                        showAlert('success', response.success);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key + '-error').text(value[0]);
                            });
                            showAlert('error',
                                'Terdapat kesalahan pada form. Silakan periksa kembali.');
                        } else {
                            showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            });

            $(document).on('click', '.view-btn', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: "{{ url('admin/data/produk') }}" + '/' + id,
                    type: "GET",
                    success: function(response) {
                        $('#view_nama').text(response.nama);
                        $('#view_kode').text(response.kode);
                        $('#view_kategori').text(response.kategori ? response.kategori.nama :
                            'Tidak Terkategori');
                        $('#view_harga').text('Rp ' + numberFormat(response.harga));

                        if (response.harga_diskon) {
                            $('#view_diskon_container').show();
                            $('#view_harga_diskon').text('Rp ' + numberFormat(response
                                .harga_diskon));
                        } else {
                            $('#view_diskon_container').hide();
                        }

                        $('#view_stok').text(response.stok);
                        $('#view_berat').text(response.berat);
                        $('#view_satuan').text(response.satuan);
                        $('#view_status').html(response.aktif ?
                            '<span class="badge badge-success">Aktif</span>' :
                            '<span class="badge badge-danger">Tidak Aktif</span>');
                        $('#view_deskripsi').text(response.deskripsi || '-');
                        $('#view_created_at').text(response.created_at);
                        $('#view_updated_at').text(response.updated_at);

                        if (response.foto) {
                            $('#view_foto').attr('src', "{{ asset('storage/foto-produk') }}" +
                                '/' + response.foto).show();
                            $('#no_foto').hide();
                        } else {
                            $('#view_foto').hide();
                            $('#no_foto').show();
                        }

                        $('#viewProdukModal').modal('show');
                    },
                    error: function() {
                        showAlert('error', 'Gagal memuat data produk');
                    }
                });
            });

            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: "{{ url('admin/data/produk') }}" + '/' + id,
                    type: "GET",
                    success: function(response) {
                        $('#edit_id').val(response.id);
                        $('#edit_nama').val(response.nama);
                        $('#edit_kode').val(response.kode);
                        $('#edit_kategori_id').val(response.kategori_id);
                        $('#edit_harga').val(response.harga);
                        $('#edit_harga_diskon').val(response.harga_diskon);
                        $('#edit_stok').val(response.stok);
                        $('#edit_berat').val(response.berat);
                        $('#edit_satuan').val(response.satuan);
                        $('#edit_deskripsi').val(response.deskripsi);
                        $('#edit_aktif').prop('checked', response.aktif);

                        if (response.foto) {
                            $('#current_foto').attr('src',
                                    "{{ asset('storage/foto-produk') }}" + '/' + response.foto)
                                .show();
                        } else {
                            $('#current_foto').hide();
                        }

                        $('#editProdukModal').modal('show');
                    },
                    error: function() {
                        showAlert('error', 'Gagal memuat data produk');
                    }
                });
            });

            $('#editProdukForm').on('submit', function(e) {
                e.preventDefault();
                var id = $('#edit_id').val();

                var formData = new FormData(this);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "{{ url('admin/data/produk') }}" + '/' + id,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#editProdukModal').modal('hide');
                        table.ajax.reload();
                        showAlert('success', response.success);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#edit_' + key).addClass('is-invalid');
                                $('#edit-' + key + '-error').text(value[0]);
                            });
                            showAlert('error',
                                'Terdapat kesalahan pada form. Silakan periksa kembali.');
                        } else {
                            showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            });

            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                $('#delete_id').val(id);
                $('#deleteProdukModal').modal('show');
            });

            $('#confirmDeleteBtn').on('click', function() {
                var id = $('#delete_id').val();

                $.ajax({
                    url: "{{ url('admin/data/produk') }}" + '/' + id,
                    type: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#deleteProdukModal').modal('hide');
                        table.ajax.reload();
                        showAlert('success', response.success);
                    },
                    error: function(xhr) {
                        showAlert('error', 'Gagal menghapus produk');
                    }
                });
            });

            function numberFormat(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }
        });
    </script>
@endsection
