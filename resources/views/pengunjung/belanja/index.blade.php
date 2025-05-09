@extends('layouts_pengunjung.main')
@section('title', 'Belanja')

@section('content')
    <div id="loading-overlay"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.7); z-index: 9999; display: flex; justify-content: center; align-items: center;">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Memproses Data...</span>
        </div>
    </div>

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
                                    <input type="hidden" name="tanggal_transaksi" id="tanggal_transaksi"
                                        value="{{ Date('Y-m-d') }}">
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
                                    <label for="berat" class="col-form-label">Berat (gram)</label>
                                    <input class="form-control" type="text" name="berat" id="berat"
                                        value="-" disabled>
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
                                                    <th width="15%">Berat</th>
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
                                                    <th id="total-berat" class="text-right">0</th>
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
                                            <input type="hidden" name="latitude_toko" id="latitude_toko"
                                                value="{{ $profileToko->latitude }}">
                                            <input type="hidden" name="longitude_toko" id="longitude_toko"
                                                value="{{ $profileToko->longitude }}">
                                            <input type="hidden" name="latitude_pelanggan" id="latitude_pelanggan">
                                            <input type="hidden" name="longitude_pelanggan" id="longitude_pelanggan">

                                            <input type="hidden" name="nama_ekspedisi" id="nama_ekspedisi">
                                            <input type="hidden" name="harga_ekspedisi" id="harga_ekspedisi">
                                            <input type="hidden" name="jarak" id="jarak">
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
                                                <option value="0">Pembayaran Online</option>
                                                <option value="1">COD (Cash on Delivery)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6" id="form_kurir_raja_ongkir">
                                        <div class="form-group">
                                            <label for="kurir_raja_ongkir" class="col-form-label">Ekspedisi</label>
                                            <select class="form-control select2" id="kurir_raja_ongkir"
                                                name="kurir_raja_ongkir" style="width: 100%;"
                                                onchange="change_ekspedisi(this)">
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
                                <button class="btn btn-danger float-left" id="btn_batal" onclick="batal_transaksi()"><i
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
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-VizuQvv54xcuk1Yg"></script>
    <script>
        $(document).ready(function() {
            $('#loading-overlay').fadeOut();
            get_alamat_aktif();
            ubah_metode_pembayaran();
            $('#kurir_raja_ongkir, #kurir_cod, #metode_pembayaran').select2();
        })

        function get_data_ongkir() {
            var city_id = $('#city_id').val();
            var totalweight = $('#total-berat').text();
            $.ajax({
                url: "{{ route('belanja.cek_ongkir') }}",
                type: 'post',
                data: {
                    city_id: city_id,
                    weight: totalweight
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#kurir_raja_ongkir').html('<option value="">Loading ...</option>');
                },
                success: (response) => {
                    const $select = $('#kurir_raja_ongkir');
                    $select.empty().append('<option value="">-- Pilih Ekspedisi --</option>');

                    if (Object.keys(response).length === 0) {
                        $select.append('<option value="">Tidak ada ekspedisi tersedia</option>');
                        return;
                    }

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

                            $select.append(
                                `<option value="${value}" data-nama-kurir="${namaLayanan}" data-harga-kurir="${harga}">${label}</option>`
                            );
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
                    $('#latitude_pelanggan').val(response['latitude']);
                    $('#longitude_pelanggan').val(response['longitude']);
                    get_data_ongkir();
                    hitungJarak();
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

        function change_ekspedisi(this_) {
            const selectedOption = this_.options[this_.selectedIndex];
            $('#nama_ekspedisi').val(selectedOption.getAttribute('data-nama-kurir'));
            $('#harga_ekspedisi').val(selectedOption.getAttribute('data-harga-kurir'));
        }

        let idProduk = "";
        let kode = "";
        let nama = "";
        let stok = "";
        let harga = "";
        let hargaDiskon = "";
        let berat = "";
        let satuan = "";

        function change_produk(this_) {
            const selectedOption = this_.options[this_.selectedIndex];

            idProduk = selectedOption.getAttribute('data-id-produk');
            kode = selectedOption.getAttribute('data-kode');
            nama = selectedOption.getAttribute('data-nama');
            stok = selectedOption.getAttribute('data-stok');
            harga = selectedOption.getAttribute('data-harga-diskon') || selectedOption.getAttribute('data-harga');
            hargaDiskon = selectedOption.getAttribute('data-harga-diskon');
            berat = parseInt(selectedOption.getAttribute('data-berat'));
            satuan = selectedOption.getAttribute('data-satuan');

            $('#stok').val(stok);
            $('#berat').val(`${berat} ${satuan}`);
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
            const barangId = idProduk;
            const barangNama = nama;
            const hargaJual = harga;
            const beratBarang = berat


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

                    const currentWeight = parseInt(row.cells[5].textContent);
                    const newWeight = currentWeight + beratBarang;

                    if (newQuantity > stokBarang) {
                        Notiflix.Notify.failure("Jumlah barang melebihi stok yang tersedia!");
                        return;
                    }

                    row.cells[3].textContent = newQuantity;
                    row.cells[4].textContent = rupiahFormat(hargaJual * newQuantity);
                    row.cells[5].textContent = newWeight;
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
                <td class="text-right">${beratBarang}</td>
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

                    const currentWeight = parseInt(newRow.cells[5].textContent);
                    const newWeight = currentWeight + beratBarang;

                    if (currentQuantity + 1 > stokTersedia) {
                        Notiflix.Notify.failure("Jumlah barang melebihi stok yang tersedia!");
                        return;
                    }

                    const newQuantity = currentQuantity + 1;
                    newRow.cells[3].textContent = newQuantity;
                    newRow.cells[4].textContent = rupiahFormat(hargaJual * newQuantity);
                    newRow.cells[5].textContent = newWeight;
                    updateFooter();
                });

                // proses untuk mengurangi jumlah barang di dalam tabel
                newRow.querySelector('.subtract-item').addEventListener('click', function() {
                    const currentQuantity = parseInt(newRow.cells[3].textContent);
                    const currentWeight = parseInt(newRow.cells[5].textContent);
                    const newWeight = currentWeight - beratBarang;
                    if (currentQuantity > 1) {
                        const newQuantity = currentQuantity - 1;
                        newRow.cells[3].textContent = newQuantity;
                        newRow.cells[4].textContent = rupiahFormat(hargaJual * newQuantity);
                        newRow.cells[5].textContent = newWeight;
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
            $('#berat').val('');
            $('#harga').val('');
            $('#harga_diskon').val('');

            idProduk = "";
            kode = "";
            nama = "";
            stok = "";
            harga = "";
            hargaDiskon = "";
            berat = "";
            satuan = "";
        }

        function updateFooter() {
            const table = document.getElementById('table-transaksi').getElementsByTagName('tbody')[0];
            let totalHarga = 0;
            let totalJumlah = 0;
            let totalSemua = 0;
            let totalBerat = 0;

            for (let i = 0; i < table.rows.length; i++) {
                const row = table.rows[i];
                totalHarga += parseInt(row.cells[2].textContent.replace(/\D/g, ''), 10);
                totalJumlah += parseInt(row.cells[3].textContent, 10);
                totalSemua += parseInt(row.cells[4].textContent.replace(/\D/g, ''), 10);
                totalBerat += parseInt(row.cells[5].textContent.replace(/\D/g, ''), 10);
            }

            $('#total-harga').html(rupiahFormat(totalHarga));
            $('#total-jumlah').html(rupiahFormat(totalJumlah));
            $('#total-semua').html(rupiahFormat(totalSemua));
            $('#total-berat').html(totalBerat);
            get_data_ongkir();
        }

        function ubah_metode_pembayaran() {
            var metode = $('#metode_pembayaran').val();
            var jarak = parseFloat($('#jarak').val());
            var berat = parseFloat($('#total-berat').text());
            if (metode == "1") {
                if (jarak <= 10000 && berat >= 5000) {
                    $('#form_kurir_raja_ongkir').hide();
                    $('#nama_ekspedisi').val('Lokal');
                    $('#harga_ekspedisi').val('0');
                } else {
                    Notiflix.Notify.failure('COD hanya berlaku untuk jarak <= 10km dan berat >= 5kg');
                    $('#metode_pembayaran').val(0).change();
                }
            } else {
                $('#form_kurir_raja_ongkir').show();
            }
        }

        function batal_transaksi() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Transaksi yang sedang berjalan akan dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, batalkan!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }

        function buat_transaksi() {
            var formData = new FormData();
            var barangData = [];
            var table = document.getElementById('table-transaksi').getElementsByTagName('tbody')[0];
            for (let i = 0; i < table.rows.length; i++) {
                const row = table.rows[i];
                var barang = {
                    barang_id: row.cells[0].getAttribute('data-barang-id'),
                    jumlah: row.cells[3].textContent,
                    sub_total: parseInt(row.cells[4].textContent.replace(/\D/g, ''), 10),
                    berat: parseInt(row.cells[5].textContent)
                };
                barangData.push(barang);
            }
            formData.append("users_id", $('#users_id').val());
            formData.append("tanggal_transaksi", $('#tanggal_transaksi').val());
            formData.append("alamat_id", $('#alamat_id_aktif').val());
            formData.append("jarak", $('#jarak').val());
            formData.append("is_cod", $('#metode_pembayaran').val());
            formData.append("ekspedisi", $('#nama_ekspedisi').val());
            formData.append("sub_total", parseInt($('#total-semua').text().replace(/\D/g, ''), 10));
            formData.append("total_berat", parseInt($('#total-berat').text()));
            formData.append("ongkir", $('#harga_ekspedisi').val());
            formData.append("catatan_pelanggan", $('#catatan').val());

            formData.append("barang_data", JSON.stringify(barangData));

            $.ajax({
                url: "{{ route('belanja.createTransaction') }}",
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: () => {
                    $('#loading-overlay').fadeIn();
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    $('#loading-overlay').fadeOut();
                    if (response.type == 'COD') {
                        if (response.status === 'success') {
                            Notiflix.Notify.success('Pemesanan Berhasil');
                            const finishRedirectUrl = '/belanja/sukses';
                            const orderId = response.order_id;
                            window.location.href = `${finishRedirectUrl}/${orderId}`;
                        } else {
                            Notiflix.Notify.failure(response.message || 'Transaksi gagal.');
                        }
                    } else {
                        if (response.status === 'success') {
                            payWithMidtrans(response.snap_token);
                        } else {
                            Notiflix.Notify.failure(response.message || 'Transaksi gagal.');
                        }
                    }
                },
                error: (xhr) => {
                    $('#loading-overlay').fadeOut();
                    let message = 'Terjadi kesalahan saat membuat transaksi.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Notiflix.Notify.failure(message);
                }
            });
        }

        function payWithMidtrans(snapToken) {
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    Notiflix.Notify.success('Pembayaran Berhasil');
                    const finishRedirectUrl = '/belanja/sukses';
                    const orderId = result.order_id;
                    window.location.href = `${finishRedirectUrl}/${orderId}`;
                },
                onPending: function(result) {
                    console.log(result);
                    Notiflix.Notify.warning('Pembayaran Pending!');
                    const finishRedirectUrl = '/belanja/gagal';
                    const orderId = result.order_id;
                    window.location.href = `${finishRedirectUrl}/${orderId}`;
                },
                onError: function(result) {
                    console.log(result);
                    Notiflix.Notify.failure('Terjadi kesalahan pada pembayaran!');
                    const finishRedirectUrl = '/belanja/gagal';
                    const orderId = result.order_id;
                    window.location.href = `${finishRedirectUrl}/${orderId}`;
                }
            });
        }

        function hitungJarak() {
            var lat1 = $('#latitude_pelanggan').val();
            var lon1 = $('#longitude_pelanggan').val();
            var lat2 = $('#latitude_toko').val();
            var lon2 = $('#longitude_toko').val();

            var R = 6371000;
            var dLat = (lat2 - lat1) * Math.PI / 180;
            var dLon = (lon2 - lon1) * Math.PI / 180;
            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var distance = R * c;
            $('#jarak').val(distance.toFixed(0));
        }
    </script>
@endsection
