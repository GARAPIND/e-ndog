@extends('layouts.main')

@section('title', 'Tambah Pesanan Onsite')
@section('page-title', 'Tambah Pesanan Onsite')
@section('page-subtitle', 'Pesanan/tambah_onsite')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@section('content')
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_pelanggan">Nama Pelanggan</label>
                                        <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_telepon">No. Telepon</label>
                                        <input type="text" class="form-control" id="no_telepon" name="no_telepon"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
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

                const formData = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    nama_pelanggan: $('#nama_pelanggan').val(),
                    no_telepon: $('#no_telepon').val(),
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
                            showAlert('success', response.message);
                            setTimeout(() => {
                                window.location.href =
                                    "{{ route('admin.pesanan.index') }}";
                            }, 1500);
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
        });
    </script>
@endsection
