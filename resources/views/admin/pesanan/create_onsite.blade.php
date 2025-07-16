@extends('layouts.main')

@section('title', 'Tambah Pesanan Onsite')
@section('page-title', 'Tambah Pesanan Onsite')
@section('page-subtitle', 'Pesanan/tambah_onsite')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-plus-circle mr-2"></i>Tambah Pesanan Onsite
                        </h4>
                    </div>
                    <div class="card-body">
                        <form id="onsiteForm">
                            @csrf

                            <!-- Pilihan Tipe Pelanggan -->
                            <div class="form-group">
                                <label>Tipe Pelanggan</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipe_pelanggan" id="pelanggan_baru"
                                        value="baru" checked>
                                    <label class="form-check-label" for="pelanggan_baru">
                                        Pelanggan Baru
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipe_pelanggan"
                                        id="pelanggan_existing" value="existing">
                                    <label class="form-check-label" for="pelanggan_existing">
                                        Pelanggan Sudah Terdaftar
                                    </label>
                                </div>
                            </div>

                            <!-- Form untuk Pelanggan Existing -->
                            <div id="existing-customer-section" style="display: none;">
                                <div class="form-group">
                                    <label for="user_id">Pilih Pelanggan</label>
                                    <select class="form-control select2" id="user_id" name="user_id">
                                        <option value="">-- Pilih Pelanggan --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" data-name="{{ $user->name }}"
                                                data-phone="{{ $user->phone }}" data-email="{{ $user->email }}">
                                                {{ $user->name }} - {{ $user->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Form untuk Pelanggan Baru -->
                            <div id="new-customer-section">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_pelanggan">Nama Pelanggan</label>
                                            <input type="text" class="form-control" id="nama_pelanggan"
                                                name="nama_pelanggan">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="no_telepon">No. Telepon</label>
                                            <input type="text" class="form-control" id="no_telepon" name="no_telepon">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="alamat">Alamat</label>
                                            <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Pelanggan yang Dipilih -->
                            <div id="customer-info" style="display: none;">
                                <div class="alert alert-info">
                                    <h6>Informasi Pelanggan:</h6>
                                    <div id="customer-details"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="catatan_penjual">Catatan Penjual</label>
                                <textarea class="form-control" id="catatan_penjual" name="catatan_penjual" rows="2"></textarea>
                            </div>

                            <hr>

                            <h5>Daftar Produk</h5>
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <select class="form-control select2" id="produk-select">
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach ($produk as $item)
                                            <option value="{{ $item->id }}" data-nama="{{ $item->nama }}"
                                                data-harga="{{ $item->harga_aktif }}" data-stok="{{ $item->stok }}"
                                                data-berat="{{ $item->berat }}">
                                                {{ $item->nama }} - Rp
                                                {{ number_format($item->harga_aktif, 0, ',', '.') }} (Stok:
                                                {{ $item->stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" id="jumlah-input" placeholder="Jumlah"
                                        min="1">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-success" id="tambah-produk">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="produk-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Produk</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                            <td><strong id="total-amount">Rp 0</strong></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save mr-2"></i>Simpan Pesanan
                                </button>
                                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary btn-lg ml-2">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            let produkData = [];
            let totalAmount = 0;

            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Handle tipe pelanggan change
            $('input[name="tipe_pelanggan"]').change(function() {
                if ($(this).val() === 'baru') {
                    $('#new-customer-section').show();
                    $('#existing-customer-section').hide();
                    $('#customer-info').hide();
                    clearForm();
                } else {
                    $('#new-customer-section').hide();
                    $('#existing-customer-section').show();
                    $('#customer-info').hide();
                    clearForm();
                }
            });

            // Handle user selection
            $('#user_id').change(function() {
                const selectedOption = $(this).find('option:selected');
                if (selectedOption.val()) {
                    const name = selectedOption.data('name');
                    const phone = selectedOption.data('phone');
                    const email = selectedOption.data('email');

                    $('#customer-details').html(`
                <strong>Nama:</strong> ${name}<br>
                <strong>Telepon:</strong> ${phone}<br>
                <strong>Email:</strong> ${email}
            `);
                    $('#customer-info').show();
                } else {
                    $('#customer-info').hide();
                }
            });

            function clearForm() {
                $('#nama_pelanggan').val('');
                $('#no_telepon').val('');
                $('#email').val('');
                $('#alamat').val('');
                $('#user_id').val('').trigger('change');
            }

            // Tambah produk ke tabel
            $('#tambah-produk').click(function() {
                const selectedOption = $('#produk-select option:selected');
                const produkId = selectedOption.val();
                const jumlah = parseInt($('#jumlah-input').val()) || 0;

                if (!produkId || jumlah <= 0) {
                    showAlert('error', 'Pilih produk dan masukkan jumlah yang valid');
                    return;
                }

                const stok = parseInt(selectedOption.data('stok'));
                if (jumlah > stok) {
                    showAlert('error', 'Jumlah melebihi stok yang tersedia');
                    return;
                }

                // Cek apakah produk sudah ada
                const existingIndex = produkData.findIndex(item => item.id === produkId);
                if (existingIndex !== -1) {
                    const newJumlah = produkData[existingIndex].jumlah + jumlah;
                    if (newJumlah > stok) {
                        showAlert('error', 'Total jumlah melebihi stok yang tersedia');
                        return;
                    }
                    produkData[existingIndex].jumlah = newJumlah;
                } else {
                    produkData.push({
                        id: produkId,
                        nama: selectedOption.data('nama'),
                        harga: selectedOption.data('harga'),
                        jumlah: jumlah,
                        stok: stok
                    });
                }

                renderTable();
                $('#produk-select').val('').trigger('change');
                $('#jumlah-input').val('');
            });

            // Render tabel produk
            function renderTable() {
                const tbody = $('#produk-table tbody');
                tbody.empty();
                totalAmount = 0;

                produkData.forEach((item, index) => {
                    const subtotal = item.harga * item.jumlah;
                    totalAmount += subtotal;

                    const row = `
                <tr>
                    <td>${item.nama}</td>
                    <td>Rp ${item.harga.toLocaleString('id-ID')}</td>
                    <td>
                        <input type="number" class="form-control jumlah-input" 
                               value="${item.jumlah}" min="1" max="${item.stok}" 
                               data-index="${index}">
                    </td>
                    <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm hapus-produk" 
                                data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
                    tbody.append(row);
                });

                $('#total-amount').text(`Rp ${totalAmount.toLocaleString('id-ID')}`);
            }

            // Update jumlah produk
            $(document).on('change', '.jumlah-input', function() {
                const index = $(this).data('index');
                const newJumlah = parseInt($(this).val()) || 1;
                const stok = produkData[index].stok;

                if (newJumlah > stok) {
                    showAlert('error', 'Jumlah melebihi stok yang tersedia');
                    $(this).val(produkData[index].jumlah);
                    return;
                }

                produkData[index].jumlah = newJumlah;
                renderTable();
            });

            // Hapus produk
            $(document).on('click', '.hapus-produk', function() {
                const index = $(this).data('index');
                produkData.splice(index, 1);
                renderTable();
            });

            // Submit form
            $('#onsiteForm').submit(function(e) {
                e.preventDefault();

                if (produkData.length === 0) {
                    showAlert('error', 'Tambahkan minimal satu produk');
                    return;
                }

                const tipePelanggan = $('input[name="tipe_pelanggan"]:checked').val();

                // Validasi form berdasarkan tipe pelanggan
                if (tipePelanggan === 'baru') {
                    if (!$('#nama_pelanggan').val() || !$('#no_telepon').val() || !$('#email').val() || !$(
                            '#alamat').val()) {
                        showAlert('error', 'Semua field untuk pelanggan baru wajib diisi');
                        return;
                    }
                } else {
                    if (!$('#user_id').val()) {
                        showAlert('error', 'Pilih pelanggan yang sudah terdaftar');
                        return;
                    }
                }

                const formData = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    tipe_pelanggan: tipePelanggan,
                    user_id: $('#user_id').val(),
                    nama_pelanggan: $('#nama_pelanggan').val(),
                    no_telepon: $('#no_telepon').val(),
                    email: $('#email').val(),
                    alamat: $('#alamat').val(),
                    catatan_penjual: $('#catatan_penjual').val(),
                    produk: produkData.map(item => ({
                        id: item.id,
                        jumlah: item.jumlah
                    }))
                };

                $.ajax({
                    url: "{{ route('admin.pesanan.store-onsite') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            let message = response.message;
                            if (response.is_new_user) {
                                message +=
                                    '. Akun baru telah dibuat dengan password default: password123';
                            }
                            showAlert('success', message);
                            setTimeout(() => {
                                window.location.href =
                                    "{{ route('admin.pesanan.index') }}";
                            }, 2000);
                        } else {
                            showAlert('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                showAlert('error', value[0]);
                            });
                        } else {
                            showAlert('error', 'Terjadi kesalahan pada server');
                        }
                    }
                });
            });

            // Function to show alerts (you need to implement this based on your alert system)
            function showAlert(type, message) {
                // Implementation depends on your alert system
                // Example using simple alert:
                if (type === 'success') {
                    alert('Success: ' + message);
                } else {
                    alert('Error: ' + message);
                }
            }
        });
    </script>
@endsection
