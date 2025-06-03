@extends('layouts.main')
@section('title', 'Dashboard')
@section('page-title', 'Halo Admin')
@section('page-subtitle', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- *************************************************************** -->
        <!-- Start First Cards -->
        <!-- *************************************************************** -->
        <div class="card-group">
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <div class="d-inline-flex align-items-center">
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $pelanggan }}</h2>
                            </div>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Pelanggan</h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ $kurir }}</h2>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Kurir
                            </h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="truck"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <div class="d-inline-flex align-items-center">
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $produk }}</h2>
                            </div>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Stok Produk
                            </h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="package"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <h2 class="text-dark mb-1 font-weight-medium">{{ $produk_terjual }}</h2>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Produk Terjual</h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="shopping-bag"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <h4 class="card-title">Pembelian Pelanggan</h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table no-wrap v-middle mb-0">
                                <thead>
                                    <tr class="border-0">
                                        <th class="border-0 font-14 font-weight-medium text-muted">Pelanggan
                                        </th>
                                        <th class="border-0 font-14 font-weight-medium text-muted px-2">Telp
                                        </th>
                                        <th class="border-0 font-14 font-weight-medium text-muted text-center">
                                            Jumlah Transaksi
                                        </th>
                                        <th class="border-0 font-14 font-weight-medium text-muted text-center">
                                            Jumlah Produk Dibeli
                                        </th>
                                        <th class="border-0 font-14 font-weight-medium text-muted text-center">
                                            Total Transaksi
                                        </th>
                                        <th class="border-0 font-14 font-weight-medium text-muted text-center">
                                            Terakhir Pembelian
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @forelse ($pembelian as $item)
                                            <td class="border-top-0 px-2 py-4">
                                                <div class="d-flex no-block align-items-center">
                                                    <div class="mr-3">
                                                        <img src="{{ asset('storage/foto-user/' . $item->photo) }}"
                                                            alt="user" class="rounded-circle" width="45"
                                                            height="45" />
                                                    </div>
                                                    <div class="">
                                                        <h5 class="text-dark mb-0 font-16 font-weight-medium">
                                                            {{ $item->user->name }}</h5>
                                                        <span class="text-muted font-14">{{ $item->user->email }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="border-top-0 text-muted px-2 py-4 font-14">{{ $item->telp }}</td>
                                            <td class="border-top-0 text-muted px-2 py-4 font-14 text-center">
                                                {{ $item->transaksi->count() }}</td>
                                            <td class="border-top-0 text-muted px-2 py-4 font-14 text-center">
                                                {{ $item->transaksi->sum(function ($transaksi) {return $transaksi->detail->sum('jumlah');}) }}
                                            </td>
                                            <td class="border-top-0 text-muted px-2 py-4 font-14">
                                                {{ rupiahFormat($item->transaksi->sum('sub_total')) }}</td>
                                            <td class="border-top-0 text-muted px-2 py-4 font-14 text-center">
                                                {{ optional($item->transaksi->sortByDesc('tanggal_transaksi')->first())->tanggal_transaksi ? optional($item->transaksi->sortByDesc('tanggal_transaksi')->first())->tanggal_transaksi : '-' }}
                                            </td>

                                        @empty
                                        @endforelse
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
