@extends('layouts.main')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')
@section('page-subtitle', 'Pesanan/detail_pesanan')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Detail Pesanan</h4>
                <h6 class="card-subtitle mb-4">Pesanan #{{ $transaksi->kode_transaksi }}</h6>

                <div class="alert alert-info">
                    Halaman detail pesanan masih dalam pengembangan.
                </div>
            </div>
        </div>
    </div>
@endsection
