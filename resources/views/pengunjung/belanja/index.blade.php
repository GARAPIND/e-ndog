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
                                    <select class="form-control" id="alamat" name="alamat">
                                        <option value="">Pilih alamat</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <h5>Tambah Pembelian</h5>
                                <div class="form-group">
                                    <input type="hidden" name="id_produk" id="id_produk">
                                    <label for="produk_id" class="col-form-label">Nama Produk</label>
                                    <select class="form-control" id="produk_id" name="produk_id">
                                        <option value="">Pilih produk</option>
                                        @foreach ($produk as $item)
                                            <option value="{{ $item->id }}" data-id-produk="{{ $item->id }}"
                                                data-kode=""="{{ $item->kode }}" data-nama="{{ $item->nama }}"
                                                data-stok="{{ intval($item->stok) }}"
                                                data-harga="{{ rupiahFormat($item->harga) }}"
                                                data-harga-diskon="{{ rupiahFormat($item->harga_diskon) }}"
                                                data-berat="{{ intval($item->berat) }}"
                                                data-satuan="{{ intval($item->satuan) }}">
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
                                    <input class="form-control" type="text" name="harga" id="harga" value="-"
                                        disabled>
                                </div>
                                <div class="form-group mt-0">
                                    <label for="harga_diskon" class="col-form-label">Harga Diskon</label>
                                    <input class="form-control" type="text" name="harga_diskon" id="harga_diskon"
                                        value="-" disabled>
                                </div>
                                <button class="btn btn-primary" id="btn_tambah"><i class="fas fa-plus-circle"></i>
                                    Tambahkan</button>
                            </div>
                            <div class="col-sm-8">
                                <div class="card card-body shadow-lg">
                                    <h5>Daftar Pembelian</h5>
                                    <div class="table_transaksi" style="width:100%; height: 400px; overflow-y: auto;">
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
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-danger float-left" id="btn_batal"><i
                                        class="fas fa-window-close"></i>
                                    Batalkan Transaksi</button>
                                <button class="btn btn-success float-right" id="btn_submit"><i
                                        class="fas fa-cart-plus"></i> Buat Transaksi</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
