@extends('layouts.main')
@section('title', 'Kelola Pelanggan')
@section('page-title', 'Kelola Pelanggan')
@section('page-subtitle', 'Master/Kelola-Pelanggan')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="card-title">Tabel Pelanggan</h4>
                                <h6 class="card-subtitle">Tabel untuk mengelola data pelanggan</h6>
                            </div>
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#addCustomerModal">
                                <i class="fas fa-plus-circle"></i> Tambah Pelanggan
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="customers-table" class="table table-striped table-bordered no-wrap">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>No. Telepon</th>
                                        <th>Alamat</th>
                                        <th>Tanggal Daftar</th>
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
    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Tambah Pelanggan Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addCustomerForm" enctype="multipart/form-data">
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
                                    <label for="latitude">Latitude</label>
                                    <input type="text" class="form-control" id="latitude" name="latitude">
                                    <div class="invalid-feedback" id="latitude-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude">Longitude</label>
                                    <input type="text" class="form-control" id="longitude" name="longitude">
                                    <div class="invalid-feedback" id="longitude-error"></div>
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
    <div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog"
        aria-labelledby="editCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCustomerModalLabel">Edit Pelanggan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editCustomerForm" enctype="multipart/form-data">
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
                                    <label for="edit_latitude">Latitude</label>
                                    <input type="text" class="form-control" id="edit_latitude" name="latitude">
                                    <div class="invalid-feedback" id="edit-latitude-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_longitude">Longitude</label>
                                    <input type="text" class="form-control" id="edit_longitude" name="longitude">
                                    <div class="invalid-feedback" id="edit-longitude-error"></div>
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
    <div class="modal fade" id="viewCustomerModal" tabindex="-1" role="dialog"
        aria-labelledby="viewCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCustomerModalLabel">Detail Pelanggan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img id="view_photo" class="img-fluid rounded" style="max-height: 200px;" alt="Foto Pelanggan">
                        <div id="no_photo_text" class="alert alert-secondary mt-2" style="display: none;">Tidak ada foto
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama:</strong> <span id="view_name"></span></p>
                            <p><strong>Username:</strong> <span id="view_username"></span></p>
                            <p><strong>Email:</strong> <span id="view_email"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Nomor Telepon:</strong> <span id="view_telp"></span></p>
                            <p><strong>Alamat:</strong> <span id="view_alamat"></span></p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6>Lokasi</h6>
                            <p><strong>Latitude:</strong> <span id="view_latitude"></span></p>
                            <p><strong>Longitude:</strong> <span id="view_longitude"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteCustomerModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCustomerModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus pelanggan ini? Tindakan ini tidak dapat dibatalkan.
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
            var table = $('#customers-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('customers.data') }}",
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
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'telp',
                        name: 'telp'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
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
                    [1, 'asc']
                ]
            });

            $('#addCustomerModal, #editCustomerModal').on('hidden.bs.modal', function() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $(this).find('form')[0].reset();
                $('#current_photo_container').empty();
            });

            $('#addCustomerForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('customers.store') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#addCustomerModal').modal('hide');
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
                    url: "/admin/customers/" + id,
                    type: "GET",
                    success: function(response) {
                        $('#view_name').text(response.name);
                        $('#view_username').text(response.username);
                        $('#view_email').text(response.email);
                        $('#view_telp').text(response.telp);
                        $('#view_alamat').text(response.alamat);
                        $('#view_latitude').text(response.latitude || 'Tidak diatur');
                        $('#view_longitude').text(response.longitude || 'Tidak diatur');
                        if (response.photo) {
                            $('#view_photo').attr('src', "{{ asset('storage/foto-user') }}/" +
                                response.photo).show();
                            $('#no_photo_text').hide();
                        } else {
                            $('#view_photo').hide();
                            $('#no_photo_text').show();
                        }

                        $('#viewCustomerModal').modal('show');
                    },
                    error: function() {
                        showAlert('error', 'Gagal memuat data pelanggan');
                    }
                });
            });

            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: "/admin/customers/" + id,
                    type: "GET",
                    success: function(response) {
                        $('#edit_id').val(response.id);
                        $('#edit_name').val(response.name);
                        $('#edit_username').val(response.username);
                        $('#edit_email').val(response.email);
                        $('#edit_telp').val(response.telp);
                        $('#edit_alamat').val(response.alamat);
                        $('#edit_latitude').val(response.latitude);
                        $('#edit_longitude').val(response.longitude);

                        $('#current_photo_container').empty();
                        if (response.photo) {
                            var photoUrl = "{{ asset('storage/foto-user') }}/" + response.photo;
                            $('#current_photo_container').html(
                                '<img src="' + photoUrl +
                                '" class="img-thumbnail mb-2" style="max-height: 150px"><br>' +
                                '<small class="text-muted">Foto saat ini</small>'
                            );
                        } else {
                            $('#current_photo_container').html(
                                '<p class="text-muted">Tidak ada foto</p>');
                        }

                        $('#editCustomerModal').modal('show');
                    },
                    error: function() {
                        showAlert('error', 'Gagal memuat data pelanggan');
                    }
                });
            });

            $('#editCustomerForm').on('submit', function(e) {
                e.preventDefault();
                var id = $('#edit_id').val();

                var formData = new FormData(this);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "/admin/customers/" + id,
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#editCustomerModal').modal('hide');
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
                $('#deleteCustomerModal').modal('show');
            });

            $('#confirmDeleteBtn').on('click', function() {
                var id = $('#delete_id').val();

                $.ajax({
                    url: "/admin/customers/" + id,
                    type: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#deleteCustomerModal').modal('hide');
                        table.ajax.reload();
                        showAlert('success', response.success);
                    },
                    error: function(xhr) {
                        showAlert('error', 'Gagal menghapus pelanggan');
                    }
                });
            });
        });
    </script>
@endsection
