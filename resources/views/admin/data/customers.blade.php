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
                <form id="addCustomerForm">
                    <div class="modal-body">
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
                                    <label for="phone">No. Telepon</label>
                                    <input type="text" class="form-control" id="phone" name="phone" required>
                                    <div class="invalid-feedback" id="phone-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            <div class="invalid-feedback" id="address-error"></div>
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
                <form id="editCustomerForm">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
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
                                    <label for="edit_phone">No. Telepon</label>
                                    <input type="text" class="form-control" id="edit_phone" name="phone" required>
                                    <div class="invalid-feedback" id="edit-phone-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_address">Alamat</label>
                            <textarea class="form-control" id="edit_address" name="address" rows="3" required></textarea>
                            <div class="invalid-feedback" id="edit-address-error"></div>
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
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama:</strong> <span id="view_name"></span></p>
                            <p><strong>Username:</strong> <span id="view_username"></span></p>
                            <p><strong>Email:</strong> <span id="view_email"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Nomor Telepon:</strong> <span id="view_phone"></span></p>
                            <p><strong>Alamat:</strong> <span id="view_address"></span></p>
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
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'address',
                        name: 'address'
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

            // Clear validation errors when modal is hidden
            $('#addCustomerModal, #editCustomerModal').on('hidden.bs.modal', function() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $(this).find('form')[0].reset();
            });

            // Add Customer Form Submit
            $('#addCustomerForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('customers.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#addCustomerModal').modal('hide');
                        table.ajax.reload();
                        toastr.success(response.success);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key + '-error').text(value[0]);
                            });
                        } else {
                            toastr.error('Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            });

            // Handle View Button Click
            $(document).on('click', '.view-btn', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: "/admin/customers/" + id,
                    type: "GET",
                    success: function(response) {
                        $('#view_name').text(response.name);
                        $('#view_username').text(response.username);
                        $('#view_email').text(response.email);
                        $('#view_phone').text(response.phone);
                        $('#view_address').text(response.address);
                        $('#view_latitude').text(response.latitude || 'Tidak diatur');
                        $('#view_longitude').text(response.longitude || 'Tidak diatur');

                        $('#viewCustomerModal').modal('show');
                    },
                    error: function() {
                        toastr.error('Gagal memuat data pelanggan');
                    }
                });
            });

            // Handle Edit Button Click
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
                        $('#edit_phone').val(response.phone);
                        $('#edit_address').val(response.address);
                        $('#edit_latitude').val(response.latitude);
                        $('#edit_longitude').val(response.longitude);

                        $('#editCustomerModal').modal('show');
                    },
                    error: function() {
                        toastr.error('Gagal memuat data pelanggan');
                    }
                });
            });

            // Edit Customer Form Submit
            $('#editCustomerForm').on('submit', function(e) {
                e.preventDefault();
                var id = $('#edit_id').val();

                $.ajax({
                    url: "/admin/customers/" + id,
                    type: "PUT",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#editCustomerModal').modal('hide');
                        table.ajax.reload();
                        toastr.success(response.success);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#edit_' + key).addClass('is-invalid');
                                $('#edit-' + key + '-error').text(value[0]);
                            });
                        } else {
                            toastr.error('Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            });

            // Handle Delete Button Click
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                $('#delete_id').val(id);
                $('#deleteCustomerModal').modal('show');
            });

            // Confirm Delete Button Click
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
                        toastr.success(response.success);
                    },
                    error: function(xhr) {
                        toastr.error('Gagal menghapus pelanggan');
                    }
                });
            });
        });
    </script>
@endsection
