@extends('layouts.main')
@section('title', 'History Pesanan')
@section('page-title', 'History Pesanan')
@section('page-subtitle', 'Pesanan/History Pesanan')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="card-title">Tabel History Pemesanan</h4>
                                <h6 class="card-subtitle">Di bawah ini adalah data pesanan yang statusnya <b
                                        class="text-success">SELESAI</b></h6>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="table-history" class="table table-striped table-bordered no-wrap">
                                <thead>
                                    <tr>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Total Belanja</th>
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
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            get_data();
        });

        function get_data() {
            let table = $("#table-history").DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('history.get_data') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'kode_transaksi',
                        className: 'text-center',
                        name: 'kode_transaksi',
                        orderable: false
                    },
                    {
                        data: 'tanggal_transaksi',
                        className: 'text-center',
                        name: 'tanggal_transaksi',
                    },
                    {
                        data: 'nama_pelanggan',
                        className: 'text-center',
                        name: 'nama_pelanggan',
                    },
                    {
                        data: 'total_belanja',
                        className: 'text-center',
                        name: 'total_belanja',
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
    </script>
@endsection
