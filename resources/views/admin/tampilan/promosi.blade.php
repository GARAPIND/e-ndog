@extends('layouts.main')
@section('title', 'Kelola Promosi')
@section('page-title', 'Kelola Promosi')
@section('page-subtitle', 'Tampilan/Kelola Banner Promosi')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="card-title">Tabel Banner Promosi</h4>
                                <h6 class="card-subtitle">Tabel untuk mengelola data banner promosi</h6>
                            </div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal"
                                onclick="submit('tambah')">
                                <i class="fas fa-plus-circle"></i> Tambah Data
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="promosi-table" class="table table-striped table-bordered no-wrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Foto</th>
                                        <th>Judul</th>
                                        <th>Sub Judul</th>
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

    <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Form Promosi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form id="formPromosi" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="judul">Judul</label>
                            <input type="hidden" name="id" id="id">
                            <input type="text" class="form-control" placeholder="Masukkan Judul" id="judul"
                                name="judul">
                            <small class="text-danger pl-1" id="error-judul"></small>
                        </div>
                        <div class="form-group">
                            <label for="sub_judul">Sub Judul</label>
                            <input type="text" class="form-control" placeholder="Masukkan sub judul" id="sub_judul"
                                name="sub_judul">
                            <small class="text-danger pl-1" id="error-sub_judul"></small>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Foto <small class="text-muted">(Disarankan 16:9/landscape)</small>
                            </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Upload</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto" name="foto">
                                    <label class="custom-file-label" for="foto">Pilih foto</label>
                                </div>
                            </div>
                            <small class="text-danger pl-1" id="error-foto"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_tambah" onclick="tambah_data()">Simpan
                        Data</button>
                    <button type="button" class="btn btn-primary" id="btn_edit" onclick="edit_data()">Perbarui
                        Data</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data banner promosi ini? Tindakan ini tidak dapat dibatalkan.</p>
                    <p class="text-danger">Perhatian: Foto data banner promosi juga akan dihapus dari penyimpanan.</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="delete_id">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn"
                        onclick="hapus_data()">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const baseUrl = "{{ url('') }}";

        $(document).ready(function() {
            get_data();
            $("#foto").change(function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("#preview-foto").html('<img src="' + e.target.result +
                            '"class="mt-2 img-thumbnail" style="max-height:200px;">');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });

        function delete_error() {
            $("[id^=error-]").hide();
        }

        function delete_form() {
            $("#judul").val("");
            $("#sub_judul").val("");
            $("#foto").val("");
            $("label[for='foto']").text("Pilih foto");
        }

        function get_data() {
            delete_error();
            delete_form();
            let table = $("#promosi-table").DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('promosi.get_data') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'foto',
                        className: 'text-center',
                        name: 'foto',
                        orderable: false
                    },
                    {
                        data: 'judul',
                        className: 'text-center',
                        name: 'judul',
                        render: (data) => {
                            return `<b>${data}</b>`;
                        }
                    },
                    {
                        data: 'sub_judul',
                        className: 'text-center',
                        name: 'sub_judul',
                        render: (data) => {
                            return data || '-';
                        }
                    },
                    {
                        data: 'aksi',
                        className: 'text-center',
                        name: 'aksi',
                        orderable: false
                    },
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).addClass('small-padding');
                }
            });
        }

        function submit(id) {
            if (id == "tambah") {
                $("#btn_tambah").show();
                $("#btn_edit").hide();
                $("#modalLabel").text("Tambah data promosi");
            } else {
                $("#btn_tambah").hide();
                $("#btn_edit").show();
                $("#modalLabel").text("Edit data promosi");
                $.ajax({
                    type: "POST",
                    url: "{{ route('promosi.get_data_id') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id,
                    },
                    dataType: "json",
                    success: function(hasil) {
                        $("#id").val(hasil.id);
                        $("#judul").val(hasil.judul);
                        $("#sub_judul").val(hasil.sub_judul);
                    },
                });
            }
            delete_form();
            delete_error();
        }

        function tambah_data() {
            var formData = new FormData(document.getElementById('formPromosi'));

            $.ajax({
                type: "POST",
                url: "{{ route('promosi.tambah_data') }}",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#btn_tambah").prop("disabled", true).html(
                        "<div class='spinner-border spinner-border-sm text-dark' role='status'></div>");
                },
                success: function(response) {
                    delete_error();
                    if (response.errors) {
                        Object.keys(response.errors).forEach(function(fieldName) {
                            $("#error-" + fieldName).show();
                            $("#error-" + fieldName).html(
                                response.errors[fieldName][0]
                            );
                        });
                    } else if (response.success) {
                        $("#modal").modal("hide");
                        showAlert('success', response.success);
                        get_data();
                    } else if (response.error) {
                        $("#modal").modal("hide");
                        showAlert('error', response.error);
                        get_data();
                    }
                    $("#btn_tambah").prop("disabled", false).text("Simpan");
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + error);
                },
            });
        }

        function edit_data() {
            var formData = new FormData(document.getElementById('formPromosi'));

            $.ajax({
                type: "POST",
                url: "{{ route('promosi.edit_data') }}",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#btn_edit").prop("disabled", true).html(
                        "<div class='spinner-border spinner-border-sm text-dark' role='status'></div>");
                },
                success: function(response) {
                    delete_error();
                    if (response.errors) {
                        Object.keys(response.errors).forEach(function(fieldName) {
                            $("#error-" + fieldName).show();
                            $("#error-" + fieldName).html(
                                response.errors[fieldName][0]
                            );
                        });
                    } else if (response.success) {
                        $("#modal").modal("hide");
                        showAlert('success', response.success);
                        get_data();
                    } else if (response.error) {
                        $("#modal").modal("hide");
                        showAlert('error', response.error);
                        get_data();
                    }
                    $("#btn_edit").prop("disabled", false).text("Simpan");
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + error);
                },
            });
        }

        function btn_delete(id) {
            $('#delete_id').val(id);
        }

        function hapus_data() {
            var id = $('#delete_id').val();
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                url: "{{ route('promosi.hapus_data') }}",
                success: function(response) {
                    if (response.success) {
                        $('#deleteModal').modal("hide");
                        showAlert('success', response.success);
                        get_data();
                    } else if (response.error) {
                        $('#deleteModal').modal("hide");
                        showAlert('error', response.error);
                        get_data();
                    }
                },
            });
        }
    </script>
@endsection
