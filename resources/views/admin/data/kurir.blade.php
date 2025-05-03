@extends('layouts.main')
@section('title', 'Kelola Kurir')
@section('page-title', 'Kelola Kurir')
@section('page-subtitle', 'Master/Kelola-Kurir')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title">Tabel Kurir</h4>
                        <h6 class="card-subtitle">Tabel untuk mengelola data kurir</h6>
                    </div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addKurirModal">
                        <i class="fas fa-plus-circle"></i> Tambah Kurir
                    </button>
                </div>
                <div class="table-responsive">
                    <table id="kurir-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>No. Telepon</th>
                                <th>Kendaraan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Kurir Modal -->
    <div class="modal fade" id="addKurirModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kurir Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addKurirForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="photo">Foto</label>
                                    <input type="file" class="form-control" id="photo" name="photo"
                                        accept="image/*">
                                    <small class="form-text text-muted">Upload foto dengan format JPG, JPEG, atau PNG (max
                                        2MB)</small>
                                    <div class="invalid-feedback" id="photo-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback" id="name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                    <div class="invalid-feedback" id="username-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback" id="email-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telp">No. Telepon</label>
                                    <input type="text" class="form-control" id="telp" name="telp" required>
                                    <div class="invalid-feedback" id="telp-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                            <div class="invalid-feedback" id="alamat-error"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="plat_nomor">Plat Nomor</label>
                                    <input type="text" class="form-control" id="plat_nomor" name="plat_nomor" required>
                                    <div class="invalid-feedback" id="plat_nomor-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis_kendaraan">Jenis Kendaraan</label>
                                    <input type="text" class="form-control" id="jenis_kendaraan"
                                        name="jenis_kendaraan" required>
                                    <div class="invalid-feedback" id="jenis_kendaraan-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="invalid-feedback" id="password-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Non-Aktif</option>
                                    </select>
                                    <div class="invalid-feedback" id="status-error"></div>
                                </div>
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

    <!-- Edit Kurir Modal -->
    <div class="modal fade" id="editKurirModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kurir</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editKurirForm" enctype="multipart/form-data">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="edit_photo">Foto</label>
                                    <div id="current_photo_container" class="mb-2"></div>
                                    <input type="file" class="form-control" id="edit_photo" name="photo"
                                        accept="image/*">
                                    <small class="form-text text-muted">Upload foto baru dengan format JPG, JPEG, atau PNG
                                        (max 2MB)</small>
                                    <div class="invalid-feedback" id="edit-photo-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_name">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="edit_name" name="name" required>
                                    <div class="invalid-feedback" id="edit-name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_username">Username</label>
                                    <input type="text" class="form-control" id="edit_username" name="username"
                                        required>
                                    <div class="invalid-feedback" id="edit-username-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_email">Email</label>
                                    <input type="email" class="form-control" id="edit_email" name="email" required>
                                    <div class="invalid-feedback" id="edit-email-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_telp">No. Telepon</label>
                                    <input type="text" class="form-control" id="edit_telp" name="telp" required>
                                    <div class="invalid-feedback" id="edit-telp-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_alamat">Alamat</label>
                            <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                            <div class="invalid-feedback" id="edit-alamat-error"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_plat_nomor">Plat Nomor</label>
                                    <input type="text" class="form-control" id="edit_plat_nomor" name="plat_nomor"
                                        required>
                                    <div class="invalid-feedback" id="edit-plat_nomor-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_jenis_kendaraan">Jenis Kendaraan</label>
                                    <input type="text" class="form-control" id="edit_jenis_kendaraan"
                                        name="jenis_kendaraan" required>
                                    <div class="invalid-feedback" id="edit-jenis_kendaraan-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_password">Password (Kosongkan jika tidak ingin mengubah)</label>
                                    <input type="password" class="form-control" id="edit_password" name="password">
                                    <div class="invalid-feedback" id="edit-password-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_status">Status</label>
                                    <select class="form-control" id="edit_status" name="status" required>
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Non-Aktif</option>
                                    </select>
                                    <div class="invalid-feedback" id="edit-status-error"></div>
                                </div>
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

    <!-- View Kurir Modal -->
    <div class="modal fade" id="viewKurirModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Kurir</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img id="view_photo" class="img-fluid rounded" style="max-height: 200px;" alt="Foto Kurir">
                        <div id="no_photo_text" class="alert alert-secondary mt-2" style="display: none;">Tidak ada foto
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama:</strong> <span id="view_name"></span></p>
                            <p><strong>Username:</strong> <span id="view_username"></span></p>
                            <p><strong>Email:</strong> <span id="view_email"></span></p>
                            <p><strong>Nomor Telepon:</strong> <span id="view_telp"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Alamat:</strong> <span id="view_alamat"></span></p>
                            <p><strong>Plat Nomor:</strong> <span id="view_plat_nomor"></span></p>
                            <p><strong>Jenis Kendaraan:</strong> <span id="view_jenis_kendaraan"></span></p>
                            <p><strong>Status:</strong> <span id="view_status" class="badge"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Kurir Modal -->
    <div class="modal fade" id="deleteKurirModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus kurir ini? Tindakan ini tidak dapat dibatalkan.
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
            // Initialize DataTable
            var table = $('#kurir-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.kurir.data') }}",
                columns: [{
                        data: 'photo',
                        name: 'photo',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'telp',
                        name: 'telp'
                    },
                    {
                        data: 'jenis_kendaraan',
                        name: 'jenis_kendaraan'
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [1, 'asc']
                ]
            });

            // Reset form on modal close
            $('.modal').on('hidden.bs.modal', function() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $(this).find('form')[0].reset();
                $('#current_photo_container').empty();
            });

            // Add Kurir Form Submit
            $('#addKurirForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.kurir.store') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#addKurirModal').modal('hide');
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
            // Continue from the existing DataTable initialization
            // Edit Kurir Button Click
            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                $('#edit_id').val(id);

                // Reset form
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#current_photo_container').empty();

                $.ajax({
                    url: `/admin/data/kurir/${id}`,
                    type: 'GET',
                    success: function(response) {
                        $('#edit_name').val(response.name);
                        $('#edit_username').val(response.username);
                        $('#edit_email').val(response.email);
                        $('#edit_telp').val(response.telp);
                        $('#edit_alamat').val(response.alamat);
                        $('#edit_plat_nomor').val(response.plat_nomor);
                        $('#edit_jenis_kendaraan').val(response.jenis_kendaraan);
                        $('#edit_status').val(response.status);

                        // Display current photo if exists
                        if (response.photo) {
                            var photoUrl = `/storage/foto-kurir/${response.photo}`;
                            $('#current_photo_container').html(`
                    <img src="${photoUrl}" alt="Current Photo" class="img-thumbnail mb-2" style="max-height: 100px;">
                    <p class="small text-muted">Foto saat ini</p>
                `);
                        }

                        $('#editKurirModal').modal('show');
                    },
                    error: function(xhr) {
                        showAlert('error', 'Gagal memuat data kurir');
                    }
                });
            });

            // Edit Kurir Form Submit
            $('#editKurirForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var id = $('#edit_id').val();

                $.ajax({
                    url: `/admin/data/kurir/${id}`,
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#editKurirModal').modal('hide');
                        table.ajax.reload();
                        showAlert('success', response.success);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $(`#edit_${key}`).addClass('is-invalid');
                                $(`#edit-${key}-error`).text(value[0]);
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
                    url: `/admin/data/kurir/${id}`,
                    type: 'GET',
                    success: function(response) {
                        $('#view_name').text(response.name);
                        $('#view_username').text(response.username);
                        $('#view_email').text(response.email);
                        $('#view_telp').text(response.telp);
                        $('#view_alamat').text(response.alamat);
                        $('#view_plat_nomor').text(response.plat_nomor);
                        $('#view_jenis_kendaraan').text(response.jenis_kendaraan);
                        var statusClass = response.status === 'active' ? 'badge-success' :
                            'badge-danger';
                        $('#view_status').removeClass('badge-success badge-danger')
                            .addClass(statusClass)
                            .text(response.status === 'active' ? 'Aktif' : 'Non-Aktif');

                        if (response.photo) {
                            var photoUrl = `/storage/foto-kurir/${response.photo}`;
                            $('#view_photo').attr('src', photoUrl).show();
                            $('#no_photo_text').hide();
                        } else {
                            $('#view_photo').hide();
                            $('#no_photo_text').show();
                        }

                        $('#viewKurirModal').modal('show');
                    },
                    error: function(xhr) {
                        showAlert('error', 'Gagal memuat data kurir');
                    }
                });
            });

            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                $('#delete_id').val(id);
                $('#deleteKurirModal').modal('show');
            });
            $('#confirmDeleteBtn').on('click', function() {
                var id = $('#delete_id').val();

                $.ajax({
                    url: `/admin/data/kurir/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#deleteKurirModal').modal('hide');
                        table.ajax.reload();
                        showAlert('success', response.success);
                    },
                    error: function(xhr) {
                        $('#deleteKurirModal').modal('hide');
                        showAlert('error', 'Gagal menghapus kurir');
                    }
                });
            });
        });
    </script>
@endsection
