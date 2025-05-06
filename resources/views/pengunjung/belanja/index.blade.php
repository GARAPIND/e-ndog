@extends('layouts_pengunjung.main')
@section('title', 'Belanja')

@section('content')
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ route('dashboard.pengunjung') }}">Home</a>
                    <span class="breadcrumb-item active">Belanja</span>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <b class="text-dark">Daftar Transaksi</b>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="pelanggan" class="col-form-label">Pelanggan</label>
                                    <input type="hidden" id="users_id" value="{{ Auth::user()->id }}">
                                    <input class="form-control" type="text" name="pelanggan" id="pelanggan"
                                        value="{{ Auth::user()->name }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="tanggal" class="col-form-label">Tanggal</label>
                                    <input class="form-control" type="text" name="tanggal" id="tanggal"
                                        value="{{ tanggalIndoLengkap(Date('Y-m-d')) }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="alamat" class="col-form-label">Dikirim ke alamat</label>
                                    <div class="input-group">
                                        <input type="hidden" name="alamat_id_aktif" id="alamat_id_aktif">
                                        <input type="hidden" name="city_id" id="city_id">
                                        <input class="form-control" type="text" name="alamat" id="alamat"
                                            placeholder="Masukkan alamat" readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" data-toggle="modal"
                                                data-target="#modalTambahAlamat" onclick="get_data_alamat()">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <h5>Tambah Pembelian</h5>
                                <div class="form-group">
                                    <input type="hidden" name="id_produk" id="id_produk">
                                    <label for="produk_id" class="col-form-label">Nama Produk</label>
                                    <select class="form-control" id="produk_id" name="produk_id"
                                        onchange="change_produk(this)">
                                        <option value="">Pilih produk</option>
                                        @foreach ($produk as $item)
                                            <option value="{{ $item->id }}" data-id-produk="{{ $item->id }}"
                                                data-kode="{{ $item->kode }}" data-nama="{{ $item->nama }}"
                                                data-stok="{{ intval($item->stok) }}" data-harga="{{ $item->harga }}"
                                                data-harga-diskon="{{ $item->harga_diskon }}"
                                                data-berat="{{ intval($item->berat) }}" data-satuan="{{ $item->satuan }}">
                                                {{ $item->kode }} || {{ $item->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-0">
                                    <label for="stok" class="col-form-label">Stok</label>
                                    <input class="form-control" type="text" name="stok" id="stok" value="-"
                                        disabled>
                                </div>
                                <div class="form-group mt-0">
                                    <label for="satuan" class="col-form-label">Satuan</label>
                                    <input class="form-control" type="text" name="satuan" id="satuan" value="-"
                                        disabled>
                                </div>
                                <div class="form-group mt-0">
                                    <label for="harga" class="col-form-label">Harga</label>
                                    <input class="form-control" type="text" name="harga" id="harga"
                                        value="-" disabled>
                                </div>
                                <div class="form-group mt-0">
                                    <label for="harga_diskon" class="col-form-label">Harga Diskon</label>
                                    <input class="form-control" type="text" name="harga_diskon" id="harga_diskon"
                                        value="-" disabled>
                                </div>
                                <button class="btn btn-primary" id="btn_tambah" onclick="tambah_barang()"><i
                                        class="fas fa-plus-circle"></i>Tambahkan</button>
                            </div>
                            <div class="col-sm-8">
                                <div class="card card-body shadow-lg">
                                    <h5>Daftar Pembelian</h5>
                                    <div class="table_transaksi" style="width:100%; height: 200px; overflow-y: auto;">
                                        <table id="table-transaksi"
                                            class="table table-bordered table-hover table-sm text-center">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th width="10%">Kode</th>
                                                    <th width="15%">Nama Barang</th>
                                                    <th width="15%">Harga</th>
                                                    <th width="10%">Jumlah</th>
                                                    <th width="15%">Total</th>
                                                    <th width="20%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot class="thead-light">
                                                <tr>
                                                    <th colspan="2" class="text-center">Total</th>
                                                    <th id="total-harga" class="text-right">Rp 0</th>
                                                    <th id="total-jumlah">0</th>
                                                    <th id="total-semua" class="text-right">Rp 0</th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="catatan" class="col-form-label">Catatan untuk penjual
                                                <small>(Bisa dikosongkan)</small> </label>
                                            <textarea class="form-control" id="catatan" name="catatan" rows="3"
                                                placeholder="Tambahkan catatan untuk penjual"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="metode_pembayaran" class="col-form-label">Metode
                                                Pembayaran</label>
                                            <select class="form-control" id="metode_pembayaran" name="metode_pembayaran"
                                                onchange="ubah_metode_pembayaran()">
                                                <option value="COD">COD (Cash on Delivery)</option>
                                                <option value="online">Pembayaran Online</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6" id="form_kurir_cod">
                                        <div class="form-group">
                                            <label for="kurir_cod" class="col-form-label">Kurir COD</label>
                                            <select class="form-control select2" id="kurir_cod" name="kurir_cod"
                                                style="width: 100%;">
                                                <option value="">-- Pilih Kurir --</option>
                                                @foreach ($kurir as $item)
                                                    <option value="{{ $item->id }}">{{ $item->user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6" id="form_kurir_raja_ongkir">
                                        <div class="form-group">
                                            <label for="kurir_raja_ongkir" class="col-form-label">Ekspedisi</label>
                                            <select class="form-control select2" id="kurir_raja_ongkir"
                                                name="kurir_raja_ongkir" style="width: 100%;">
                                                <option value="">Loading ...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-danger float-left" id="btn_batal"><i
                                        class="fas fa-window-close"></i>
                                    Batalkan Transaksi</button>
                                <button class="btn btn-success float-right" id="btn_submit" onclick="buat_transaksi()"><i
                                        class="fas fa-cart-plus"></i> Buat Transaksi</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahAlamat" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahAlamatLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahAlamatLabel">Data Alamat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="alamat_id" id="alamat_id">
                    <div id="data_alamat"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="ganti_alamat()">Gunakan Alamat</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-XlYKYpWQKtLrtPtA"></script>
    <script>
        $(document).ready(function() {
            get_alamat_aktif();
            ubah_metode_pembayaran();
            $('#kurir_raja_ongkir, #kurir_cod, #metode_pembayaran').select2();
        })

        function get_data_ongkir() {
            var city_id = $('#city_id').val();
            $.ajax({
                url: "{{ route('belanja.cek_ongkir') }}",
                type: 'post',
                data: {
                    city_id: city_id,
                    weight: 1000
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    const $select = $('#kurir_raja_ongkir');
                    $select.empty().append('<option value="">-- Pilih Ekspedisi --</option>');

                    Object.keys(response).forEach(kurir => {
                        const layanan = response[kurir];
                        layanan.forEach(service => {
                            const namaLayanan = `${kurir.toUpperCase()} - ${service.service}`;
                            const harga = service.cost[0].value;
                            const etd = service.cost[0].etd;

                            const label =
                                `${namaLayanan} | Rp ${harga.toLocaleString()} | ${etd} hari`;
                            const value =
                                `${kurir}|${service.service}|${harga}|${etd}`;

                            $select.append(`<option value="${value}">${label}</option>`);
                        });
                    });
                },
                error: (xhr) => {
                    Notiflix.Notify.failure('Terjadi kesalahan saat ambil ekspedisi.');
                }
            });
        }

        function get_alamat_aktif() {
            $.ajax({
                url: "{{ route('belanja.get_data_alamat_aktif') }}",
                type: 'get',
                beforeSend: () => {
                    $('#alamat').val(`Loading alamat ...`);
                },
                success: (response) => {
                    $('#alamat').val(response['alamat']);
                    $('#alamat_id_aktif').val(response['alamat_id']);
                    $('#city_id').val(response['city_id']);
                    get_data_ongkir();
                },
                error: ({
                    responseText
                }) => {
                    Notiflix.Notify.failure(responseText);
                }
            })
        }

        function get_data_alamat() {
            $.ajax({
                url: "{{ route('belanja.get_data_alamat') }}",
                type: 'get',
                beforeSend: () => {
                    $('#data_alamat').html(`
                <div class="col-12 d-flex justify-content-center align-items-center" style="height: 200px;">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                        <div>Loading data alamat...</div>
                    </div>
                </div>
            `);
                },
                success: (response) => {
                    let html = '';

                    if (response.length > 0) {
                        response.forEach((item) => {
                            html += `
                        <div class="card mb-3 alamat-card" 
                             data-id="${item.id}" style="cursor: pointer;">
                            <div class="card-body">
                                <h5 class="card-title">
                                    ${item.keterangan} ${item.is_primary == 1 ? '<span class="badge badge-success">Utama</span>' : ''}
                                </h5>
                                <p class="card-text mb-1"><strong>Alamat:</strong> ${item.alamat}</p>
                                <p class="card-text mb-1"><strong>Provinsi:</strong> ${item.provinsi}</p>
                                <p class="card-text mb-1"><strong>Kota:</strong> ${item.kota}</p>
                                <p class="card-text mb-1"><strong>Kecamatan:</strong> ${item.kecamatan}</p>
                                <p class="card-text mb-1"><strong>Kode Pos:</strong> ${item.kode_pos}</p>
                            </div>
                        </div>
                    `;
                            if (item.is_primary == 1) {
                                $('#alamat_id').val(item.id);
                            }
                        });
                    } else {
                        html =
                            `<div class="alert alert-warning text-center">Tidak ada data alamat tersedia.</div>`;
                    }

                    $('#data_alamat').html(html);

                    $('.alamat-card').on('click', function() {
                        $('.alamat-card').removeClass('border-primary');
                        $(this).addClass('border-primary');
                        $('#alamat_id').val($(this).data('id'));
                    });
                },
                error: ({
                    responseText
                }) => {
                    Notiflix.Notify.failure(responseText);
                }
            });
        }

        function ganti_alamat() {
            var alamat_id = $('#alamat_id').val();

            $.ajax({
                url: "{{ route('belanja.ganti_alamat') }}",
                type: 'post',
                data: {
                    alamat_id: alamat_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    $('#modalTambahAlamat').modal('hide');
                    get_alamat_aktif();
                    Notiflix.Notify.success('Alamat berhasil diganti');
                },
                error: (xhr) => {
                    Notiflix.Notify.failure('Terjadi kesalahan saat mengganti alamat.');
                }
            });
        }

        let idProduk = "";
        let kode = "";
        let nama = "";
        let stok = "";
        let harga = "";
        let hargaDiskon = "";
        let satuan = "";

        function change_produk(this_) {
            const selectedOption = this_.options[this_.selectedIndex];

            idProduk = selectedOption.getAttribute('data-id-produk');
            kode = selectedOption.getAttribute('data-kode');
            nama = selectedOption.getAttribute('data-nama');
            stok = selectedOption.getAttribute('data-stok');
            harga = selectedOption.getAttribute('data-harga-diskon') || selectedOption.getAttribute('data-harga');
            hargaDiskon = selectedOption.getAttribute('data-harga-diskon');
            satuan = selectedOption.getAttribute('data-satuan');

            $('#stok').val(stok);
            $('#satuan').val(satuan);
            $('#harga').val(rupiahFormat(selectedOption.getAttribute('data-harga')));
            $('#harga_diskon').val(rupiahFormat(hargaDiskon));
        }

        function tambah_barang() {
            if (kode == "") {
                Notiflix.Notify.failure("Pilih produk terlebih dahulu!");
                return;
            }

            const idBarang = idProduk;
            const stokBarang = parseInt(stok);
            const barangId = kode;
            const barangNama = nama;
            const hargaJual = harga;

            const jumlah = 1;

            if (jumlah > stokBarang) {
                Notiflix.Notify.failure("Jumlah barang melebihi stok yang tersedia!");
                return;
            }

            const total = hargaJual * jumlah;
            const table = document.getElementById('table-transaksi').getElementsByTagName('tbody')[0];
            let rowExists = false;

            for (let i = 0; i < table.rows.length; i++) {
                const row = table.rows[i];
                const currentBarangId = row.cells[0].textContent;

                if (currentBarangId == barangId) {
                    const currentQuantity = parseInt(row.cells[3].textContent);
                    const newQuantity = currentQuantity + jumlah;

                    if (newQuantity > stokBarang) {
                        Notiflix.Notify.failure("Jumlah barang melebihi stok yang tersedia!");
                        return;
                    }

                    row.cells[3].textContent = newQuantity;
                    row.cells[4].textContent = rupiahFormat(hargaJual * newQuantity);
                    rowExists = true;
                    break;
                }
            }

            if (!rowExists) {
                const newRow = table.insertRow(table.rows.length);
                newRow.innerHTML = `
                <td data-barang-id="${idBarang}">${barangId}</td>
                <td data-stok="${stokBarang}">${barangNama}</td>
                <td class="text-right">${rupiahFormat(hargaJual)}</td>
                <td>${jumlah}</td>
                <td class="text-right">${rupiahFormat(total)}</td>
                <td>
                    <button class="btn btn-warning btn-sm subtract-item"><i class="fas fa-minus-circle"></i></button>
                    <button class="btn btn-success btn-sm add-item"><i class="fas fa-plus-circle"></i></button>
                    <button class="btn btn-danger btn-sm remove-item"><i class="fas fa-trash"></i> Hapus</button>
                </td>
            `;

                // proses untuk menambah jumlah barang di dalam tabel
                newRow.querySelector('.add-item').addEventListener('click', function() {
                    const currentQuantity = parseInt(newRow.cells[3].textContent);
                    const stokTersedia = parseInt(newRow.cells[1].getAttribute('data-stok'));

                    if (currentQuantity + 1 > stokTersedia) {
                        Notiflix.Notify.failure("Jumlah barang melebihi stok yang tersedia!");
                        return;
                    }

                    const newQuantity = currentQuantity + 1;
                    newRow.cells[3].textContent = newQuantity;
                    newRow.cells[4].textContent = rupiahFormat(hargaJual * newQuantity);
                    updateFooter();
                });

                // proses untuk mengurangi jumlah barang di dalam tabel
                newRow.querySelector('.subtract-item').addEventListener('click', function() {
                    const currentQuantity = parseInt(newRow.cells[3].textContent);
                    if (currentQuantity > 1) {
                        const newQuantity = currentQuantity - 1;
                        newRow.cells[3].textContent = newQuantity;
                        newRow.cells[4].textContent = rupiahFormat(hargaJual * newQuantity);
                    } else {
                        newRow.remove();
                    }
                    updateFooter();
                });

                // proses untuk menghapus barang dalam tabel
                newRow.querySelector('.remove-item').addEventListener('click', function() {
                    newRow.remove();
                    updateFooter();
                });
            }
            updateFooter();
            $('#produk_id').val('').change();
            $('#stok').val('');
            $('#satuan').val('');
            $('#harga').val('');
            $('#harga_diskon').val('');

            idProduk = "";
            kode = "";
            nama = "";
            stok = "";
            harga = "";
            hargaDiskon = "";
            satuan = "";
        }

        function updateFooter() {
            const table = document.getElementById('table-transaksi').getElementsByTagName('tbody')[0];
            let totalHarga = 0;
            let totalJumlah = 0;
            let totalSemua = 0;

            for (let i = 0; i < table.rows.length; i++) {
                const row = table.rows[i];
                totalHarga += parseInt(row.cells[2].textContent.replace(/\D/g, ''), 10);
                totalJumlah += parseInt(row.cells[3].textContent, 10);
                totalSemua += parseInt(row.cells[4].textContent.replace(/\D/g, ''), 10);
            }

            $('#total-harga').html(rupiahFormat(totalHarga));
            $('#total-jumlah').html(rupiahFormat(totalJumlah));
            $('#total-semua').html(rupiahFormat(totalSemua));
        }

        function ubah_metode_pembayaran() {
            var metode = $('#metode_pembayaran').val();
            if (metode == "COD") {
                $('#form_kurir_cod').show();
                $('#form_kurir_raja_ongkir').hide();
            } else {
                $('#form_kurir_cod').hide();
                $('#form_kurir_raja_ongkir').show();
            }
        }

        function buat_transaksi() {
            $.ajax({
                url: "{{ route('belanja.createTransaction') }}",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    payWithMidtrans(response['snap_token']);
                },
                error: (xhr) => {
                    Notiflix.Notify.failure('Terjadi kesalahan saat ambil ekspedisi.');
                }
            });
        }

        function payWithMidtrans(snapToken) {
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    alert('Pembayaran berhasil!');
                    console.log(result);
                },
                onPending: function(result) {
                    alert('Pembayaran pending!');
                    console.log(result);
                },
                onError: function(result) {
                    alert('Terjadi kesalahan pada pembayaran!');
                    console.log(result);
                }
            });
        }
    </script>
@endsection
