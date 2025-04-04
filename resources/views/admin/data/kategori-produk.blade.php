@extends('layouts.main')
@section('title', 'Kelola Kategori Produk')
@section('page-title', 'Kelola Kategori Produk')
@section('page-subtitle', 'Master/Kelola-Kategori-Produk')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="card-title">Tabel Kategori Produk</h4>
                                <h6 class="card-subtitle">Tabel untuk mengelola data kategori produk</h6>
                            </div>
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#addKategoriModal">
                                <i class="fas fa-plus-circle"></i> Tambah Kategori
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="kategori-table" class="table table-striped table-bordered no-wrap">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Slug</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                        <th>Tanggal Dibuat</th>
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

    <div class="modal fade" id="addKategoriModal" tabindex="-1" role="dialog" aria-labelledby="addKategoriModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKategoriModalLabel">Tambah Kategori Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addKategoriForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama">Nama Kategori</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                            <div class="invalid-feedback" id="nama-error"></div>
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug (Opsional)</label>
                            <input type="text" class="form-control" id="slug" name="slug">
                            <small class="form-text text-muted">Jika dikosongkan, slug akan otomatis dibuat</small>
                            <div class="invalid-feedback" id="slug-error"></div>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                            <div class="invalid-feedback" id="deskripsi-error"></div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="aktif" name="aktif" checked>
                                <label class="custom-control-label" for="aktif">Aktif</label>
                            </div>
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

    <div class="modal fade" id="editKategoriModal" tabindex="-1" role="dialog" aria-labelledby="editKategoriModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKategoriModalLabel">Edit Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editKategoriForm">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_nama">Nama Kategori</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                            <div class="invalid-feedback" id="edit-nama-error"></div>
                        </div>
                        <div class="form-group">
                            <label for="edit_slug">Slug (Opsional)</label>
                            <input type="text" class="form-control" id="edit_slug" name="slug">
                            <small class="form-text text-muted">Jika dikosongkan, slug akan otomatis dibuat</small>
                            <div class="invalid-feedback" id="edit-slug-error"></div>
                        </div>
                        <div class="form-group">
                            <label for="edit_deskripsi">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
                            <div class="invalid-feedback" id="edit-deskripsi-error"></div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="edit_aktif" name="aktif">
                                <label class="custom-control-label" for="edit_aktif">Aktif</label>
                            </div>
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

    <div class="modal fade" id="viewKategoriModal" tabindex="-1" role="dialog"
        aria-labelledby="viewKategoriModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewKategoriModalLabel">Detail Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 id="view_nama"></h4>
                    <p><strong>Slug:</strong> <span id="view_slug"></span></p>
                    <p><strong>Status:</strong> <span id="view_status"></span></p>
                    <p><strong>Deskripsi:</strong></p>
                    <p id="view_deskripsi"></p>
                    <p><strong>Tanggal Dibuat:</strong> <span id="view_created_at"></span></p>
                    <p><strong>Terakhir Diperbarui:</strong> <span id="view_updated_at"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteKategoriModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteKategoriModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteKategoriModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.</p>
                    <p class="text-danger">Perhatian: Menghapus kategori juga akan mempengaruhi produk yang terkait.</p>
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
            var table = $('#kategori-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.data.kategori.data') }}",
                columns: [{
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'slug',
                        name: 'slug'
                    },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi',
                        render: function(data) {
                            return data ? data.substring(0, 50) + (data.length > 50 ? '...' : '') :
                                '-';
                        }
                    },
                    {
                        data: 'status',
                        name: 'aktif',
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [0, 'asc']
                ]
            });

            $('#addKategoriModal, #editKategoriModal').on('hidden.bs.modal', function() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $(this).find('form')[0].reset();
            });

            $('#nama').on('blur', function() {
                if ($('#slug').val() === '') {
                    var nama = $(this).val();
                    var slug = nama.toLowerCase()
                        .replace(/[^\w ]+/g, '')
                        .replace(/ +/g, '-');
                    $('#slug').val(slug);
                }
            });

            $('#edit_nama').on('blur', function() {
                if ($('#edit_slug').val() === '') {
                    var nama = $(this).val();
                    var slug = nama.toLowerCase()
                        .replace(/[^\w ]+/g, '')
                        .replace(/ +/g, '-');
                    $('#edit_slug').val(slug);
                }
            });

            $('#addKategoriForm').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('admin.data.kategori.store') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#addKategoriModal').modal('hide');
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
                    url: "{{ url('admin/data/kategori') }}" + '/' + id,
                    type: "GET",
                    success: function(response) {
                        $('#view_nama').text(response.nama);
                        $('#view_slug').text(response.slug);
                        $('#view_status').html(response.aktif ?
                            '<span class="badge badge-success">Aktif</span>' :
                            '<span class="badge badge-danger">Tidak Aktif</span>');
                        $('#view_deskripsi').text(response.deskripsi || '-');
                        $('#view_created_at').text(response.created_at);
                        $('#view_updated_at').text(response.updated_at);

                        $('#viewKategoriModal').modal('show');
                    },
                    error: function() {
                        showAlert('error', 'Gagal memuat data kategori');
                    }
                });
            });

            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: "{{ url('admin/data/kategori') }}" + '/' + id,
                    type: "GET",
                    success: function(response) {
                        $('#edit_id').val(response.id);
                        $('#edit_nama').val(response.nama);
                        $('#edit_slug').val(response.slug);
                        $('#edit_deskripsi').val(response.deskripsi);
                        $('#edit_aktif').prop('checked', response.aktif);

                        $('#editKategoriModal').modal('show');
                    },
                    error: function() {
                        showAlert('error', 'Gagal memuat data kategori');
                    }
                });
            });

            $('#editKategoriForm').on('submit', function(e) {
                e.preventDefault();
                var id = $('#edit_id').val();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ url('admin/data/kategori') }}" + '/' + id,
                    type: "PUT",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#editKategoriModal').modal('hide');
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
                $('#deleteKategoriModal').modal('show');
            });

            $('#confirmDeleteBtn').on('click', function() {
                var id = $('#delete_id').val();

                $.ajax({
                    url: "{{ url('admin/data/kategori') }}" + '/' + id,
                    type: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#deleteKategoriModal').modal('hide');
                        table.ajax.reload();
                        showAlert('success', response.success);
                    },
                    error: function(xhr) {
                        showAlert('error', 'Gagal menghapus kategori');
                    }
                });
            });
        });
    </script>
@endsection
