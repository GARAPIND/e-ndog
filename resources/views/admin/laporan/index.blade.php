@extends('layouts.main')

@section('title', 'Laporan')
@section('page-title', 'Laporan')
@section('page-subtitle', 'Laporan/index')

@section('content')
    <style>
        /* Enhanced Dashboard Cards Styling */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.6) 50%, rgba(255, 255, 255, 0.3) 100%);
        }

        .card-body {
            padding: 2rem 1.5rem;
            position: relative;
        }

        .card-title {
            font-size: 0.95rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .card h3,
        .card h6 {
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            font-size: 2.5rem;
            line-height: 1;
        }

        .card h6 {
            font-size: 1.4rem;
        }

        /* Custom gradient backgrounds */
        .bg-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        }

        .bg-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%) !important;
        }

        .bg-info {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%) !important;
        }

        .bg-primary {
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%) !important;
        }

        /* Icon placeholders - add icons if needed */
        .card-body::after {
            content: '';
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            opacity: 0.6;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem 1rem;
            }

            .card h3 {
                font-size: 2rem;
            }

            .card h6 {
                font-size: 1.2rem;
            }
        }

        /* Animation for numbers */
        .card h3,
        .card h6 {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Additional enhancement - subtle pattern overlay */
        .card-body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Laporan Stok Masuk & Keluar</h4>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success btn-sm" onclick="unduh_laporan_stok('excel')">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="unduh_laporan_stok('pdf')">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="filter_periode_stok">Filter Periode</label>
                                    <select id="filter_periode_stok" class="form-control">
                                        <option value="">Semua Data</option>
                                        <option value="hari">Harian</option>
                                        <option value="minggu">Mingguan</option>
                                        <option value="bulan">Bulanan</option>
                                        <option value="tahun">Tahunan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12" id="detail_filter_stok" style="display: none;">
                                <div class="filter-hari" style="display: none;">
                                    <div class="form-group">
                                        <label>Pilih Tanggal</label>
                                        <input type="date" id="tanggal_stok" class="form-control">
                                    </div>
                                </div>

                                <div class="filter-minggu" style="display: none;">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Minggu Ke</label>
                                                <select id="minggu_stok" class="form-control">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Bulan</label>
                                                <select id="bulan_minggu_stok" class="form-control">
                                                    <option value="1">Januari</option>
                                                    <option value="2">Februari</option>
                                                    <option value="3">Maret</option>
                                                    <option value="4">April</option>
                                                    <option value="5">Mei</option>
                                                    <option value="6">Juni</option>
                                                    <option value="7">Juli</option>
                                                    <option value="8">Agustus</option>
                                                    <option value="9">September</option>
                                                    <option value="10">Oktober</option>
                                                    <option value="11">November</option>
                                                    <option value="12">Desember</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Tahun</label>
                                                <select id="tahun_minggu_stok" class="form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-bulan" style="display: none;">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Bulan</label>
                                                <select id="bulan_stok" class="form-control">
                                                    <option value="1">Januari</option>
                                                    <option value="2">Februari</option>
                                                    <option value="3">Maret</option>
                                                    <option value="4">April</option>
                                                    <option value="5">Mei</option>
                                                    <option value="6">Juni</option>
                                                    <option value="7">Juli</option>
                                                    <option value="8">Agustus</option>
                                                    <option value="9">September</option>
                                                    <option value="10">Oktober</option>
                                                    <option value="11">November</option>
                                                    <option value="12">Desember</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Tahun</label>
                                                <select id="tahun_bulan_stok" class="form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-tahun" style="display: none;">
                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <select id="tahun_stok" class="form-control"></select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Stok Masuk</h5>
                                        <h3 id="total_stok_masuk">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Stok Keluar</h5>
                                        <h3 id="total_stok_keluar">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 d-none">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Total Transaksi</h5>
                                        <h3 id="total_transaksi_stok">0</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">Stok Masuk</h4>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success btn-sm"
                                                onclick="unduh_excel_stok_masuk()">
                                                <i class="fas fa-file-excel"></i> Excel
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="unduh_excel_stok_masuk_pdf()">
                                                <i class="fas fa-file-pdf"></i> PDF
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3 d-none">
                                            <div class="col-md-12">
                                                <div class="card bg-success text-white">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title">Total Stok Masuk</h5>
                                                        <h3 id="total_masuk_only">0 kg</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="tabel_stok_masuk"
                                                class="table table-striped table-bordered table-sm">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Produk</th>
                                                        <th>Jumlah</th>
                                                        <th>Keterangan</th>
                                                        <th>Admin</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">Stok Keluar</h4>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success btn-sm"
                                                onclick="unduh_excel_stok_keluar()">
                                                <i class="fas fa-file-excel"></i> Excel
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="unduh_excel_stok_keluar_pdf()">
                                                <i class="fas fa-file-pdf"></i> PDF
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3 d-none">
                                            <div class="col-md-12">
                                                <div class="card bg-danger text-white">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title">Total Stok Keluar</h5>
                                                        <h3 id="total_keluar_only">0 kg</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="tabel_stok_keluar"
                                                class="table table-striped table-bordered table-sm">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Produk</th>
                                                        <th>Jumlah</th>
                                                        <th>Keterangan</th>
                                                        <th>Pembeli</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive d-none">
                            <table id="tabel_stok" class="table table-striped table-bordered table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Produk</th>
                                        <th>Tipe</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Laporan Pemasukan</h4>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success btn-sm"
                                onclick="unduh_laporan_pemasukan('excel')">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="unduh_laporan_pemasukan('pdf')">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="filter_periode_pemasukan">Filter Periode</label>
                                    <select id="filter_periode_pemasukan" class="form-control">
                                        <option value="">Semua Data</option>
                                        <option value="hari">Harian</option>
                                        <option value="minggu">Mingguan</option>
                                        <option value="bulan">Bulanan</option>
                                        <option value="tahun">Tahunan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12" id="detail_filter_pemasukan" style="display: none;">
                                <div class="filter-hari" style="display: none;">
                                    <div class="form-group">
                                        <label>Pilih Tanggal</label>
                                        <input type="date" id="tanggal_pemasukan" class="form-control">
                                    </div>
                                </div>

                                <div class="filter-minggu" style="display: none;">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Minggu Ke</label>
                                                <select id="minggu_pemasukan" class="form-control">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Bulan</label>
                                                <select id="bulan_minggu_pemasukan" class="form-control">
                                                    <option value="1">Januari</option>
                                                    <option value="2">Februari</option>
                                                    <option value="3">Maret</option>
                                                    <option value="4">April</option>
                                                    <option value="5">Mei</option>
                                                    <option value="6">Juni</option>
                                                    <option value="7">Juli</option>
                                                    <option value="8">Agustus</option>
                                                    <option value="9">September</option>
                                                    <option value="10">Oktober</option>
                                                    <option value="11">November</option>
                                                    <option value="12">Desember</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Tahun</label>
                                                <select id="tahun_minggu_pemasukan" class="form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-bulan" style="display: none;">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Bulan</label>
                                                <select id="bulan_pemasukan" class="form-control">
                                                    <option value="1">Januari</option>
                                                    <option value="2">Februari</option>
                                                    <option value="3">Maret</option>
                                                    <option value="4">April</option>
                                                    <option value="5">Mei</option>
                                                    <option value="6">Juni</option>
                                                    <option value="7">Juli</option>
                                                    <option value="8">Agustus</option>
                                                    <option value="9">September</option>
                                                    <option value="10">Oktober</option>
                                                    <option value="11">November</option>
                                                    <option value="12">Desember</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Tahun</label>
                                                <select id="tahun_bulan_pemasukan" class="form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-tahun" style="display: none;">
                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <select id="tahun_pemasukan" class="form-control"></select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Total Pemasukan</h5>
                                        <h6 id="total_pemasukan">Rp 0</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">COD</h5>
                                        <h6 id="pemasukan_cod">Rp 0</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Transfer</h5>
                                        <h6 id="pemasukan_transfer">Rp 0</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="tabel_pemasukan" class="table table-striped table-bordered table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kode</th>
                                        <th>Pelanggan</th>
                                        <th>Total</th>
                                        <th>Metode</th>
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
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            atur_opsi_tahun();
            inisialisasi_tabel();
            atur_event_filter();
            muat_ringkasan_data();
            inisialisasi_tabel_terpisah();
            muat_ringkasan_stok_terpisah();

            $('#filter_periode_stok').change(function() {
                muat_ulang_data_stok_terpisah();
            });

            $('#tanggal_stok, #minggu_stok, #bulan_minggu_stok, #tahun_minggu_stok, #bulan_stok, #tahun_bulan_stok, #tahun_stok')
                .change(function() {
                    muat_ulang_data_stok_terpisah();
                });
        });

        function atur_opsi_tahun() {
            const tahun_sekarang = new Date().getFullYear();
            let opsi_tahun = '';

            for (let tahun = tahun_sekarang; tahun >= tahun_sekarang - 5; tahun--) {
                opsi_tahun += `<option value="${tahun}">${tahun}</option>`;
            }

            const select_tahun = [
                '#tahun_minggu_stok', '#tahun_bulan_stok', '#tahun_stok',
                '#tahun_minggu_pemasukan', '#tahun_bulan_pemasukan', '#tahun_pemasukan'
            ];

            select_tahun.forEach(selector => {
                $(selector).html(opsi_tahun);
            });
        }

        function inisialisasi_tabel() {
            window.tabel_stok = $('#tabel_stok').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                },
                ajax: {
                    url: "{{ route('admin.laporan.stok.data') }}",
                    data: function(d) {
                        return dapatkan_parameter_filter_stok(d);
                    }
                },
                columns: [{
                        data: 'tanggal',
                        name: 'created_at'
                    },
                    {
                        data: 'produk_nama',
                        name: 'produk.nama'
                    },
                    {
                        data: 'tipe_label',
                        name: 'tipe'
                    },
                    {
                        data: 'jumlah_formatted',
                        name: 'jumlah_formatted'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'user_nama',
                        name: 'user.name'
                    }
                ]
            });

            window.tabel_pemasukan = $('#tabel_pemasukan').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                },
                ajax: {
                    url: "{{ route('admin.laporan.pemasukan.data') }}",
                    data: function(d) {
                        return dapatkan_parameter_filter_pemasukan(d);
                    }
                },
                columns: [{
                        data: 'tanggal_formatted',
                        name: 'tanggal_transaksi'
                    },
                    {
                        data: 'kode_transaksi',
                        name: 'kode_transaksi'
                    },
                    {
                        data: 'pelanggan_nama',
                        name: 'pelanggan.user.name'
                    },
                    {
                        data: 'total_formatted',
                        name: 'sub_total'
                    },
                    {
                        data: 'cod_label',
                        name: 'is_cod'
                    }
                ]
            });
        }

        function atur_event_filter() {
            $('#filter_periode_stok').change(function() {
                tampilkan_filter_detail('stok', $(this).val());
                muat_ulang_data_stok();
            });

            $('#filter_periode_pemasukan').change(function() {
                tampilkan_filter_detail('pemasukan', $(this).val());
                muat_ulang_data_pemasukan();
            });

            $('#tanggal_stok, #minggu_stok, #bulan_minggu_stok, #tahun_minggu_stok, #bulan_stok, #tahun_bulan_stok, #tahun_stok')
                .change(function() {
                    muat_ulang_data_stok();
                });

            $('#tanggal_pemasukan, #minggu_pemasukan, #bulan_minggu_pemasukan, #tahun_minggu_pemasukan, #bulan_pemasukan, #tahun_bulan_pemasukan, #tahun_pemasukan')
                .change(function() {
                    muat_ulang_data_pemasukan();
                });
        }

        function tampilkan_filter_detail(tipe, nilai_filter) {
            const container = tipe === 'stok' ? '#detail_filter_stok' : '#detail_filter_pemasukan';

            $(container).hide();
            $(container + ' .filter-hari, ' + container + ' .filter-minggu, ' + container + ' .filter-bulan, ' + container +
                ' .filter-tahun').hide();

            if (nilai_filter) {
                $(container).show();
                $(container + ' .filter-' + nilai_filter).show();
            }
        }

        function dapatkan_parameter_filter_stok(d) {
            d.filter_type = $('#filter_periode_stok').val();

            if (d.filter_type === 'hari') {
                d.filter_value = $('#tanggal_stok').val();
            } else if (d.filter_type === 'minggu') {
                d.minggu = $('#minggu_stok').val();
                d.bulan = $('#bulan_minggu_stok').val();
                d.tahun = $('#tahun_minggu_stok').val();
            } else if (d.filter_type === 'bulan') {
                d.bulan = $('#bulan_stok').val();
                d.tahun = $('#tahun_bulan_stok').val();
            } else if (d.filter_type === 'tahun') {
                d.tahun = $('#tahun_stok').val();
            }

            return d;
        }

        function dapatkan_parameter_filter_pemasukan(d) {
            d.filter_type = $('#filter_periode_pemasukan').val();

            if (d.filter_type === 'hari') {
                d.filter_value = $('#tanggal_pemasukan').val();
            } else if (d.filter_type === 'minggu') {
                d.minggu = $('#minggu_pemasukan').val();
                d.bulan = $('#bulan_minggu_pemasukan').val();
                d.tahun = $('#tahun_minggu_pemasukan').val();
            } else if (d.filter_type === 'bulan') {
                d.bulan = $('#bulan_pemasukan').val();
                d.tahun = $('#tahun_bulan_pemasukan').val();
            } else if (d.filter_type === 'tahun') {
                d.tahun = $('#tahun_pemasukan').val();
            }

            return d;
        }

        function muat_ulang_data_stok() {
            if (window.tabel_stok) {
                window.tabel_stok.ajax.reload();
                muat_ringkasan_stok();
            }
        }

        function muat_ulang_data_pemasukan() {
            if (window.tabel_pemasukan) {
                window.tabel_pemasukan.ajax.reload();
                muat_ringkasan_pemasukan();
            }
        }

        function muat_ringkasan_data() {
            muat_ringkasan_stok();
            muat_ringkasan_pemasukan();
        }

        function muat_ringkasan_stok() {
            const parameter = dapatkan_parameter_filter_stok({});

            $.ajax({
                url: "{{ route('admin.laporan.stok.ringkasan') }}",
                method: 'GET',
                data: parameter,
                success: function(response) {
                    $('#total_stok_masuk').text(response.total_masuk || 0);
                    $('#total_stok_keluar').text(response.total_keluar || 0);
                    $('#total_transaksi_stok').text(response.total_transaksi || 0);
                },
                error: function() {
                    console.log('Gagal memuat ringkasan stok');
                }
            });
        }

        function muat_ringkasan_pemasukan() {
            const parameter = dapatkan_parameter_filter_pemasukan({});

            $.ajax({
                url: "{{ route('admin.laporan.pemasukan.ringkasan') }}",
                method: 'GET',
                data: parameter,
                success: function(response) {
                    $('#total_pemasukan').text('Rp ' + format_angka(response.total_pemasukan || 0));
                    $('#pemasukan_cod').text('Rp ' + format_angka(response.pemasukan_cod || 0));
                    $('#pemasukan_transfer').text('Rp ' + format_angka(response.pemasukan_transfer || 0));
                },
                error: function() {
                    console.log('Gagal memuat ringkasan pemasukan');
                }
            });
        }

        function format_angka(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function unduh_laporan_stok(format) {
            if (format === 'excel') {

                const parameter = dapatkan_parameter_filter_stok({});
                $.ajax({
                    url: "{{ route('admin.laporan.stok.data') }}",
                    method: 'GET',
                    data: {
                        ...parameter,
                        length: -1, // Ambil semua data
                        start: 0
                    },
                    success: function(response) {
                        export_to_excel_stok(response.data);
                    }
                });
            } else {

                const parameter = dapatkan_parameter_filter_stok({});
                parameter.format = format;
                const url = "{{ route('admin.laporan.stok.unduh') }}" + '?' + $.param(parameter);
                window.open(url, '_blank');
            }
        }

        function unduh_laporan_pemasukan(format) {
            if (format === 'excel') {

                const parameter = dapatkan_parameter_filter_pemasukan({});


                $.ajax({
                    url: "{{ route('admin.laporan.pemasukan.data') }}",
                    method: 'GET',
                    data: {
                        ...parameter,
                        length: -1,
                        start: 0
                    },
                    success: function(response) {
                        export_to_excel_pemasukan(response.data);
                    }
                });
            } else {

                const parameter = dapatkan_parameter_filter_pemasukan({});
                parameter.format = format;
                const url = "{{ route('admin.laporan.pemasukan.unduh') }}" + '?' + $.param(parameter);
                window.open(url, '_blank');
            }
        }

        function export_to_excel_stok(data) {

            const wb = XLSX.utils.book_new();


            const ws_data = [
                ['Tanggal', 'Produk', 'Tipe', 'Jumlah', 'Keterangan', 'User']
            ];

            // Tambahkan data
            data.forEach(row => {
                ws_data.push([
                    row.tanggal,
                    row.produk_nama,
                    row.tipe,
                    row.jumlah + " kg",
                    row.keterangan,
                    row.user_nama
                ]);
            });


            const ws = XLSX.utils.aoa_to_sheet(ws_data);


            XLSX.utils.book_append_sheet(wb, ws, 'Laporan Stok');


            XLSX.writeFile(wb, 'laporan_stok_' + new Date().toISOString().split('T')[0] + '.xlsx');
        }

        function export_to_excel_pemasukan(data) {

            const wb = XLSX.utils.book_new();


            const ws_data = [
                ['Tanggal', 'Kode Transaksi', 'Pelanggan', 'Total', 'Metode']
            ];

            data.forEach(row => {
                const total = (row.sub_total + row.ongkir);
                const metode = row.is_cod ? 'COD' : 'Transfer';

                ws_data.push([
                    row.tanggal_formatted,
                    row.kode_transaksi,
                    row.pelanggan_nama,
                    total,
                    metode
                ]);
            });


            const ws = XLSX.utils.aoa_to_sheet(ws_data);
            XLSX.utils.book_append_sheet(wb, ws, 'Laporan Pemasukan');
            XLSX.writeFile(wb, 'laporan_pemasukan_' + new Date().toISOString().split('T')[0] + '.xlsx');
        }

        function inisialisasi_tabel_terpisah() {
            window.tabel_stok_masuk = $('#tabel_stok_masuk').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                },
                ajax: {
                    url: "{{ route('admin.laporan.stok.masuk.data') }}",
                    data: function(d) {
                        return dapatkan_parameter_filter_stok(d);
                    }
                },
                columns: [{
                        data: 'tanggal',
                        name: 'created_at'
                    },
                    {
                        data: 'produk_nama',
                        name: 'produk.nama'
                    },
                    {
                        data: 'jumlah_formatted',
                        name: 'jumlah_formatted'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'user_nama',
                        name: 'user.name'
                    }
                ]
            });

            window.tabel_stok_keluar = $('#tabel_stok_keluar').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                },
                ajax: {
                    url: "{{ route('admin.laporan.stok.keluar.data') }}",
                    data: function(d) {
                        return dapatkan_parameter_filter_stok(d);
                    }
                },
                columns: [{
                        data: 'tanggal',
                        name: 'created_at'
                    },
                    {
                        data: 'produk_nama',
                        name: 'produk.nama'
                    },
                    {
                        data: 'jumlah_formatted',
                        name: 'jumlah_formatted'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'user_nama',
                        name: 'user.name'
                    }
                ]
            });
        }

        function muat_ulang_data_stok_terpisah() {
            if (window.tabel_stok_masuk) {
                window.tabel_stok_masuk.ajax.reload();
            }
            if (window.tabel_stok_keluar) {
                window.tabel_stok_keluar.ajax.reload();
            }
            muat_ringkasan_stok_terpisah();
        }

        function muat_ringkasan_stok_terpisah() {
            const parameter = dapatkan_parameter_filter_stok({});

            $.ajax({
                url: "{{ route('admin.laporan.stok.ringkasan') }}",
                method: 'GET',
                data: parameter,
                success: function(response) {
                    $('#total_masuk_only').text(response.total_masuk || '0 kg');
                    $('#total_keluar_only').text(response.total_keluar || '0 kg');
                }
            });
        }

        function unduh_excel_stok_masuk_pdf() {
            format = 'masuk';
            const parameter = dapatkan_parameter_filter_stok({});
            parameter.format = format;
            const url = "{{ route('admin.laporan.stok.unduh') }}" + '?' + $.param(parameter);
            window.open(url, '_blank');
        }

        function unduh_excel_stok_keluar_pdf() {
            format = 'keluar';
            const parameter = dapatkan_parameter_filter_stok({});
            parameter.format = format;
            const url = "{{ route('admin.laporan.stok.unduh') }}" + '?' + $.param(parameter);
            window.open(url, '_blank');
        }

        function unduh_excel_stok_masuk() {
            const parameter = dapatkan_parameter_filter_stok({});
            $.ajax({
                url: "{{ route('admin.laporan.stok.masuk.data') }}",
                method: 'GET',
                data: {
                    ...parameter,
                    length: -1,
                    start: 0
                },
                success: function(response) {
                    export_to_excel_stok_masuk(response.data);
                }
            });
        }

        function unduh_excel_stok_keluar() {
            const parameter = dapatkan_parameter_filter_stok({});
            $.ajax({
                url: "{{ route('admin.laporan.stok.keluar.data') }}",
                method: 'GET',
                data: {
                    ...parameter,
                    length: -1,
                    start: 0
                },
                success: function(response) {
                    export_to_excel_stok_keluar(response.data);
                }
            });
        }

        function export_to_excel_stok_masuk(data) {
            const wb = XLSX.utils.book_new();
            const ws_data = [
                ['Tanggal', 'Produk', 'Jumlah', 'Keterangan', 'Admin']
            ];

            data.forEach(row => {
                ws_data.push([
                    row.tanggal,
                    row.produk_nama,
                    row.jumlah_formatted,
                    row.keterangan,
                    row.user_nama
                ]);
            });

            const ws = XLSX.utils.aoa_to_sheet(ws_data);
            XLSX.utils.book_append_sheet(wb, ws, 'Stok Masuk');
            XLSX.writeFile(wb, 'stok_masuk_' + new Date().toISOString().split('T')[0] + '.xlsx');
        }

        function export_to_excel_stok_keluar(data) {
            const wb = XLSX.utils.book_new();
            const ws_data = [
                ['Tanggal', 'Produk', 'Jumlah', 'Keterangan', 'Pembeli']
            ];

            data.forEach(row => {
                ws_data.push([
                    row.tanggal,
                    row.produk_nama,
                    row.jumlah_formatted,
                    row.keterangan,
                    row.user_nama
                ]);
            });

            const ws = XLSX.utils.aoa_to_sheet(ws_data);
            XLSX.utils.book_append_sheet(wb, ws, 'Stok Keluar');
            XLSX.writeFile(wb, 'stok_keluar_' + new Date().toISOString().split('T')[0] + '.xlsx');
        }
    </script>
@endsection
