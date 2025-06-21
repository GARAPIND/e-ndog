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
                        <!-- Step Container -->
                        <div id="step-1">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="pelanggan">Pelanggan</label>
                                        <input type="hidden" id="users_id" value="{{ Auth::user()->id }}">
                                        <input class="form-control" type="text" value="{{ Auth::user()->name }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input class="form-control" type="text"
                                            value="{{ tanggalIndoLengkap(Date('Y-m-d')) }}" disabled>
                                        <input type="hidden" id="tanggal_transaksi" value="{{ Date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="alamat">Dikirim ke alamat</label>
                                        <div class="input-group">
                                            <input type="hidden" id="alamat_id_aktif">
                                            <input type="hidden" id="city_id">
                                            <input class="form-control" type="text" id="alamat"
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

                            <!-- Tambah produk -->
                            <div class="row">
                                <div class="col-sm-4">
                                    <h5>Tambah Pembelian</h5>
                                    <div class="form-group">
                                        <input type="hidden" id="id_produk">
                                        <label for="produk_id">Nama Produk</label>
                                        <select class="form-control" id="produk_id" onchange="change_produk(this)">
                                            <option value="" data-stok="-" data-satuan=""
                                                data-foto="/assets/default.jpg">Pilih
                                                produk</option>
                                            @foreach ($produk as $item)
                                                <option value="{{ $item->id }}" data-id-produk="{{ $item->id }}"
                                                    data-kode="{{ $item->kode }}" data-nama="{{ $item->nama }}"
                                                    data-stok="{{ intval($item->stok) }}" data-harga="{{ $item->harga }}"
                                                    data-harga-grosir="{{ $item->harga_grosir }}"
                                                    data-harga-pengampu="{{ $item->harga_pengampu }}"
                                                    data-berat="{{ intval($item->berat) }}"
                                                    data-foto="storage/foto-produk/{{ $item->foto }}"
                                                    data-satuan="{{ $item->satuan }}">
                                                    {{ $item->kode }} || {{ $item->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Stok</label>
                                        <input class="form-control" type="text" id="stok" value="-" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Foto Produk</label>
                                        <div>
                                            <img id="foto-produk" src="/assets/default.jpg" alt="Foto Produk"
                                                class="img-fluid rounded" style="max-height: 200px;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Harga Ecer <small class="text-muted">(Berlaku untuk pembelian dibawah
                                                10kg)</small></label>
                                        <input class="form-control" type="text" id="harga" value="-" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Harga Grosir <small class="text-muted">(Berlaku untuk pembelian
                                                10-30kg)</small></label>
                                        <input class="form-control" type="text" id="harga_grosir" value="-"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Harga Pengampu <small class="text-muted">(Berlaku untuk pembelian diatas
                                                30kg)</small></label>
                                        <input class="form-control" type="text" id="harga_pengampu" value="-"
                                            disabled>
                                    </div>
                                    <button class="btn btn-primary" onclick="tambah_barang()">
                                        <i class="fas fa-plus-circle"></i> Tambahkan
                                    </button>
                                </div>
                                <div class="col-sm-8">
                                    <div class="card card-body shadow-lg">
                                        <h5>Daftar Pembelian</h5>
                                        <p class="text-muted mb-2" style="font-size: 0.9rem;">
                                            * Harga ecer sudah bersahabat<br>
                                            * Beli grosir? lebih hemat lagi<br>
                                            * Harga pengampu super terjangkau
                                        </p>
                                        <div class="table_transaksi" style="width:100%; height: 350px; overflow-y: auto;">
                                            <table id="table-transaksi"
                                                class="table table-bordered table-hover table-sm text-center">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Kode</th>
                                                        <th>Nama Barang</th>
                                                        <th>Harga</th>
                                                        <th>Jumlah</th>
                                                        <th>Total</th>
                                                        <th>Berat (KG)</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot class="thead-light">
                                                    <tr>
                                                        <th colspan="2">Total</th>
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
                                    <input type="hidden" id="latitude_toko" value="{{ $profileToko->latitude }}">
                                    <input type="hidden" id="longitude_toko" value="{{ $profileToko->longitude }}">
                                    <input type="hidden" id="latitude_pelanggan">
                                    <input type="hidden" id="longitude_pelanggan">
                                    <input type="hidden" id="nama_ekspedisi">
                                    <input type="hidden" id="harga_ekspedisi">
                                    <input type="hidden" id="jarak">
                                    <button class="btn btn-primary float-right" id="btnNext"
                                        onclick="nextStep()">Selanjutnya</button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div id="step-2" style="display: none;">

                            <div class="alert alert-warning" role="alert">
                                <strong>Perhatian!</strong> COD hanya bisa dilakukan jika total berat pesanan lebih dari
                                <strong>5kg</strong> dan alamat berada di wilayah <strong>Kota/Kab Kediri</strong>.
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Catatan untuk penjual <small>(Bisa dikosongkan)</small></label>
                                        <textarea class="form-control" id="catatan" rows="3" placeholder="Tambahkan catatan untuk penjual"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Metode Pembayaran</label>
                                        <select class="form-control" id="metode_pembayaran"
                                            onchange="ubah_metode_pembayaran()">
                                            <option value="1">COD (Cash on Delivery)</option>
                                            <option value="0">Pembayaran Online</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Sub Total</label>
                                        <input type="text" id="sub_total" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-6" id="form_harga_ongkir">
                                    <div class="form-group">
                                        <label>Harga Ongkir</label>
                                        <input type="text" id="harga_ongkir" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-6" id="form_kurir_raja_ongkir">
                                    <div class="form-group">
                                        <label>Ekspedisi</label><br>
                                        <select class="form-control" id="kurir_raja_ongkir"
                                            onchange="change_ekspedisi(this)">
                                            <option value="">Loading ...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-secondary float-left" onclick="prevStep()">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </button>
                                    <button class="btn btn-danger float-left ml-2" onclick="batal_transaksi()">
                                        <i class="fas fa-window-close"></i> Batalkan Transaksi
                                    </button>
                                    <button class="btn btn-success float-right" onclick="buat_transaksi()">
                                        <i class="fas fa-cart-plus"></i> Buat Transaksi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-body -->
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
            // ubah_metode_pembayaran();
            $('#kurir_raja_ongkir, #kurir_cod, #metode_pembayaran').select2();
        })

        function nextStep() {
            const rowCount = $('#table-transaksi tbody tr').length;

            if (rowCount === 0) {
                Notiflix.Notify.warning('Tidak ada produk yang dipesan.');
                return;
            }

            $('#step-1').hide();
            $('#step-2').show();
            ubah_metode_pembayaran();
            // get_data_ongkir();
        }

        function prevStep() {
            $('#step-2').hide();
            $('#step-1').show();
        }


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
                    if (response.status == 'error') {
                        Notiflix.Notify.warning(
                            "Belum ada alamat yang aktif. Silakan tambah alamat di profile.");
                        $('#btnNext').prop('disabled', true);
                    } else {
                        $('#alamat').val(response['alamat']);
                        $('#alamat_id_aktif').val(response['alamat_id']);
                        $('#city_id').val(response['city_id']);
                        $('#latitude_pelanggan').val(response['latitude']);
                        $('#longitude_pelanggan').val(response['longitude']);
                        $('#btnNext').prop('disabled', false);
                        hitungJarak();
                    }
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
        let hargaGrosir = "";
        let hargaPengampu = "";
        let berat = "";
        let satuan = "";

        function getHargaByBerat(beratTotal, harga, hargaGrosir, hargaPengampu) {
            if (beratTotal < 10) return parseInt(harga);
            else if (beratTotal <= 30) return parseInt(hargaGrosir);
            else return parseInt(hargaPengampu);
        }

        function getStatusByBerat(beratTotal) {
            if (beratTotal < 10) return 'ecer';
            else if (beratTotal <= 30) return 'grosir';
            else return 'pengampu';
        }

        function change_produk(this_) {
            const selectedOption = this_.options[this_.selectedIndex];

            idProduk = selectedOption.getAttribute('data-id-produk');
            kode = selectedOption.getAttribute('data-kode');
            foto = selectedOption.getAttribute('data-foto');
            nama = selectedOption.getAttribute('data-nama');
            stok = selectedOption.getAttribute('data-stok');
            harga = selectedOption.getAttribute('data-harga');
            hargaGrosir = selectedOption.getAttribute('data-harga-grosir');
            hargaPengampu = selectedOption.getAttribute('data-harga-pengampu');
            berat = parseInt(selectedOption.getAttribute('data-berat'));
            satuan = selectedOption.getAttribute('data-satuan');

            $('#stok').val(`${stok} ${satuan}`);
            $('#foto-produk').attr('src', foto);
            $('#harga').val(rupiahFormat(selectedOption.getAttribute('data-harga')));
            $('#harga_grosir').val(rupiahFormat(hargaGrosir));
            $('#harga_pengampu').val(rupiahFormat(hargaPengampu));
        }

        function tambah_barang() {
            const select = document.getElementById('produk_id');
            const selectedOption = select.options[select.selectedIndex];

            if (!selectedOption.value) {
                Notiflix.Notify.failure("Pilih produk terlebih dahulu!");
                return;
            }

            const idBarang = selectedOption.getAttribute('data-id-produk');
            const kodeBarang = selectedOption.getAttribute('data-kode');
            const namaBarang = selectedOption.getAttribute('data-nama');
            const stok = parseInt(selectedOption.getAttribute('data-stok'));
            const harga = parseInt(selectedOption.getAttribute('data-harga'));
            const hargaGrosir = parseInt(selectedOption.getAttribute('data-harga-grosir'));
            const hargaPengampu = parseInt(selectedOption.getAttribute('data-harga-pengampu'));
            const berat = parseInt(selectedOption.getAttribute('data-berat'));

            const jumlah = 1;
            const totalBerat = jumlah * berat;
            const hargaJual = getHargaByBerat(totalBerat, harga, hargaGrosir, hargaPengampu);
            const statusHarga = getStatusByBerat(totalBerat);
            const total = hargaJual * jumlah;

            const table = document.getElementById('table-transaksi').getElementsByTagName('tbody')[0];

            let rowExists = false;

            for (let i = 0; i < table.rows.length; i++) {
                const row = table.rows[i];
                if (row.getAttribute('data-id') === idBarang) {
                    let qty = parseInt(row.cells[3].textContent);
                    let beratLama = parseInt(row.cells[5].textContent);
                    let beratBaru = beratLama + berat;
                    let qtyBaru = qty + 1;

                    if (qtyBaru > stok) {
                        Notiflix.Notify.failure("Jumlah melebihi stok!");
                        return;
                    }

                    const hargaJualBaru = getHargaByBerat(beratBaru, harga, hargaGrosir, hargaPengampu);

                    row.cells[3].textContent = qtyBaru;
                    row.cells[4].textContent = rupiahFormat(hargaJualBaru * qtyBaru);
                    row.cells[5].textContent = beratBaru;

                    row.setAttribute('data-harga', harga);
                    row.setAttribute('data-harga-grosir', hargaGrosir);
                    row.setAttribute('data-harga-pengampu', hargaPengampu);
                    row.setAttribute('data-berat', berat);

                    rowExists = true;
                    break;
                }
            }

            if (!rowExists) {
                const newRow = table.insertRow();
                newRow.setAttribute('data-id', idBarang);
                newRow.setAttribute('data-harga', harga);
                newRow.setAttribute('data-harga-grosir', hargaGrosir);
                newRow.setAttribute('data-harga-pengampu', hargaPengampu);
                newRow.setAttribute('data-berat', berat);

                newRow.innerHTML = `
            <td data-barang-id="${idBarang}">${kodeBarang}</td>
            <td>${namaBarang}</td>
            <td class="text-right" data-status="${statusHarga}">${rupiahFormat(hargaJual)}</td>
            <td>${jumlah}</td>
            <td class="text-right">${rupiahFormat(total)}</td>
            <td class="text-right">${totalBerat}</td>
            <td>
                <button class="btn btn-warning btn-sm subtract-item"><i class="fas fa-minus-circle"></i></button>
                <button class="btn btn-success btn-sm add-item"><i class="fas fa-plus-circle"></i></button>
                <button class="btn btn-danger btn-sm remove-item"><i class="fas fa-trash"></i> Hapus</button>
            </td>
        `;

                newRow.querySelector('.add-item').addEventListener('click', function() {
                    let qty = parseInt(newRow.cells[3].textContent);
                    let beratSatuan = parseInt(newRow.getAttribute('data-berat'));
                    let beratTotal = parseInt(newRow.cells[5].textContent);
                    let stok = parseInt(selectedOption.getAttribute('data-stok'));

                    if (qty + 1 > stok) {
                        Notiflix.Notify.failure("Jumlah melebihi stok!");
                        return;
                    }

                    qty += 1;
                    beratTotal += beratSatuan;

                    const harga = parseInt(newRow.getAttribute('data-harga'));
                    const hargaGrosir = parseInt(newRow.getAttribute('data-harga-grosir'));
                    const hargaPengampu = parseInt(newRow.getAttribute('data-harga-pengampu'));
                    const hargaJual = getHargaByBerat(beratTotal, harga, hargaGrosir, hargaPengampu);
                    const statusHarga = getStatusByBerat(beratTotal);

                    newRow.cells[2].textContent = rupiahFormat(hargaJual);
                    newRow.cells[2].setAttribute('data-status', statusHarga);
                    newRow.cells[3].textContent = qty;
                    newRow.cells[4].textContent = rupiahFormat(hargaJual * qty);
                    newRow.cells[5].textContent = beratTotal;

                    updateFooter();
                });

                newRow.querySelector('.subtract-item').addEventListener('click', function() {
                    let qty = parseInt(newRow.cells[3].textContent);
                    let beratSatuan = parseInt(newRow.getAttribute('data-berat'));
                    let beratTotal = parseInt(newRow.cells[5].textContent);

                    if (qty > 1) {
                        qty -= 1;
                        beratTotal -= beratSatuan;

                        const harga = parseInt(newRow.getAttribute('data-harga'));
                        const hargaGrosir = parseInt(newRow.getAttribute('data-harga-grosir'));
                        const hargaPengampu = parseInt(newRow.getAttribute('data-harga-pengampu'));
                        const hargaJual = getHargaByBerat(beratTotal, harga, hargaGrosir, hargaPengampu);
                        const statusHarga = getStatusByBerat(beratTotal);

                        newRow.cells[2].textContent = rupiahFormat(hargaJual);
                        newRow.cells[2].setAttribute('data-status', statusHarga);
                        newRow.cells[3].textContent = qty;
                        newRow.cells[4].textContent = rupiahFormat(hargaJual * qty);
                        newRow.cells[5].textContent = beratTotal;
                    } else {
                        newRow.remove();
                    }

                    updateFooter();
                });

                newRow.querySelector('.remove-item').addEventListener('click', function() {
                    newRow.remove();
                    updateFooter();
                });
            }

            updateFooter();

            $('#produk_id').val('').change();
            $('#foto-produk').attr('src', `assets/default.jpg`);
            $('#stok').val('');
            $('#berat').val('');
            $('#harga').val('');
            $('#harga_grosir').val('');
            $('#harga_pengampu').val('');
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
            // ubah_metode_pembayaran();
            // get_data_ongkir();
        }

        function ubah_metode_pembayaran() {
            var metode = $('#metode_pembayaran').val();
            var jarak = parseFloat($('#jarak').val());
            var city_id = $('#city_id').val();
            var berat = parseFloat($('#total-berat').text()) * 1000;

            $('#sub_total').val($('#total-semua').text());
            if (metode == "1") {
                $('#form_kurir_raja_ongkir').hide();
                $('#form_harga_ongkir').show();
                if (city_id > 0 && berat > 0) {
                    if ((city_id == 178 || city_id == 179) && berat >= 5000) {
                        const ongkir = hitungOngkir(jarak, berat);
                        $('#nama_ekspedisi').val('Lokal');
                        $('#harga_ekspedisi').val(ongkir);
                        $('#harga_ongkir').val(rupiahFormat(ongkir));
                    } else {
                        Notiflix.Notify.failure('COD hanya berlaku untuk area kediri dan berat >= 5kg');
                        $('#metode_pembayaran').val(0).change();
                        get_data_ongkir();
                    }
                }
            } else {
                $('#form_harga_ongkir').hide();
                $('#form_kurir_raja_ongkir').show();
            }
        }

        function hitungOngkir(jarak, total_berat) {
            let ongkirJarak, ongkirBerat;

            if (jarak < 1000) {
                ongkirJarak = 2000;
            } else {
                ongkirJarak = (jarak / 1000) * 2000;
            }

            if (total_berat < 5000) {
                ongkirBerat = 1000;
            } else {
                ongkirBerat = (total_berat / 5000) * 1000;
            }

            return Math.round(ongkirJarak + ongkirBerat);
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
                    status_harga: row.cells[2].getAttribute('data-status'),
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
                            payWithMidtrans(response.snap_token, response.order_id);
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

        function payWithMidtrans(snapToken, order_id) {
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    Notiflix.Notify.success('Pembayaran Berhasil');
                    const finishRedirectUrl = '/belanja/sukses';
                    const orderId = result.order_id;
                    window.location.href = `${finishRedirectUrl}/${orderId}`;
                },
                onPending: function(result) {
                    Notiflix.Notify.warning('Pembayaran Pending!');
                    const finishRedirectUrl = '/belanja/gagal';
                    const orderId = result.order_id;
                    window.location.href = `${finishRedirectUrl}/${orderId}`;
                },
                onError: function(result) {
                    Notiflix.Notify.failure('Terjadi kesalahan pada pembayaran!');
                    const finishRedirectUrl = '/belanja/gagal';
                    const orderId = result.order_id;
                    window.location.href = `${finishRedirectUrl}/${orderId}`;
                },
                onClose: function() {
                    Notiflix.Notify.failure('Pembayaran Ditunda!');
                    const finishRedirectUrl = '/belanja/gagal';
                    const orderId = order_id;
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
            // ubah_metode_pembayaran();
            // get_data_ongkir();
        }
    </script>
@endsection
