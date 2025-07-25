@extends('layouts.main')
@section('title', 'Stok Keluar')
@section('page-title', 'Stok Keluar')
@section('page-subtitle', 'Master/Stok-Keluar')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="card-title">Data Stok Keluar</h4>
                                <h6 class="card-subtitle">Riwayat pengurangan stok produk</h6>
                            </div>
                            <button type="button" class="btn btn-primary" id="kurangi-stok-btn">
                                <i class="fas fa-minus-circle"></i> Kurangi Stok
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="stok-keluar-table" class="table table-striped table-bordered no-wrap">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
                                        <th>Stok Sebelum</th>
                                        <th>Stok Setelah</th>
                                        <th>Keterangan</th>
                                        <th>User</th>
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

    <!-- Modal Kurangi Stok -->
    <div class="modal fade" id="kurangiStokModal" tabindex="-1" role="dialog" aria-labelledby="kurangiStokModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kurangiStokModalLabel">Kurangi Stok</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="kurangiStokForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="produk_id">Produk</label>
                            <select class="form-control select2" id="produk_id" name="produk_id" required>
                                <option value="">-- Pilih Produk --</option>
                            </select>
                            <div class="invalid-feedback" id="produk-id-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="jumlah">Jumlah Stok Keluar</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1"
                                required>
                            <div class="invalid-feedback" id="jumlah-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                                placeholder="Contoh: Penjualan, retur, kerusakan, dll"></textarea>
                            <div class="invalid-feedback" id="keterangan-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Kurangi Stok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Stok Keluar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Produk</label>
                        <h5 id="detail_nama_produk" class="font-weight-bold"></h5>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Keluar</label>
                                <p id="detail_tanggal"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>User</label>
                                <p id="detail_user"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jumlah</label>
                                <p id="detail_jumlah" class="font-weight-bold text-danger"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Stok Sebelum</label>
                                <p id="detail_stok_sebelum"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Stok Setelah</label>
                                <p id="detail_stok_setelah"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <p id="detail_keterangan"></p>
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
            // Initialize DataTable
            var table = $('#stok-keluar-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.stok-keluar.data') }}",
                columns: [{
                        data: 'tanggal',
                        name: 'created_at'
                    },
                    {
                        data: 'nama_produk',
                        name: 'produk.nama'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'stok_sebelum',
                        name: 'stok_sebelum'
                    },
                    {
                        data: 'stok_setelah',
                        name: 'stok_setelah'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        render: function(data) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: 'user_name',
                        name: 'user.name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'desc']
                ]
            });

            document.getElementById("jumlah").addEventListener("keypress", function(e) {
                const char = String.fromCharCode(e.which);
                if (!/[0-9]/.test(char)) {
                    e.preventDefault();
                    alert("Inputan harus berupa angka...");
                }
            });

            // Load produk for select2
            $('#kurangi-stok-btn').click(function() {
                loadProduk();
                $('#kurangiStokModal').modal('show');
            });

            function loadProduk() {
                $.ajax({
                    url: "{{ route('admin.produk.list') }}",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        $('#produk_id').empty().append('<option value="">-- Pilih Produk --</option>');
                        $.each(response, function(key, item) {
                            $('#produk_id').append('<option value="' + item.id +
                                '" data-stok="' + item.stok + '">' +
                                item.kode + ' - ' + item.nama + ' (Stok: ' + item.stok +
                                ')</option>');
                        });
                    },
                    error: function() {
                        showAlert('error', 'Gagal memuat data produk');
                    }
                });
            }

            // Initialize select2
            if ($.fn.select2) {
                $('.select2').select2({
                    width: '100%',
                    dropdownParent: $('#kurangiStokModal')
                });
            }

            // Reset form ketika modal ditutup
            $('#kurangiStokModal').on('hidden.bs.modal', function() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $(this).find('form')[0].reset();
                if ($.fn.select2) {
                    $('#produk_id').val('').trigger('change');
                }
            });

            // Validasi jumlah stok
            $('#produk_id').on('change', function() {
                var option = $(this).find('option:selected');
                var stok = option.data('stok');
                if (stok) {
                    $('#jumlah').attr('max', stok);
                }
            });

            // Submit form kurangi stok
            $('#kurangiStokForm').on('submit', function(e) {
                e.preventDefault();

                var produkId = $('#produk_id').val();
                var jumlah = parseInt($('#jumlah').val());

                if (produkId) {
                    var stok = $('#produk_id option:selected').data('stok');
                    if (jumlah > stok) {
                        $('#jumlah').addClass('is-invalid');
                        $('#jumlah-error').text('Jumlah melebihi stok yang tersedia (' + stok + ')');
                        return false;
                    }
                }

                $.ajax({
                    url: "{{ route('admin.stok-keluar.kurangi') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#kurangiStokModal').modal('hide');
                        table.ajax.reload();
                        showAlert('success', 'Stok berhasil dikurangi');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key + '-error').text(value[0]);
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.error) {
                            showAlert('error', xhr.responseJSON.error);
                        } else {
                            showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            });

            $(document).on('click', '.detail-btn', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ url('admin/data/stok-keluar/detail') }}" + '/' + id,
                    type: "GET",
                    success: function(response) {
                        $('#detail_nama_produk').text(response.produk.kode + ' - ' + response
                            .produk.nama);
                        $('#detail_tanggal').text(formatDate(response.history.created_at));
                        $('#detail_user').text(response.user ? response.user.name : 'Sistem');
                        $('#detail_jumlah').text('-' + response.history.jumlah);
                        $('#detail_stok_sebelum').text(response.history.stok_sebelum);
                        $('#detail_stok_setelah').text(response.history.stok_setelah);
                        $('#detail_keterangan').text(response.history.keterangan || '-');
                        $('#detailModal').modal('show');
                    },
                    error: function() {
                        showAlert('error', 'Gagal memuat detail stok keluar');
                    }
                });
            });

            function formatDate(dateString) {
                var date = new Date(dateString);
                return date.toLocaleString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            }
        });
    </script>
@endsection
